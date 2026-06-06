<?php

namespace Database\Seeders;

use App\Models\Course;
use Illuminate\Database\Seeder;

class CourseSeeder extends Seeder
{
    public function run(): void
    {
        $courses = [
            // Semester 1
            ['code' => '1000002003', 'name' => 'Bahasa Indonesia', 'sks' => 2, 'category' => 'General Education', 'semester' => 1, 'sort_order' => 1],
            ['code' => '2020102437', 'name' => 'FISIKA I', 'sks' => 2, 'category' => 'Basic Science & Math', 'semester' => 1, 'sort_order' => 2],
            ['code' => '2020103262', 'name' => 'KALKULUS I', 'sks' => 3, 'category' => 'Basic Science & Math', 'semester' => 1, 'sort_order' => 3],
            ['code' => '2020103440', 'name' => 'MATEMATIKA DISKRIT', 'sks' => 3, 'category' => 'Basic Science & Math', 'semester' => 1, 'sort_order' => 4],
            ['code' => '1000002018', 'name' => 'Pancasila', 'sks' => 2, 'category' => 'General Education', 'semester' => 1, 'sort_order' => 5],
            ['code' => '2020102441', 'name' => 'PENGANTAR TEKNIK ELEKTRO DAN TEKNOLOGI INFORMASI', 'sks' => 2, 'category' => 'Engineering Topics', 'semester' => 1, 'sort_order' => 6],
            
            // Peminatan / Pilihan (Sheet 2)
            ['code' => '2020103290', 'name' => 'EMBEDDED SYSTEM', 'sks' => 3, 'category' => 'Engineering Topics', 'semester' => 0, 'sort_order' => 1],
            ['code' => '2020102408', 'name' => 'Kecerdasan Artifisial dan Analisis Big Data', 'sks' => 2, 'category' => 'Engineering Topics', 'semester' => 0, 'sort_order' => 2],
            ['code' => '2020102386', 'name' => 'Cloud Computing', 'sks' => 2, 'category' => 'Engineering Topics', 'semester' => 0, 'sort_order' => 3],
        ];

        foreach ($courses as $course) {
            Course::create($course);
        }
    }
}