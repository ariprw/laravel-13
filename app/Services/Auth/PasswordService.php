<?php

namespace App\Services\Auth;

use Illuminate\Support\Facades\Hash;
use RuntimeException;

class PasswordService
{
    public function hash(string $password): string
    {
        return Hash::make(
            $this->prepare($password),
            [
                'rounds' => (int) env('BCRYPT_ROUNDS', 12),
            ]
        );
    }

    public function check(
        string $password,
        string $hashedPassword
    ): bool {
        return Hash::check(
            $this->prepare($password),
            $hashedPassword
        );
    }

    private function prepare(string $password): string
    {
        $pepper = (string) env('PASSWORD_PEPPER');

        $split = (string) env(
            'PASSWORD_PEPPER_SPLIT'
        );

        if ($pepper === '') {
            throw new RuntimeException(
                'PASSWORD_PEPPER belum dikonfigurasi.'
            );
        }

        $positions = array_filter(
            array_map(
                'intval',
                explode(',', $split)
            ),
            fn ($position) => $position > 0
        );

        $positions = array_values(
            array_unique($positions)
        );

        sort($positions);

        if (empty($positions)) {
            return $pepper.$password;
        }

        $length = strlen($pepper);

        foreach ($positions as $position) {
            if ($position >= $length) {
                throw new RuntimeException(
                    'PASSWORD_PEPPER_SPLIT berada di luar panjang PASSWORD_PEPPER.'
                );
            }
        }

        $parts = [];
        $start = 0;

        foreach ($positions as $position) {
            $parts[] = substr(
                $pepper,
                $start,
                $position - $start
            );

            $start = $position;
        }

        $parts[] = substr(
            $pepper,
            $start
        );

        $result = '';

        foreach ($parts as $index => $part) {
            $result .= $part;

            if ($index < count($parts) - 1) {
                $result .= $password;
            }
        }

        return $result;
    }
}