<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name' => 'Software Engineering', 'code' => 'SE', 'description' => 'Clean code, architecture & agile principles'],
            ['name' => 'Database & Infrastructure', 'code' => 'DBI', 'description' => 'PostgreSQL, Redis, Distributed systems'],
            ['name' => 'Computer Science', 'code' => 'CS', 'description' => 'Algorithms & data structures'],
            ['name' => 'Backend Development', 'code' => 'BE', 'description' => 'Laravel, Microservices, REST APIs'],
            ['name' => 'General Literature', 'code' => 'LIT', 'description' => 'General science and academic references'],
        ];

        foreach ($categories as $cat) {
            Category::firstOrCreate(['code' => $cat['code']], $cat);
        }
    }
}
