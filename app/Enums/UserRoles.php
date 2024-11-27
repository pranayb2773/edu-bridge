<?php

namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasLabel;

enum UserRoles: int implements HasColor, HasLabel
{
    case STUDENT = 1;
    case TEACHER = 2;
    case ADMIN = 3;

    public function getLabel(): ?string
    {
        return match ($this) {
            self::STUDENT => 'Student',
            self::TEACHER => 'Teacher',
            self::ADMIN => 'Admin',
        };
    }

    public function getColor(): string|array|null
    {
        return match ($this) {
            self::STUDENT => 'primary',
            self::TEACHER => 'warning',
            self::ADMIN => 'danger',
        };
    }
}
