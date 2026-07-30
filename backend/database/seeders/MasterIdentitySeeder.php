<?php

namespace Database\Seeders;

use App\Models\MasterIdentity;
use Illuminate\Database\Seeder;

class MasterIdentitySeeder extends Seeder
{
    public function run(): void
    {
        $identities = [
            // Mahasiswa Master Records
            [
                'identity_number' => '20261001',
                'full_name' => 'Budi Santoso',
                'email' => 'mahasiswa@library.local',
                'phone' => '081234567892',
                'role_type' => 'mahasiswa',
                'status' => 'active',
            ],
            [
                'identity_number' => '20261002',
                'full_name' => 'Siti Rahmawati',
                'email' => 'siti.rahma@student.ac.id',
                'phone' => '081234567894',
                'role_type' => 'mahasiswa',
                'status' => 'active',
            ],
            [
                'identity_number' => '20261003',
                'full_name' => 'Andi Wijaya',
                'email' => 'andi.wijaya@student.ac.id',
                'phone' => '081234567895',
                'role_type' => 'mahasiswa',
                'status' => 'active',
            ],
            // Dosen Master Records
            [
                'identity_number' => '198501012010',
                'full_name' => 'Dr. Chairul Umam, M.Kom',
                'email' => 'dosen@library.local',
                'phone' => '081234567893',
                'role_type' => 'dosen',
                'status' => 'active',
            ],
            [
                'identity_number' => '199002022015',
                'full_name' => 'Prof. Dr. Ir. H. Bambang Hartono, M.T.',
                'email' => 'bambang.h@university.ac.id',
                'phone' => '081234567896',
                'role_type' => 'dosen',
                'status' => 'active',
            ],
        ];

        foreach ($identities as $data) {
            MasterIdentity::firstOrCreate(
                ['identity_number' => $data['identity_number']],
                $data
            );
        }
    }
}
