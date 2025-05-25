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
        Schema::table('students', function (Blueprint $table) {
            Schema::table('students', function (Blueprint $table) {
                $table->string('place_of_birth')->nullable();
                $table->string('religion')->nullable();
                $table->string('province')->nullable();
                $table->string('district')->nullable();
                $table->string('sub_district')->nullable();
                $table->string('village')->nullable();
                $table->string('origin_school_name')->nullable();
                $table->string('origin_school_address')->nullable();
                $table->year('graduation_year')->nullable();
                $table->string('photo')->nullable(); // path foto, opsional
            });
    
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('students', function (Blueprint $table) {
            Schema::table('students', function (Blueprint $table) {
                $table->dropColumn([
                    'place_of_birth', 'religion', 'province', 'district',
                    'sub_district', 'village', 'origin_school_name',
                    'origin_school_address', 'graduation_year', 'photo'
                ]);
            });    
        });
    }
};
