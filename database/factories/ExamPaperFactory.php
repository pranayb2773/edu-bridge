<?php

namespace Database\Factories;

use App\Enums\ExamPaperStatus;
use App\Enums\ExamPaperType;
use App\Models\ExamPaper;
use App\Models\Subject;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class ExamPaperFactory extends Factory
{
    protected $model = ExamPaper::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->name(),
            'type' => $this->faker->randomElement(ExamPaperType::cases()),
            'status' => $this->faker->randomElement(ExamPaperStatus::cases()),
            'description' => $this->faker->text(),
            'file_path' => $this->faker->word(),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),

            'subject_id' => Subject::factory(),
        ];
    }
}
