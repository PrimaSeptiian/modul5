<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            
            // Relasi ke User & Service (Wajib 'constrained' agar data valid)
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('service_id')->constrained()->onDelete('cascade');
            
            // Data Pemesan
            $table->string('name');
            $table->string('phone');
            $table->text('address');
            $table->date('booking_date');
            
            $table->string('status')->default('Pending'); // Pending, Confirmed, Completed
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};