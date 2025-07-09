<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('student_progresses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained()->cascadeOnDelete();
            $table->string('class_level', 5); // X, XI, XII
            $table->enum('semester', ['ganjil', 'genap']);
            $table->string('year', 9); // Contoh: 2024/2025
            $table->enum('status', ['aktif', 'lulus', 'naik', 'tinggal'])->default('aktif');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('student_progresses');
    }
};