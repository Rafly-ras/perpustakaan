<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder
{
    public function run(): void
    {
        $permissions = [
            // Master & Admin Module
            ['name' => 'master.identities.manage', 'module_name' => 'Master Identity'],
            ['name' => 'users.admin.crud', 'module_name' => 'User Management'],
            ['name' => 'settings.coin.configure', 'module_name' => 'Settings'],
            ['name' => 'settings.whatsapp.configure', 'module_name' => 'Settings'],

            // Catalog & Barcode Module
            ['name' => 'books.crud', 'module_name' => 'Catalog'],
            ['name' => 'barcodes.generate_and_print', 'module_name' => 'Barcode Generator'],
            ['name' => 'opac.search', 'module_name' => 'OPAC'],

            // Circulation Module
            ['name' => 'circulation.borrow', 'module_name' => 'Circulation'],
            ['name' => 'circulation.return', 'module_name' => 'Circulation'],
            ['name' => 'circulation.autocomplete', 'module_name' => 'Circulation'],

            // Reservation Module
            ['name' => 'reservations.create', 'module_name' => 'Reservation'],
            ['name' => 'profile.view_coins', 'module_name' => 'Profile'],
        ];

        foreach ($permissions as $permData) {
            Permission::firstOrCreate(['name' => $permData['name']], $permData);
        }

        // Assign Permissions to Roles
        $adminRole = Role::where('name', 'admin')->first();
        if ($adminRole) {
            $adminPerms = Permission::whereIn('name', [
                'books.crud',
                'barcodes.generate_and_print',
                'circulation.borrow',
                'circulation.return',
                'circulation.autocomplete',
                'opac.search',
                'profile.view_coins',
            ])->pluck('id');
            $adminRole->permissions()->sync($adminPerms);
        }

        $mahasiswaRole = Role::where('name', 'mahasiswa')->first();
        if ($mahasiswaRole) {
            $mahasiswaPerms = Permission::whereIn('name', [
                'opac.search',
                'reservations.create',
                'profile.view_coins',
            ])->pluck('id');
            $mahasiswaRole->permissions()->sync($mahasiswaPerms);
        }

        $dosenRole = Role::where('name', 'dosen')->first();
        if ($dosenRole) {
            $dosenPerms = Permission::whereIn('name', [
                'opac.search',
                'reservations.create',
                'profile.view_coins',
            ])->pluck('id');
            $dosenRole->permissions()->sync($dosenPerms);
        }
    }
}
