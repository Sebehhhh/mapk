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
        Schema::table('scores', function (Blueprint $table) {
            $table->integer('attendance')->nullable()->change();
            $table->integer('assignment')->nullable()->change();
            $table->integer('mid_exam')->nullable()->change();
            $table->integer('final_exam')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('scores', function (Blueprint $table) {
            $table->integer('attendance')->default(0)->change();
            $table->integer('assignment')->default(0)->change();
            $table->integer('mid_exam')->default(0)->change();
            $table->integer('final_exam')->default(0)->change();
        });
    }
};
