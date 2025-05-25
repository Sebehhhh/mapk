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
        Schema::create('scores', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained()->onDelete('cascade');
            $table->foreignId('subject_id')->constrained()->onDelete('cascade');
            $table->year('year');
            $table->enum('semester', ['ganjil', 'genap']);
            $table->integer('attendance')->default(0);
            $table->integer('assignment')->default(0);
            $table->integer('mid_exam')->default(0); // UTS
            $table->integer('final_exam')->default(0); // UAS
            $table->integer('total')->nullable();
            $table->integer('final_score')->nullable();
            $table->integer('rank')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('scores');
    }
};
