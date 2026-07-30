<?php

namespace Database\Seeders;

use App\Models\Author;
use App\Models\Book;
use App\Models\BookCopy;
use App\Models\Category;
use App\Models\Publisher;
use Illuminate\Database\Seeder;

class BookSeeder extends Seeder
{
    public function run(): void
    {
        $seCategory = Category::where('code', 'SE')->first();
        $dbiCategory = Category::where('code', 'DBI')->first();
        $csCategory = Category::where('code', 'CS')->first();
        $beCategory = Category::where('code', 'BE')->first();

        $prenticeHall = Publisher::where('name', 'Prentice Hall')->first();
        $oreilly = Publisher::where('name', 'O\'Reilly Media')->first();
        $addison = Publisher::where('name', 'Addison-Wesley')->first();
        $manning = Publisher::where('name', 'Manning Publications')->first();

        $uncleBob = Author::where('name', 'LIKE', '%Robert C. Martin%')->first();
        $kleppmann = Author::where('name', 'LIKE', '%Martin Kleppmann%')->first();
        $gamma = Author::where('name', 'LIKE', '%Erich Gamma%')->first();
        $walls = Author::where('name', 'LIKE', '%Craig Walls%')->first();

        $booksData = [
            [
                'isbn' => '978-602-03-3160-7',
                'title' => 'Clean Architecture: A Craftsman\'s Guide to Software Structure',
                'category_id' => $seCategory?->id,
                'publisher_id' => $prenticeHall?->id,
                'publication_year' => 2018,
                'description' => 'A practical guide to software structure, design patterns, and maintainable software architecture.',
                'cover_image_path' => 'https://images.unsplash.com/photo-1532012197267-da84d127e765?auto=format&fit=crop&w=400&q=80',
                'authors' => [$uncleBob?->id],
                'copy_count' => 5,
            ],
            [
                'isbn' => '978-0-13-235088-4',
                'title' => 'Clean Code: A Handbook of Agile Software Craftsmanship',
                'category_id' => $seCategory?->id,
                'publisher_id' => $prenticeHall?->id,
                'publication_year' => 2008,
                'description' => 'Even bad code can function. But if code isn\'t clean, it can bring a development organization to its knees.',
                'cover_image_path' => 'https://images.unsplash.com/photo-1544716278-ca5e3f4abd8c?auto=format&fit=crop&w=400&q=80',
                'authors' => [$uncleBob?->id],
                'copy_count' => 3,
            ],
            [
                'isbn' => '978-1-4919-5035-7',
                'title' => 'Designing Data-Intensive Applications',
                'category_id' => $dbiCategory?->id,
                'publisher_id' => $oreilly?->id,
                'publication_year' => 2017,
                'description' => 'The big ideas behind reliable, scalable, and maintainable systems.',
                'cover_image_path' => 'https://images.unsplash.com/photo-1512820790803-83ca734da794?auto=format&fit=crop&w=400&q=80',
                'authors' => [$kleppmann?->id],
                'copy_count' => 4,
            ],
            [
                'isbn' => '978-0-201-63361-0',
                'title' => 'Design Patterns: Elements of Reusable Object-Oriented Software',
                'category_id' => $csCategory?->id,
                'publisher_id' => $addison?->id,
                'publication_year' => 1994,
                'description' => 'Capturing a wealth of experience about the design of object-oriented software.',
                'cover_image_path' => 'https://images.unsplash.com/photo-1497633762265-9d179a990aa6?auto=format&fit=crop&w=400&q=80',
                'authors' => [$gamma?->id],
                'copy_count' => 2,
            ],
            [
                'isbn' => '978-1-61729-456-3',
                'title' => 'Spring in Action, Fifth Edition',
                'category_id' => $beCategory?->id,
                'publisher_id' => $manning?->id,
                'publication_year' => 2018,
                'description' => 'Comprehensive guide to building enterprise Java backend microservices.',
                'cover_image_path' => 'https://images.unsplash.com/photo-1456513080510-7bf3a84b82f8?auto=format&fit=crop&w=400&q=80',
                'authors' => [$walls?->id],
                'copy_count' => 3,
            ],
        ];

        $copyCounter = 1000;

        foreach ($booksData as $bData) {
            $book = Book::firstOrCreate(
                ['isbn' => $bData['isbn']],
                [
                    'title' => $bData['title'],
                    'category_id' => $bData['category_id'],
                    'publisher_id' => $bData['publisher_id'],
                    'publication_year' => $bData['publication_year'],
                    'description' => $bData['description'],
                    'cover_image_path' => $bData['cover_image_path'],
                    'total_copies' => 0,
                    'available_copies' => 0,
                    'status' => 'available',
                ]
            );

            if (! empty($bData['authors'])) {
                $book->authors()->sync(array_filter($bData['authors']));
            }

            // Generate copies with unique barcodes LIB-2026-XXXX
            for ($i = 0; $i < $bData['copy_count']; $i++) {
                $copyCounter++;
                $barcode = sprintf('LIB-2026-X%04d', $copyCounter);
                BookCopy::firstOrCreate(
                    ['barcode' => $barcode],
                    [
                        'book_id' => $book->id,
                        'condition' => 'good',
                        'status' => 'available',
                    ]
                );
            }

            $book->updateStockCounts();
        }
    }
}
