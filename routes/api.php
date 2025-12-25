<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TodoController;
use App\Http\Controllers\AuthController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// --- AUTH ROUTES ---
Route::group(['middleware' => 'api', 'prefix' => 'auth'], function ($router) {
    Route::post('register', [AuthController::class, 'register']);
    Route::post('login', [AuthController::class, 'login']);
    Route::post('logout', [AuthController::class, 'logout'])->middleware('auth:api');
    Route::get('me', [AuthController::class, 'me'])->middleware('auth:api');
});

// --- PUBLIC ROUTES (TUGAS POINT 2a & 2b) ---
// Bisa diakses siapa saja tanpa token
Route::get('todos', [TodoController::class, 'index']); // Get All
Route::get('todos/{id}', [TodoController::class, 'show']); // Get Detail

// --- PROTECTED ROUTES (TUGAS POINT 2c, 2d, 2e) ---
// Hanya bisa diakses jika punya Token (Login dulu)
Route::middleware(['auth:api'])->group(function () {
    Route::post('todos', [TodoController::class, 'store']);       // Create
    Route::put('todos/{id}', [TodoController::class, 'update']);  // Update
    Route::delete('todos/{id}', [TodoController::class, 'destroy']); // Delete
});