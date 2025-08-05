<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run()
    {
        $categories = [
            [
                'name' => 'Matematika',
                'type' => 'student',
                'description' => 'Quiz matematika untuk pelajar'
            ],
            [
                'name' => 'Bahasa Indonesia',
                'type' => 'student',
                'description' => 'Quiz bahasa Indonesia untuk pelajar'
            ],
            [
                'name' => 'IPA',
                'type' => 'student',
                'description' => 'Quiz IPA untuk pelajar'
            ],
            [
                'name' => 'Pengetahuan Umum',
                'type' => 'public',
                'description' => 'Quiz pengetahuan umum untuk masyarakat'
            ],
            [
                'name' => 'Teknologi',
                'type' => 'public',
                'description' => 'Quiz tentang teknologi'
            ],
            [
                'name' => 'Sejarah',
                'type' => 'public',
                'description' => 'Quiz sejarah Indonesia'
            ]
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}