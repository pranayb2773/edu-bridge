<?php

namespace App\Models;

use App\Enums\ExamPaperStatus;
use App\Enums\ExamPaperType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ExamPaper extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'type',
        'status',
        'description',
        'file_path',
        'subject_id',
        'conducted_at',
    ];

    protected function casts(): array
    {
        return [
            'type' => ExamPaperType::class,
            'status' => ExamPaperStatus::class,
            'conducted_at' => 'date',
        ];
    }

    public function subject(): BelongsTo
    {
        return $this->belongsTo(Subject::class);
    }
}
