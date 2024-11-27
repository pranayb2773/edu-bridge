<?php

namespace Database\Seeders;

use App\Enums\UserRoles;
use App\Enums\UserStatus;
use App\Models\Branch;
use App\Models\Subject;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Admin User
        User::create([
            'name' => 'Snithik Loka',
            'email' => 'snithik.loka@gmail.com',
            'password' => 'password',
            'status' => UserStatus::ACTIVE,
            'role' => UserRoles::ADMIN,
        ]);

        // Create fake 100 student users
        User::factory(100)->create([
            'role' => UserRoles::STUDENT,
        ]);

        // Create fake 20 teacher users
        User::factory(20)->create([
            'role' => UserRoles::TEACHER,
        ]);

        // Create fake branches
        collect([
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
        ])->each(function ($name) {
            Branch::factory()->create([
                'name' => $name,
            ]);
        });

        // Create fake 50 subjects
        Subject::factory(50)->create();
    }
}
