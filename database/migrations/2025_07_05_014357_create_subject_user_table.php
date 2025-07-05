<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('subject_user', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('subject_id')->constrained()->onDelete('cascade');
            $table->enum('semester', ['ganjil', 'genap']);
            $table->enum('class_level', ['X', 'XI', 'XII']);
            $table->year('year'); // Tipe YEAR (jika MySQL), jika pakai DB lain bisa string(4) atau integer
            $table->timestamps();

            $table->unique(['user_id', 'subject_id', 'semester', 'class_level', 'year'], 'subject_user_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('subject_user');
    }
};