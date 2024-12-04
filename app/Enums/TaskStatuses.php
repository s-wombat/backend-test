<?php

namespace App\Enums;

enum TaskStatuses: string {
    case To_do = "to_do";
    case In_progress = "in_progress";
    case Done = "done";

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}