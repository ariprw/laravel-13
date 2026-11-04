<?php

namespace App\Services\Upload;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;
use RuntimeException;

class PhotoService
{
    private const MAX_FILE_SIZE = 10 * 1024 * 1024;
    private const MAX_DIMENSION = 8000;
    private const MAX_PIXELS = 12000000;
    private const JPG_QUALITY = 60;
    private const PNG_COMPRESSION = 7;

    public function upload(
        UploadedFile $file,
        string $folder = '',
        int $maxWidth = 1000
    ): string {
        if (!$file->isValid()) {
            throw new RuntimeException('File upload tidak valid');
        }

        if (!extension_loaded('gd')) {
            throw new RuntimeException('PHP GD belum tersedia');
        }

        if (
            !function_exists('imagecreatefromjpeg') ||
            !function_exists('imagecreatefrompng') ||
            !function_exists('imagecreatetruecolor') ||
            !function_exists('imagecopyresampled') ||
            !function_exists('imagejpeg') ||
            !function_exists('imagepng')
        ) {
            throw new RuntimeException('Fungsi PHP GD tidak lengkap');
        }

        if ($file->getSize() > self::MAX_FILE_SIZE) {
            throw new RuntimeException('Ukuran foto maksimal 10 MB');
        }

        $path = $file->getRealPath();

        if (!$path || !is_file($path) || !is_readable($path)) {
            throw new RuntimeException('File tidak dapat dibaca');
        }

        $info = @getimagesize($path);

        if ($info === false) {
            throw new RuntimeException('File bukan gambar valid');
        }

        [$width, $height, $type] = $info;

        if (!in_array($type, [
            IMAGETYPE_JPEG,
            IMAGETYPE_PNG,
        ], true)) {
            throw new RuntimeException('Format foto harus JPG atau PNG');
        }

        if (
            $width > self::MAX_DIMENSION ||
            $height > self::MAX_DIMENSION ||
            ($width * $height) > self::MAX_PIXELS
        ) {
            throw new RuntimeException('Dimensi foto terlalu besar');
        }

        if ($type === IMAGETYPE_JPEG) {
            $source = @imagecreatefromjpeg($path);
        } else {
            $source = @imagecreatefrompng($path);
        }

        if ($source === false) {
            throw new RuntimeException('Gagal membaca gambar');
        }

        $thumb = null;

        try {
            if ($type === IMAGETYPE_JPEG) {
                $source = $this->fixOrientation($source, $path);
            }

            $width = imagesx($source);
            $height = imagesy($source);

            $maxWidth = max(1, min($maxWidth, 2000));

            $ratio = $maxWidth / $width;

            $newWidth = $maxWidth;
            $newHeight = (int) round($height * $ratio);

            $newWidth = max(1, $newWidth);
            $newHeight = max(1, $newHeight);

            $thumb = imagecreatetruecolor(
                $newWidth,
                $newHeight
            );

            if ($thumb === false) {
                throw new RuntimeException('Gagal membuat gambar');
            }

            if ($type === IMAGETYPE_PNG) {
                imagealphablending($thumb, false);
                imagesavealpha($thumb, true);

                $transparent = imagecolorallocatealpha(
                    $thumb,
                    0,
                    0,
                    0,
                    127
                );

                imagefill($thumb, 0, 0, $transparent);
            } else {
                $white = imagecolorallocate(
                    $thumb,
                    255,
                    255,
                    255
                );

                imagefill($thumb, 0, 0, $white);
            }

            imagecopyresampled(
                $thumb,
                $source,
                0,
                0,
                0,
                0,
                $newWidth,
                $newHeight,
                $width,
                $height
            );

            $extension = $type === IMAGETYPE_PNG ? 'png' : 'jpg';
            $subFolder = $this->getFolder($folder);
            $folderPath = public_path('upload/'.$subFolder);

            if (
                !is_dir($folderPath) &&
                !mkdir($folderPath, 0755, true) &&
                !is_dir($folderPath)
            ) {
                throw new RuntimeException('Gagal membuat folder');
            }

            $filename = Str::uuid().'-'.Str::random(10).'.'.$extension;
            $destination = $folderPath.$filename;
            $temporary = $destination.'.tmp';

            if ($type === IMAGETYPE_PNG) {
                $saved = imagepng(
                    $thumb,
                    $temporary,
                    self::PNG_COMPRESSION
                );
            } else {
                $saved = imagejpeg(
                    $thumb,
                    $temporary,
                    self::JPG_QUALITY
                );
            }

            if (!$saved) {
                @unlink($temporary);
                throw new RuntimeException('Gagal menyimpan foto');
            }

            $feSize = $file->getSize();
            
            if ($feSize === null || $feSize <= 0) {
                throw new RuntimeException('Ukuran foto tidak valid');
            }

            clearstatcache(true, $temporary);

            $beSize = filesize($temporary);

            if ($beSize === false) {
                @unlink($temporary);
                throw new RuntimeException('Gagal membaca ukuran foto');
            }

            if ($feSize <= $beSize) {
                @unlink($temporary);

                if (!copy($file->getRealPath(), $destination)) {
                    throw new RuntimeException('Gagal menyimpan foto FE');
                }
            } else {
                if (!rename($temporary, $destination)) {
                    @unlink($temporary);
                    throw new RuntimeException('Gagal memindahkan foto BE');
                }
            }

            return 'upload/'.$subFolder.$filename;
        } finally {
            if ($thumb !== null) {
                imagedestroy($thumb);
            }

            imagedestroy($source);
        }
    }

    private function fixOrientation($source, string $path)
    {
        if (!function_exists('exif_read_data')) {
            return $source;
        }

        $exif = @exif_read_data($path);
        $orientation = (int) ($exif['Orientation'] ?? 1);

        $rotate = null;
        $flip = null;

        switch ($orientation) {
            case 2:
                $flip = IMG_FLIP_HORIZONTAL;
                break;

            case 3:
                $rotate = 180;
                break;

            case 4:
                $flip = IMG_FLIP_VERTICAL;
                break;

            case 5:
                $rotate = 90;
                $flip = IMG_FLIP_HORIZONTAL;
                break;

            case 6:
                $rotate = -90;
                break;

            case 7:
                $rotate = -90;
                $flip = IMG_FLIP_HORIZONTAL;
                break;

            case 8:
                $rotate = 90;
                break;
        }

        if ($rotate !== null) {
            $rotated = imagerotate($source, $rotate, 0);

            if ($rotated !== false) {
                imagedestroy($source);
                $source = $rotated;
            }
        }

        if ($flip !== null) {
            imageflip($source, $flip);
        }

        return $source;
    }

    private function getFolder(string $folder): string
    {
        $folder = strtolower(trim($folder, '/'));

        if ($folder !== '') {
            $parts = explode('/', $folder);

            $parts = array_map(
                fn ($part) => preg_replace(
                    '/[^a-zA-Z0-9_-]/',
                    '',
                    $part
                ),
                $parts
            );

            $folder = strtolower(implode('/', array_filter($parts)));
        }

        return ($folder !== '' ? $folder.'/' : '').date('ym').'/';
    }

    public function delete(?string $path): void
    {
        if (empty($path)) {
            return;
        }

        $basePath = realpath(public_path('upload'));

        if ($basePath === false) {
            return;
        }

        $filePath = public_path($path);
        $realPath = realpath($filePath);

        if (
            $realPath === false ||
            !is_file($realPath) ||
            !str_starts_with(
                $realPath,
                $basePath . DIRECTORY_SEPARATOR
            )
        ) {
            return;
        }

        @unlink($realPath);
    }
}