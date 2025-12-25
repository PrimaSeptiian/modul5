<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TodoController;
use App\Http\Controllers\AuthController;
// Import Controller untuk UAP
use App\Http\Controllers\BookingController;
use App\Http\Controllers\ServiceController;

// Ubah middleware ke 'auth:api' agar konsisten dengan JWT (bukan sanctum)
Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:api');

// --- AUTH ROUTES ---
Route::group(['middleware' => 'api', 'prefix' => 'auth'], function ($router) {
    Route::post('register', [AuthController::class, 'register']);
    Route::post('login', [AuthController::class, 'login']);
    Route::post('logout', [AuthController::class, 'logout'])->middleware('auth:api');
    Route::get('me', [AuthController::class, 'me'])->middleware('auth:api');
});

// --- PUBLIC ROUTES ---
// Bisa diakses tanpa login (Opsional, sesuaikan dengan kebutuhan)
Route::get('todos', [TodoController::class, 'index']);
Route::get('todos/{id}', [TodoController::class, 'show']);

// --- PROTECTED ROUTES ---
// Wajib Login (Bearer Token) untuk mengakses ini 
Route::middleware(['auth:api'])->group(function () {
    
    // 1. TODOS (Tugas Modul Sebelumnya)
    Route::post('todos', [TodoController::class, 'store']);
    Route::put('todos/{id}', [TodoController::class, 'update']);
    Route::delete('todos/{id}', [TodoController::class, 'destroy']);

    // 2. SERVICES (Master Data: Layanan Sedot WC)
    // Syarat: CRUD Master Data
    Route::get('services', [ServiceController::class, 'index']);         // Get List
    Route::post('services', [ServiceController::class, 'store']);        // Create
    Route::get('services/{uuid}', [ServiceController::class, 'show']);   // Detail (pakai UUID)
    Route::put('services/{uuid}', [ServiceController::class, 'update']); // Update (pakai UUID)
    Route::delete('services/{uuid}', [ServiceController::class, 'destroy']); // Delete (pakai UUID)

    // 3. BOOKINGS (Transaksi: Pemesanan)
    // Syarat: CRUD Transaksi yang berelasi dengan Master Data [cite: 38]
    Route::get('bookings', [BookingController::class, 'index']);         // Get List
    Route::post('bookings', [BookingController::class, 'store']);        // Create
    Route::get('bookings/{uuid}', [BookingController::class, 'show']);   // Detail (pakai UUID)
    Route::put('bookings/{uuid}', [BookingController::class, 'update']); // Update (pakai UUID)
    Route::delete('bookings/{uuid}', [BookingController::class, 'destroy']); // Delete (pakai UUID)
});