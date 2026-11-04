<?php

namespace App\Enums\User;

enum Gender: string
{
    case MALE = 'male';
    case FEMALE = 'female';

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }

    public function label(): string
    {
        return match ($this) {
            self::MALE => 'Laki-laki',
            self::FEMALE => 'Perempuan',
        };
    }

    public static function options(): array
    {
        return array_map(
            fn (self $gender) => [
                'value' => $gender->value,
                'label' => $gender->label(),
            ],
            self::cases()
        );
    }
}