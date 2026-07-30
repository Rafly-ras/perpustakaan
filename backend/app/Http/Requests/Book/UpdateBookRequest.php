<?php

declare(strict_types=1);

namespace App\Http\Requests\Book;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateBookRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $bookId = $this->route('book');

        return [
            'isbn' => ['required', 'string', 'max:50', Rule::unique('books', 'isbn')->ignore($bookId)],
            'title' => ['required', 'string', 'max:255'],
            'category_id' => ['required', 'integer', 'exists:categories,id'],
            'publisher_id' => ['nullable', 'integer', 'exists:publishers,id'],
            'publication_year' => ['required', 'integer', 'min:1900', 'max:' . (date('Y') + 1)],
            'description' => ['nullable', 'string'],
            'cover_image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'author_names' => ['nullable', 'array'],
        ];
    }
}
