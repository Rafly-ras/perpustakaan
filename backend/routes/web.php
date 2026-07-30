<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return response()->json([
        'status' => 'success',
        'message' => 'Library Management System API V1 (Laravel 13)',
        'timestamp' => now()->toIso8601String(),
    ]);
});
