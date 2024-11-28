<?php

namespace Database\Factories;

use App\Enums\SubjectStatus;
use App\Models\Branch;
use App\Models\Subject;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class SubjectFactory extends Factory
{
    protected $model = Subject::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->randomElement([
                'Physics',
                'Mathematics',
                'Chemistry',
                'Mechanics',
                'English',
                'Electrical Technology',
                'Engineering Drawing and Graphics',
                'Programming',
                'Data Structures',
                'Computer Science and Programming',
                'Basic Chemistry',
                'Linear Algebra',
                'Data Structure and Algorithm',
                'Basic Electronics',
            ]),
            'code' => $this->faker->slug(),
            'description' => $this->faker->text(),
            'status' => $this->faker->randomElement(SubjectStatus::cases()),
            'semester' => $this->faker->randomDigitNotZero(),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
            'branch_id' => Branch::inRandomOrder()->first()->id,
        ];
    }
}
