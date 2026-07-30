<?php

namespace Database\Seeders;

use App\Models\Publisher;
use Illuminate\Database\Seeder;

class PublisherSeeder extends Seeder
{
    public function run(): void
    {
        $publishers = [
            ['name' => 'Prentice Hall', 'address' => 'Upper Saddle River, NJ', 'phone' => '+1-201-236-7000'],
            ['name' => 'O\'Reilly Media', 'address' => 'Sebastopol, CA', 'phone' => '+1-707-827-7000'],
            ['name' => 'Addison-Wesley', 'address' => 'Boston, MA', 'phone' => '+1-617-848-6000'],
            ['name' => 'Manning Publications', 'address' => 'Shelter Island, NY', 'phone' => '+1-203-626-1510'],
        ];

        foreach ($publishers as $pub) {
            Publisher::firstOrCreate(['name' => $pub['name']], $pub);
        }
    }
}
