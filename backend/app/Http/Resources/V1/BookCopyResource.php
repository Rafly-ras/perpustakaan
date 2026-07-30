<?php

declare(strict_types=1);

namespace App\Http\Resources\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BookCopyResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        /** @var \App\Models\BookCopy $this */
        return [
            'id' => $this->id,
            'book_id' => $this->book_id,
            'barcode' => $this->barcode,
            'condition' => $this->condition,
            'status' => $this->status,
            'created_at' => $this->created_at?->toIso8601String(),
        ];
    }
}
