<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        $roles = [
            ['name' => 'super_admin', 'display_name' => 'Super Admin'],
            ['name' => 'admin', 'display_name' => 'Admin Desk Sirkulasi'],
            ['name' => 'mahasiswa', 'display_name' => 'Mahasiswa'],
            ['name' => 'dosen', 'display_name' => 'Dosen'],
        ];

        foreach ($roles as $role) {
            Role::firstOrCreate(['name' => $role['name']], $role);
        }
    }
}
