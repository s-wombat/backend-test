<?php

namespace App\Enums;

enum RoleTypes: string {
    case Admin = "admin";
    case Manager = "manager";
    case User = "user";

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}