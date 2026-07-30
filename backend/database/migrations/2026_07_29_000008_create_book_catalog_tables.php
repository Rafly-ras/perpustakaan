<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Categories Table
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->string('code')->unique();
            $table->text('description')->nullable();
            $table->timestamps();
        });

        // 2. Publishers Table
        Schema::create('publishers', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('address')->nullable();
            $table->string('phone')->nullable();
            $table->timestamps();
        });

        // 3. Authors Table
        Schema::create('authors', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->nullable();
            $table->text('bio')->nullable();
            $table->timestamps();
        });

        // 4. Books Table
        Schema::create('books', function (Blueprint $table) {
            $table->id();
            $table->string('isbn')->unique();
            $table->string('title');
            $table->foreignId('category_id')->constrained('categories')->onDelete('restrict');
            $table->foreignId('publisher_id')->nullable()->constrained('publishers')->onDelete('set null');
            $table->integer('publication_year');
            $table->text('description')->nullable();
            $table->string('cover_image_path')->nullable();
            $table->integer('total_copies')->default(0);
            $table->integer('available_copies')->default(0);
            $table->enum('status', ['available', 'out_of_stock', 'maintenance'])->default('available');
            $table->timestamps();

            $table->index(['title', 'isbn', 'category_id', 'status']);
        });

        // 5. Book Copies (Eksemplar & Barcode Stiker)
        Schema::create('book_copies', function (Blueprint $table) {
            $table->id();
            $table->foreignId('book_id')->constrained('books')->onDelete('cascade');
            $table->string('barcode')->unique(); // e.g. LIB-2026-X89A
            $table->enum('condition', ['good', 'slightly_damaged', 'severely_damaged'])->default('good');
            $table->enum('status', ['available', 'borrowed', 'reserved', 'maintenance'])->default('available');
            $table->timestamps();

            $table->index(['barcode', 'status']);
        });

        // 6. Book Author Pivot
        Schema::create('book_author', function (Blueprint $table) {
            $table->id();
            $table->foreignId('book_id')->constrained('books')->onDelete('cascade');
            $table->foreignId('author_id')->constrained('authors')->onDelete('cascade');
            $table->timestamps();

            $table->unique(['book_id', 'author_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('book_author');
        Schema::dropIfExists('book_copies');
        Schema::dropIfExists('books');
        Schema::dropIfExists('authors');
        Schema::dropIfExists('publishers');
        Schema::dropIfExists('categories');
    }
};
