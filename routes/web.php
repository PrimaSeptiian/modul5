<?php

use Illuminate\Support\Facades\Route;


// Route untuk Dashboard UAP
Route::get('/dashboard', function () {
    return view('dashboard');
});