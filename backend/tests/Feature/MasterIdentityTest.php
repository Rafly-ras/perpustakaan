<?php

namespace Tests\Feature;

use App\Models\MasterIdentity;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MasterIdentityTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    public function test_super_admin_can_list_master_identities(): void
    {
        $admin = User::where('email', 'admin@library.local')->first();

        $response = $this->actingAs($admin, 'sanctum')
            ->getJson('/api/v1/master-identities');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'message',
                'data' => [
                    '*' => ['id', 'identity_number', 'full_name', 'role_type', 'status'],
                ],
                'meta',
            ]);
    }

    public function test_super_admin_can_create_master_identity(): void
    {
        $admin = User::where('email', 'admin@library.local')->first();

        $response = $this->actingAs($admin, 'sanctum')
            ->postJson('/api/v1/master-identities', [
                'identity_number' => '20269999',
                'full_name' => 'Mahasiswa Test Baru',
                'email' => 'testbaru@student.ac.id',
                'phone' => '081299990000',
                'role_type' => 'mahasiswa',
                'status' => 'active',
            ]);

        $response->assertStatus(201)
            ->assertJson([
                'success' => true,
                'data' => [
                    'identity_number' => '20269999',
                    'full_name' => 'Mahasiswa Test Baru',
                ],
            ]);

        $this->assertDatabaseHas('master_identities', [
            'identity_number' => '20269999',
        ]);
    }

    public function test_registration_fails_if_nim_not_in_master_identities(): void
    {
        $response = $this->postJson('/api/v1/auth/register', [
            'name' => 'Unregistered Student',
            'email' => 'unregistered@student.ac.id',
            'phone' => '089900112233',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'role' => 'mahasiswa',
            'nim' => '99999999', // Not in master_identities table
        ]);

        $response->assertStatus(422)
            ->assertJson(['success' => false]);
    }

    public function test_super_admin_can_import_csv_master_identities(): void
    {
        $admin = User::where('email', 'admin@library.local')->first();

        $rows = [
            [
                'identity_number' => '20268801',
                'full_name' => 'Imported Student 1',
                'email' => 'imp1@student.ac.id',
                'phone' => '0811111111',
            ],
            [
                'identity_number' => '20268802',
                'full_name' => 'Imported Student 2',
                'email' => 'imp2@student.ac.id',
                'phone' => '0822222222',
            ],
            [
                'identity_number' => '20261001', // Already exists in seeder, should be SKIPPED
                'full_name' => 'Budi Santoso Duplicate',
                'email' => 'budi.dup@student.ac.id',
            ],
        ];

        $response = $this->actingAs($admin, 'sanctum')
            ->postJson('/api/v1/master-identities/import', [
                'role_type' => 'mahasiswa',
                'rows' => $rows,
            ]);

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'data' => [
                    'inserted' => 2,
                    'skipped' => 1,
                ],
            ]);
    }

    public function test_non_super_admin_cannot_access_master_identities(): void
    {
        $mahasiswa = User::where('email', 'mahasiswa@library.local')->first();

        $response = $this->actingAs($mahasiswa, 'sanctum')
            ->getJson('/api/v1/master-identities');

        $response->assertStatus(403);
    }
}
