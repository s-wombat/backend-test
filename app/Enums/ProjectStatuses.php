<?php

namespace App\Enums;

enum ProjectStatuses: string {
    case Active = "active";
    case Completed = "completed";
    case Archived = "archived";

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}