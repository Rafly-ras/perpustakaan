<?php

declare(strict_types=1);

namespace App\Http\Resources\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BookResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        /** @var \App\Models\Book $this */
        return [
            'id' => $this->id,
            'isbn' => $this->isbn,
            'title' => $this->title,
            'category_id' => $this->category_id,
            'category_name' => $this->category?->name ?? 'Umum',
            'publisher_id' => $this->publisher_id,
            'publisher_name' => $this->publisher?->name ?? '-',
            'authors' => $this->authors->pluck('name')->toArray(),
            'publication_year' => $this->publication_year,
            'description' => $this->description,
            'cover_image_url' => $this->cover_image_path ? (str_starts_with($this->cover_image_path, 'http') ? $this->cover_image_path : asset('storage/' . $this->cover_image_path)) : null,
            'total_copies' => (int) $this->total_copies,
            'available_copies' => (int) $this->available_copies,
            'status' => $this->status,
            'copies' => BookCopyResource::collection($this->whenLoaded('copies')),
            'created_at' => $this->created_at?->toIso8601String(),
        ];
    }
}
