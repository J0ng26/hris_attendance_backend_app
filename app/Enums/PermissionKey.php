<?php

namespace App\Enums;

enum PermissionKey: string
{
    case READ = 'read';
    case CREATE = 'create';
    case UPDATE = 'update';
    case DELETE = 'delete';

    public static function values(): array
    {
        return array_map(fn($case) => $case->value, self::cases());
    }
}
