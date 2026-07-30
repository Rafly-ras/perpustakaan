<?php

namespace Database\Seeders;

use App\Models\Author;
use Illuminate\Database\Seeder;

class AuthorSeeder extends Seeder
{
    public function run(): void
    {
        $authors = [
            ['name' => 'Robert C. Martin (Uncle Bob)', 'email' => 'unclebob@cleancoder.com'],
            ['name' => 'Martin Kleppmann', 'email' => 'martin@dataintensive.net'],
            ['name' => 'Erich Gamma', 'email' => 'erich@gangoffour.org'],
            ['name' => 'Craig Walls', 'email' => 'craig@manning.com'],
        ];

        foreach ($authors as $aut) {
            Author::firstOrCreate(['name' => $aut['name']], $aut);
        }
    }
}
