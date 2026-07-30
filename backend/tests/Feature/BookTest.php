<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BookTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    public function test_anyone_can_list_books_in_opac(): void
    {
        $response = $this->getJson('/api/v1/books');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'message',
                'data' => [
                    '*' => ['id', 'isbn', 'title', 'total_copies', 'available_copies'],
                ],
            ]);
    }

    public function test_admin_can_create_book_and_auto_generate_copies(): void
    {
        $admin = User::where('email', 'admin@library.local')->first();
        $category = Category::first();

        $response = $this->actingAs($admin, 'sanctum')
            ->postJson('/api/v1/books', [
                'title' => 'Test Book Auto Barcode',
                'isbn' => '978-999-000-111-2',
                'publication_year' => 2026,
                'category_id' => $category->id,
                'copy_count' => 3,
                'author_names' => ['Penulis Test'],
            ]);

        $response->assertStatus(201)
            ->assertJson([
                'success' => true,
                'data' => [
                    'title' => 'Test Book Auto Barcode',
                    'total_copies' => 3,
                    'available_copies' => 3,
                ],
            ]);

        $this->assertDatabaseHas('books', ['isbn' => '978-999-000-111-2']);
    }
}
