<?php

namespace App\Enums\User;

enum Status: string 
{
    case PENDING = 'pending';
    case ACTIVE = 'active';
    case INACTIVE = 'inactive';

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
    
    public function label(): string
    {
        return match ($this) {
            self::PENDING => 'Pending',
            self::ACTIVE => 'Aktif',
            self::INACTIVE => 'Nonaktif',
        };
    }

    public static function options(): array
    {
        return array_map(
            fn (self $status) => [
                'value' => $status->value,
                'label' => $status->label(),
            ],
            self::cases()
        );
    }
}
