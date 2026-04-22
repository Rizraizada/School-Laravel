<?php

namespace Database\Seeders;

use App\Models\SchoolClass;
use App\Models\Section;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::firstOrCreate(
            ['username' => 'admin'],
            [
                'full_name' => 'System Admin',
                'email' => 'admin@school.test',
                'password' => Hash::make('admin12345'),
                'role' => 'admin',
                'phone' => '01700000000',
                'gender' => 'male',
                'position' => 'Administrator',
            ]
        );

        User::firstOrCreate(
            ['username' => 'headmaster'],
            [
                'full_name' => 'Headmaster User',
                'email' => 'headmaster@school.test',
                'password' => Hash::make('head12345'),
                'role' => 'headmaster',
                'phone' => '01800000000',
                'gender' => 'male',
                'position' => 'Headmaster',
            ]
        );

        User::firstOrCreate(
            ['username' => 'teacher'],
            [
                'full_name' => 'Teacher User',
                'email' => 'teacher@school.test',
                'password' => Hash::make('teacher12345'),
                'role' => 'teacher',
                'phone' => '01900000000',
                'gender' => 'female',
                'position' => 'Teacher',
            ]
        );

        foreach (['Class 6', 'Class 7', 'Class 8', 'Class 9', 'Class 10'] as $className) {
            $schoolClass = SchoolClass::firstOrCreate(['class_name' => $className]);
            foreach (['A', 'B'] as $sectionName) {
                Section::firstOrCreate([
                    'class_id' => $schoolClass->id,
                    'section_name' => $sectionName,
                ]);
            }
        }

        $this->call(BangladeshCurriculumSeeder::class);
    }
}
