<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('produk_kemasans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('produk_id')->constrained('produks')->onDelete('cascade');
            $table->string('nama'); 
            $table->integer('konversi'); 
            $table->integer('harga_jual'); 
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('produk_kemasan');
    }
};
