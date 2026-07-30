<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Author;
use App\Models\Book;
use App\Models\BookCopy;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class BookService
{
    public function getPaginated(array $filters = [], int $perPage = 12): LengthAwarePaginator
    {
        $query = Book::with(['category', 'publisher', 'authors', 'copies']);

        if (! empty($filters['search'])) {
            $search = $filters['search'];
            $query->where(function ($q) use ($search) {
                $q->where('title', 'ILIKE', "%{$search}%")
                  ->orWhere('isbn', 'ILIKE', "%{$search}%")
                  ->orWhereHas('authors', function ($aQ) use ($search) {
                      $aQ->where('name', 'ILIKE', "%{$search}%");
                  });
            });
        }

        if (! empty($filters['category_id'])) {
            $query->where('category_id', $filters['category_id']);
        }

        if (! empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        return $query->latest('id')->paginate($perPage);
    }

    public function create(array $data, $coverImageFile = null): Book
    {
        return DB::transaction(function () use ($data, $coverImageFile) {
            if ($coverImageFile) {
                $path = $coverImageFile->store('covers', 'public');
                $data['cover_image_path'] = $path;
            }

            $book = Book::create([
                'isbn' => $data['isbn'],
                'title' => $data['title'],
                'category_id' => $data['category_id'],
                'publisher_id' => $data['publisher_id'] ?? null,
                'publication_year' => (int) $data['publication_year'],
                'description' => $data['description'] ?? null,
                'cover_image_path' => $data['cover_image_path'] ?? null,
                'status' => 'available',
            ]);

            // Sync/Create Authors
            if (! empty($data['author_names'])) {
                $authorIds = [];
                foreach ($data['author_names'] as $name) {
                    $name = trim((string) $name);
                    if ($name) {
                        $author = Author::firstOrCreate(['name' => $name]);
                        $authorIds[] = $author->id;
                    }
                }
                $book->authors()->sync($authorIds);
            }

            // Generate initial copies (barcodes)
            $copyCount = isset($data['copy_count']) ? (int) $data['copy_count'] : 1;
            $this->generateCopies($book, $copyCount);

            return $book->load(['category', 'publisher', 'authors', 'copies']);
        });
    }

    public function update(Book $book, array $data, $coverImageFile = null): Book
    {
        return DB::transaction(function () use ($book, $data, $coverImageFile) {
            if ($coverImageFile) {
                if ($book->cover_image_path && ! str_starts_with($book->cover_image_path, 'http')) {
                    Storage::disk('public')->delete($book->cover_image_path);
                }
                $data['cover_image_path'] = $coverImageFile->store('covers', 'public');
            }

            $book->update([
                'isbn' => $data['isbn'],
                'title' => $data['title'],
                'category_id' => $data['category_id'],
                'publisher_id' => $data['publisher_id'] ?? null,
                'publication_year' => (int) $data['publication_year'],
                'description' => $data['description'] ?? null,
                'cover_image_path' => $data['cover_image_path'] ?? $book->cover_image_path,
            ]);

            if (isset($data['author_names'])) {
                $authorIds = [];
                foreach ($data['author_names'] as $name) {
                    $name = trim((string) $name);
                    if ($name) {
                        $author = Author::firstOrCreate(['name' => $name]);
                        $authorIds[] = $author->id;
                    }
                }
                $book->authors()->sync($authorIds);
            }

            $book->updateStockCounts();

            return $book->load(['category', 'publisher', 'authors', 'copies']);
        });
    }

    public function delete(Book $book): bool
    {
        if ($book->cover_image_path && ! str_starts_with($book->cover_image_path, 'http')) {
            Storage::disk('public')->delete($book->cover_image_path);
        }

        return (bool) $book->delete();
    }

    public function generateCopies(Book $book, int $count = 1): array
    {
        $createdCopies = [];
        $latestId = BookCopy::max('id') ?? 1000;

        for ($i = 0; $i < $count; $i++) {
            $latestId++;
            $barcode = sprintf('LIB-2026-X%04d', $latestId);

            $copy = BookCopy::create([
                'book_id' => $book->id,
                'barcode' => $barcode,
                'condition' => 'good',
                'status' => 'available',
            ]);

            $createdCopies[] = $copy;
        }

        $book->updateStockCounts();

        return $createdCopies;
    }
}
