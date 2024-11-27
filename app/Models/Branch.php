<?php

namespace App\Models;

use App\Enums\BranchStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Branch extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'no_of_semesters',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'status' => BranchStatus::class,
        ];
    }
}
