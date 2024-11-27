<?php

namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasLabel;

enum ExamPaperType: int implements HasColor, HasLabel
{
    case MIDTERM = 1;
    case MAIN = 2;
    case SUPPLY = 3;

    public function getLabel(): ?string
    {
        return match ($this) {
            self::MIDTERM => 'Mid-Term Exam',
            self::MAIN => 'Main Exam',
            self::SUPPLY => 'Supply Exam',
        };
    }

    public function getColor(): string|array|null
    {
        return match ($this) {
            self::MIDTERM => 'primary',
            self::MAIN => 'warning',
            self::SUPPLY => 'danger',
        };
    }
}
