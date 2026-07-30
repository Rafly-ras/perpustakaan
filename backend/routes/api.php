<?php

use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\BookController;
use App\Http\Controllers\Api\V1\MasterIdentityController;
use App\Http\Controllers\Api\V1\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| REST API Routes - Version 1
|--------------------------------------------------------------------------
*/

// Health Check Endpoint
Route::get('/health', function () {
    return response()->json([
        'success' => true,
        'message' => 'System is running normally',
        'data' => [
            'service' => 'Library Management System REST API',
            'version' => '1.0.0',
            'timestamp' => now()->toIso8601String(),
        ]
    ]);
});

// Public Authentication Endpoints
Route::prefix('auth')->group(function () {
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);

    // Protected Authentication Endpoints (Requires Sanctum Bearer Token)
    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/logout', [AuthController::class, 'logout']);
        Route::get('/me', [AuthController::class, 'me']);
    });
});

// Public / Authenticated OPAC Catalog Routes
Route::get('/categories', [BookController::class, 'categories']);
Route::get('/books', [BookController::class, 'index']);
Route::get('/books/{book}', [BookController::class, 'show']);

// Admin & Super Admin Book Catalog & Barcode Routes
Route::middleware(['auth:sanctum', 'role:admin,super_admin'])->group(function () {
    Route::post('/books', [BookController::class, 'store']);
    Route::put('/books/{book}', [BookController::class, 'update']);
    Route::delete('/books/{book}', [BookController::class, 'destroy']);
    Route::post('/books/{book}/copies', [BookController::class, 'addCopies']);
});

// Super Admin Protected Master & User Management Endpoints
Route::middleware(['auth:sanctum', 'role:super_admin'])->group(function () {
    // Master Identity Endpoints
    Route::post('/master-identities/import', [MasterIdentityController::class, 'import']);
    Route::get('/master-identities/export', [MasterIdentityController::class, 'export']);
    Route::apiResource('/master-identities', MasterIdentityController::class);

    // User Management Endpoints
    Route::post('/users/{user}/reset-password', [UserController::class, 'resetPassword']);
    Route::patch('/users/{user}/activate', [UserController::class, 'activate']);
    Route::patch('/users/{user}/deactivate', [UserController::class, 'deactivate']);
    Route::apiResource('/users', UserController::class);
});

// Protected Role-based Route Test Endpoints
Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('/dashboard/super-admin', function () {
        return response()->json(['success' => true, 'message' => 'Super Admin Dashboard Access Granted']);
    })->middleware('role:super_admin');

    Route::get('/dashboard/admin', function () {
        return response()->json(['success' => true, 'message' => 'Admin Dashboard Access Granted']);
    })->middleware('role:admin,super_admin');

    Route::get('/dashboard/student', function () {
        return response()->json(['success' => true, 'message' => 'Student Dashboard Access Granted']);
    })->middleware('role:mahasiswa,super_admin');

    Route::get('/dashboard/lecturer', function () {
        return response()->json(['success' => true, 'message' => 'Lecturer Dashboard Access Granted']);
    })->middleware('role:dosen,super_admin');
});
