<?php

namespace App\Enums\User;

enum Status: string 
{
    case PENDING = 'pending';
    case AKTIF = 'aktif';
    case NONAKTIF = 'nonaktif';

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
