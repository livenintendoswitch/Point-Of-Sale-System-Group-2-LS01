<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('detail_penjualans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('penjualan_id')->constrained('penjualans')->onDelete('cascade');
            $table->foreignId('produk_id')->constrained('produks');
            $table->foreignId('produk_kemasan_id')->constrained('produk_kemasans');
            $table->integer('jumlah');
            $table->decimal('harga_satuan', 15, 2);
            $table->decimal('harga_beli', 15, 2); // <--- KOLOM INI YANG HILANG
            $table->decimal('subtotal', 15, 2);
            $table->integer('konversi_saat_itu'); // <--- PASTIKAN INI JUGA ADA
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('detail_penjualans');
    }
};
