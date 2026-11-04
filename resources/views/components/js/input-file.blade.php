@once
<script src="{{ asset('js/pako.min.js') }}"></script>
<script src="{{ asset('js/upng.min.js') }}"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('input[type="file"]').forEach(formatInputFile);
    });

    function formatInputFile(input) {
        if (input.dataset.compressionInitialized) return;

        input.dataset.compressionInitialized = 'true';

        input.addEventListener('click', function () {
            this.value = '';
        });

        input.addEventListener('change', async function () {
            const originalFile = this.files?.[0];

            if (!originalFile) return;

            const inputElement = this;

            inputElement.disabled = true;

            try {
                const maxWidth = parseInt(inputElement.dataset.maxWidth, 10) || 1000;
                const compressedFile = await compressImage(originalFile, maxWidth);
                const saved = (
                    (1 - compressedFile.size / originalFile.size) * 100
                    ).toFixed(2);

                if (typeof DataTransfer === 'undefined') {
                    throw new Error('Browser tidak mendukung DataTransfer');
                }

                if (!(compressedFile instanceof File)) {
                    throw new Error('Hasil kompresi bukan File');
                }

                const transfer = new DataTransfer();
                transfer.items.add(compressedFile);

                inputElement.files = transfer.files;
            } catch (error) {
                inputElement.value = '';
            } finally {
                inputElement.disabled = false;
            }
        });
    }

    async function compressImage(file, maxWidth = 1000) {
        maxWidth = Math.max(1, parseInt(maxWidth, 10) || 1000);
        
        const isHeic = /\.(heic|heif)$/i.test(file.name) ||
        ['image/heic', 'image/heif'].includes(file.type);

        let sourceFile = file;

        if (isHeic) {
            if (typeof heic2any === 'undefined') {
                throw new Error('Library heic2any belum dimuat');
            }

            const converted = await heic2any({
                blob: file,
                toType: 'image/jpeg',
                quality: 0.9
            });

            const blob = Array.isArray(converted)
            ? converted[0]
            : converted;

            sourceFile = new File(
                [blob],
                file.name.replace(/\.(heic|heif)$/i, '.jpg'),
                { type: 'image/jpeg' }
                );
        }

        const isPng = sourceFile.type === 'image/png';
        const isJpg = sourceFile.type === 'image/jpeg';

        if (!isPng && !isJpg) {
            throw new Error('Format harus JPG atau PNG');
        }

        const url = URL.createObjectURL(sourceFile);

        try {
            const img = new Image();

            await new Promise((resolve, reject) => {
                img.onload = resolve;
                img.onerror = () => reject(
                    new Error('Gagal membaca gambar')
                    );
                img.src = url;
            });

            const ratio = maxWidth / img.width;

            const width = Math.max(1, Math.round(img.width * ratio));
            const height = Math.max(1, Math.round(img.height * ratio));

            const canvas = document.createElement('canvas');

            canvas.width = width;
            canvas.height = height;

            const ctx = canvas.getContext('2d', {
                willReadFrequently: true
            });

            if (!ctx) {
                throw new Error('Canvas tidak tersedia');
            }

            ctx.drawImage(img, 0, 0, width, height);

            if (isPng) {
                return await compressPng(
                    sourceFile,
                    ctx,
                    width,
                    height
                    );
            }

            return await compressJpg(
                sourceFile,
                canvas
                );

        } finally {
            URL.revokeObjectURL(url);
        }
    }

    async function compressPng(sourceFile, ctx, width, height) {
        if (typeof pako === 'undefined') {
            throw new Error('Pako.js belum dimuat');
        }

        if (typeof UPNG === 'undefined') {
            throw new Error('UPNG.js belum dimuat');
        }

        const rgba = ctx.getImageData(
            0, 0, width, height
            ).data;

        const encoded = UPNG.encode(
            [rgba.slice().buffer],
            width,
            height,
            192
            );

        const blob = new Blob(
            [encoded],
            { type: 'image/png' }
            );

        return new File(
            [blob],
            sourceFile.name,
            {
                type: 'image/png',
                lastModified: Date.now()
            }
            );
    }

    async function compressJpg(sourceFile, canvas) {
        const blob = await new Promise((resolve, reject) => {
            canvas.toBlob(
                result => result
                ? resolve(result)
                : reject(new Error('Gagal encode JPG')),
                'image/jpeg',
                0.6
                );
        });

        return new File(
            [blob],
            sourceFile.name.replace(/\.[^.]+$/, '') + '.jpg',
            {
                type: 'image/jpeg',
                lastModified: Date.now()
            }
            );
    }
</script>
@endonce