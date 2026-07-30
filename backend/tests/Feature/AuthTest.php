<?php

namespace Tests\Feature;

use App\Models\MasterIdentity;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    public function test_user_can_login_with_email_and_password(): void
    {
        $response = $this->postJson('/api/v1/auth/login', [
            'login' => 'admin@library.local',
            'password' => 'password',
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'message',
                'data' => [
                    'user' => ['id', 'name', 'email', 'role', 'coin_balance', 'permissions'],
                    'token',
                    'token_type',
                ],
            ])
            ->assertJson(['success' => true]);
    }

    public function test_mahasiswa_can_self_register(): void
    {
        MasterIdentity::create([
            'identity_number' => '20269999',
            'full_name' => 'Siswa Baru',
            'email' => 'siswabaru@student.ac.id',
            'role_type' => 'mahasiswa',
            'status' => 'active',
        ]);

        $response = $this->postJson('/api/v1/auth/register', [
            'name' => 'Siswa Baru',
            'email' => 'siswabaru@student.ac.id',
            'phone' => '081299001122',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'role' => 'mahasiswa',
            'nim' => '20269999',
        ]);

        $response->assertStatus(201)
            ->assertJson([
                'success' => true,
                'data' => [
                    'user' => [
                        'email' => 'siswabaru@student.ac.id',
                        'role' => 'mahasiswa',
                        'coin_balance' => 5,
                    ],
                ],
            ]);
    }

    public function test_dosen_can_self_register(): void
    {
        MasterIdentity::create([
            'identity_number' => '1990010199',
            'full_name' => 'Dosen Baru',
            'email' => 'dosenbaru@university.ac.id',
            'role_type' => 'dosen',
            'status' => 'active',
        ]);

        $response = $this->postJson('/api/v1/auth/register', [
            'name' => 'Dosen Baru',
            'email' => 'dosenbaru@university.ac.id',
            'phone' => '081299003344',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'role' => 'dosen',
            'nidn' => '1990010199',
        ]);

        $response->assertStatus(201)
            ->assertJson([
                'success' => true,
                'data' => [
                    'user' => [
                        'email' => 'dosenbaru@university.ac.id',
                        'role' => 'dosen',
                        'coin_balance' => 10,
                    ],
                ],
            ]);
    }

    public function test_user_can_fetch_me_profile(): void
    {
        $user = User::where('email', 'admin@library.local')->first();

        $response = $this->actingAs($user, 'sanctum')
            ->getJson('/api/v1/auth/me');

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'data' => [
                    'email' => 'admin@library.local',
                    'role' => 'super_admin',
                ],
            ]);
    }

    public function test_user_can_logout(): void
    {
        $user = User::where('email', 'admin@library.local')->first();
        $token = $user->createToken('test_token')->plainTextToken;

        $response = $this->withHeader('Authorization', "Bearer {$token}")
            ->postJson('/api/v1/auth/logout');

        $response->assertStatus(200)
            ->assertJson(['success' => true]);
    }
}
