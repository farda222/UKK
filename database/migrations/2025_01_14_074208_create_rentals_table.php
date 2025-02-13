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
        Schema::create('rentals', function (Blueprint $table) {
            $table->id(); // Primary key
            $table->foreignId('customer_id')->constrained('customers')->onDelete('cascade'); // Relasi dengan customer
            $table->foreignId('car_id')->constrained('cars')->onDelete('cascade'); // Relasi dengan mobil
            $table->dateTime('mulai_sewa'); // Waktu mulai sewa
            $table->dateTime('selesai_sewa'); // Waktu selesai sewa
            $table->enum('status', ['pending', 'disetujui', 'dibatalkan', 'selesai'])->default('pending'); // Status booking
            $table->integer('total_harga'); // Total harga yang harus dibayar
            $table->timestamps(); // Kolom created_at dan updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rentals');
    }
};
