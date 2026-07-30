<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Book\StoreBookRequest;
use App\Http\Requests\Book\UpdateBookRequest;
use App\Http\Resources\V1\BookResource;
use App\Http\Resources\V1\CategoryResource;
use App\Models\Book;
use App\Models\Category;
use App\Services\BookService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class BookController extends Controller
{
    public function __construct(
        private readonly BookService $bookService
    ) {}

    public function index(Request $request): JsonResponse
    {
        $filters = $request->only(['search', 'category_id', 'status']);
        $perPage = (int) $request->input('per_page', 12);
        $paginated = $this->bookService->getPaginated($filters, $perPage);

        return response()->json([
            'success' => true,
            'message' => 'Katalog buku berhasil dimuat.',
            'data' => BookResource::collection($paginated->items()),
            'meta' => [
                'current_page' => $paginated->currentPage(),
                'last_page' => $paginated->lastPage(),
                'per_page' => $paginated->perPage(),
                'total' => $paginated->total(),
            ],
        ], Response::HTTP_OK);
    }

    public function store(StoreBookRequest $request): JsonResponse
    {
        $file = $request->file('cover_image');
        $book = $this->bookService->create($request->validated(), $file);

        return response()->json([
            'success' => true,
            'message' => 'Judul buku baru & stiker barcode eksemplar berhasil ditambahkan.',
            'data' => new BookResource($book),
        ], Response::HTTP_CREATED);
    }

    public function show(Book $book): JsonResponse
    {
        return response()->json([
            'success' => true,
            'message' => 'Detail pustaka dimuat.',
            'data' => new BookResource($book->load(['category', 'publisher', 'authors', 'copies'])),
        ], Response::HTTP_OK);
    }

    public function update(UpdateBookRequest $request, Book $book): JsonResponse
    {
        $file = $request->file('cover_image');
        $updated = $this->bookService->update($book, $request->validated(), $file);

        return response()->json([
            'success' => true,
            'message' => 'Data katalog buku berhasil diperbarui.',
            'data' => new BookResource($updated),
        ], Response::HTTP_OK);
    }

    public function destroy(Book $book): JsonResponse
    {
        $this->bookService->delete($book);

        return response()->json([
            'success' => true,
            'message' => 'Buku & seluruh eksemplar barcode berhasil dihapus.',
            'data' => null,
        ], Response::HTTP_OK);
    }

    public function addCopies(Request $request, Book $book): JsonResponse
    {
        $request->validate([
            'count' => ['required', 'integer', 'min:1', 'max:20'],
        ]);

        $copies = $this->bookService->generateCopies($book, (int) $request->input('count'));

        return response()->json([
            'success' => true,
            'message' => "Berhasil menambah {$request->input('count')} stiker barcode eksemplar baru.",
            'data' => new BookResource($book->fresh()->load(['category', 'publisher', 'authors', 'copies'])),
        ], Response::HTTP_OK);
    }

    public function categories(): JsonResponse
    {
        $categories = Category::all();

        return response()->json([
            'success' => true,
            'message' => 'Daftar kategori pustaka dimuat.',
            'data' => CategoryResource::collection($categories),
        ], Response::HTTP_OK);
    }
}
