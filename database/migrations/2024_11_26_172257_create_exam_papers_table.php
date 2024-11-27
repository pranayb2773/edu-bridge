<?php

use App\Enums\ExamPaperStatus;
use App\Enums\ExamPaperType;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('exam_papers', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->tinyInteger('type')->default(ExamPaperType::MIDTERM);
            $table->tinyInteger('status')->default(ExamPaperStatus::ACTIVE->value);
            $table->longText('description')->nullable();
            $table->string('file_path')->nullable();
            $table->date('conducted_at')->nullable();
            $table->foreignId('subject_id')->constrained('subjects');
            $table->timestamps();
            $table->unique(['name', 'type', 'subject_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('exam_papers');
    }
};
