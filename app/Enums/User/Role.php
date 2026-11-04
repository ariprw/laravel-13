<?php

namespace App\Enums\User;

enum Role: string
{
    case ADMIN = 'admin';
    case USER = 'user';
    case EMPLOYEE = 'employee';

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }

    public function label(): string
    {
        return match ($this) {
            self::ADMIN => 'Admin',
            self::USER => 'User',
            self::EMPLOYEE => 'Karyawan',
        };
    }

    public static function options(): array
    {
        return array_map(
            fn (self $role) => [
                'value' => $role->value,
                'label' => $role->label(),
            ],
            self::cases()
        );
    }
}