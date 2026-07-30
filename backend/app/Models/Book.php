<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Book extends Model
{
    use HasFactory;

    protected $fillable = [
        'isbn',
        'title',
        'category_id',
        'publisher_id',
        'publication_year',
        'description',
        'cover_image_path',
        'total_copies',
        'available_copies',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'publication_year' => 'integer',
            'total_copies' => 'integer',
            'available_copies' => 'integer',
        ];
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function publisher(): BelongsTo
    {
        return $this->belongsTo(Publisher::class, 'publisher_id');
    }

    public function authors(): BelongsToMany
    {
        return $this->belongsToMany(Author::class, 'book_author');
    }

    public function copies(): HasMany
    {
        return $this->hasMany(BookCopy::class, 'book_id');
    }

    public function updateStockCounts(): void
    {
        $total = $this->copies()->count();
        $available = $this->copies()->where('status', 'available')->count();
        $status = $available > 0 ? 'available' : 'out_of_stock';

        $this->update([
            'total_copies' => $total,
            'available_copies' => $available,
            'status' => $status,
        ]);
    }
}
