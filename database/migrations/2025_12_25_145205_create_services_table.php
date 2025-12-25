<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('services', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();  // Kolom UUID (Wajib untuk fitur Delete/Show)
            $table->string('name');          // Kolom Name (Agar tidak error 'no column name')
            $table->decimal('price', 10, 2); // Kolom Price
            $table->text('description')->nullable(); // Kolom Description
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('services');
    }
};