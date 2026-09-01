<?php

namespace App\Enums;

enum UserRole: string
{
    case ADMIN = 'admin';
    case TEACHER = 'teacher';

    // Rótulo amigável para exibir nas telas/views
    public function label(): string
    {
        return match ($this) {
            self::ADMIN => 'Administrador',
            self::TEACHER => 'Professor',
        };
    }
}
