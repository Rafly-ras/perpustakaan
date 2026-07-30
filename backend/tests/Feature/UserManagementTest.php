<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserManagementTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    public function test_super_admin_can_list_users(): void
    {
        $admin = User::where('email', 'admin@library.local')->first();

        $response = $this->actingAs($admin, 'sanctum')
            ->getJson('/api/v1/users');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'message',
                'data' => [
                    '*' => ['id', 'name', 'email', 'role', 'coin_balance'],
                ],
                'meta',
            ]);
    }

    public function test_super_admin_can_deactivate_and_activate_user(): void
    {
        $admin = User::where('email', 'admin@library.local')->first();
        $mahasiswa = User::where('email', 'mahasiswa@library.local')->first();

        // Deactivate
        $response = $this->actingAs($admin, 'sanctum')
            ->patchJson("/api/v1/users/{$mahasiswa->id}/deactivate");

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'data' => [
                    'status' => 'inactive',
                ],
            ]);

        // Activate
        $response2 = $this->actingAs($admin, 'sanctum')
            ->patchJson("/api/v1/users/{$mahasiswa->id}/activate");

        $response2->assertStatus(200)
            ->assertJson([
                'success' => true,
                'data' => [
                    'status' => 'active',
                ],
            ]);
    }

    public function test_super_admin_cannot_delete_super_admin_account(): void
    {
        $admin = User::where('email', 'admin@library.local')->first();

        $response = $this->actingAs($admin, 'sanctum')
            ->deleteJson("/api/v1/users/{$admin->id}");

        $response->assertStatus(422)
            ->assertJson(['success' => false]);
    }

    public function test_super_admin_can_reset_user_password(): void
    {
        $admin = User::where('email', 'admin@library.local')->first();
        $mahasiswa = User::where('email', 'mahasiswa@library.local')->first();

        $response = $this->actingAs($admin, 'sanctum')
            ->postJson("/api/v1/users/{$mahasiswa->id}/reset-password", [
                'password' => 'newpassword123',
            ]);

        $response->assertStatus(200)
            ->assertJson(['success' => true]);
    }
}
