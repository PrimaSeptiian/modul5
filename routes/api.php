<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TodoController;
use App\Http\Controllers\AuthController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// Group Auth
Route::group(['middleware' => 'api', 'prefix' => 'auth'], function ($router) {
    Route::post('register', [AuthController::class, 'register']);
    Route::post('login', [AuthController::class, 'login']);
    Route::post('logout', [AuthController::class, 'logout'])->middleware('auth:api');
    Route::get('me', [AuthController::class, 'me'])->middleware('auth:api');
});

// Endpoint Public (GET)
Route::get('todos', [TodoController::class, 'index']);
Route::get('todos/{id}', [TodoController::class, 'show']);

// Endpoint Protected (POST, PUT, DELETE) -> Perlu Login
Route::middleware(['auth:api'])->group(function () {
    Route::post('todos', [TodoController::class, 'store']);
    Route::put('todos/{id}', [TodoController::class, 'update']);
    Route::delete('todos/{id}', [TodoController::class, 'destroy']);
});