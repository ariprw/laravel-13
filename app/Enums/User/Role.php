<?php

namespace App\Enums\User;

enum Role: string 
{
    case ADMIN = 'admin';
    case USER = 'user';
    case KARYAWAN = 'karyawan';

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
