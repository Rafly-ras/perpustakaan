<?php

namespace Database\Seeders;

use App\Models\MasterIdentity;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $superAdminRole = Role::where('name', 'super_admin')->first();
        $adminRole = Role::where('name', 'admin')->first();
        $mahasiswaRole = Role::where('name', 'mahasiswa')->first();
        $dosenRole = Role::where('name', 'dosen')->first();

        // 1. Super Admin Account
        User::firstOrCreate(
            ['email' => 'admin@library.local'],
            [
                'name' => 'Super Administrator',
                'phone' => '081234567890',
                'password' => Hash::make('password'),
                'role' => 'super_admin',
                'role_id' => $superAdminRole?->id,
                'identity_number' => 'SA-001',
                'coin_balance' => 999,
                'status' => 'active',
                'is_active' => true,
            ]
        );

        // 2. Admin Operator Account
        User::firstOrCreate(
            ['email' => 'operator@library.local'],
            [
                'name' => 'Operator Sirkulasi Admin Desk',
                'phone' => '081234567891',
                'password' => Hash::make('password'),
                'role' => 'admin',
                'role_id' => $adminRole?->id,
                'identity_number' => 'ADM-001',
                'coin_balance' => 0,
                'status' => 'active',
                'is_active' => true,
            ]
        );

        // Master records for linking
        $mhsMaster = MasterIdentity::where('identity_number', '20261001')->first();
        $dosenMaster = MasterIdentity::where('identity_number', '198501012010')->first();

        // 3. Pre-registered Mahasiswa Account
        User::firstOrCreate(
            ['email' => 'mahasiswa@library.local'],
            [
                'master_identity_id' => $mhsMaster?->id,
                'name' => 'Budi Santoso',
                'phone' => '081234567892',
                'password' => Hash::make('password'),
                'role' => 'mahasiswa',
                'role_id' => $mahasiswaRole?->id,
                'nim' => '20261001',
                'identity_number' => '20261001',
                'coin_balance' => 5,
                'status' => 'active',
                'is_active' => true,
            ]
        );

        // 4. Pre-registered Dosen Account
        User::firstOrCreate(
            ['email' => 'dosen@library.local'],
            [
                'master_identity_id' => $dosenMaster?->id,
                'name' => 'Dr. Chairul Umam, M.Kom',
                'phone' => '081234567893',
                'password' => Hash::make('password'),
                'role' => 'dosen',
                'role_id' => $dosenRole?->id,
                'nidn' => '198501012010',
                'identity_number' => '198501012010',
                'coin_balance' => 10,
                'status' => 'active',
                'is_active' => true,
            ]
        );
    }
}
