<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePengumumanTable extends Migration
{
    public function up()
    {
        Schema::create('pengumuman', function (Blueprint $table) {
            $table->id();
            $table->string('judul', 200);
            $table->date('tanggal');
            $table->text('isi')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('pengumuman');
    }
}