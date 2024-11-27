<?php

namespace Database\Factories;

use App\Enums\BranchStatus;
use App\Models\Branch;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class BranchFactory extends Factory
{
    protected $model = Branch::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->randomElement([
                'Mechanical Engineering',
                'Computer Science and Engineering',
                'Civil Engineering',
                'Electronics and Computer Engineering',
                'Electronics and Electrical Engineering',
                'Information Technology',
                'Artificial Intelligence and Machine Learning',
                'Data Sciences',
                'BioTechnology',
                'Chemical Engineering',
                'Cyber Security',
                'Computer Engineering',
                'Automobile Engineering',
                'Telecom and Electronics Engineering',
                'Agricultural Engineering',
                'Food Technology',
            ]),
            'no_of_semesters' => $this->faker->randomDigitNotZero(),
            'status' => $this->faker->randomElement(BranchStatus::cases()),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
    }
}
