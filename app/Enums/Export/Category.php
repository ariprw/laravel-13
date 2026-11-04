<?php

namespace App\Enums\Export;

enum Category: string
{
    case ALL = 'all';
    case CREATED_AT = 'created_at';

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }

    public function label(): string
    {
        return match ($this) {
            self::ALL => 'Semua',
            self::CREATED_AT => 'Tanggal Dibuat',
        };
    }

    public static function options(): array
    {
        return array_map(
            fn (self $category) => [
                'value' => $category->value,
                'label' => $category->label(),
            ],
            self::cases()
        );
    }
}