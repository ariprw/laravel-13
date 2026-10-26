<?php

namespace App\Enums;

enum Delete: string 
{
    case YES = 'yes';

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
