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
        Schema::create('harga_produk', function (Blueprint $table) {
            $table->id();
            $table->foreignId('produk_id')
                ->constrained('produk')
                ->onDelete('cascade');

            $table->enum('jenis', ['satuan', 'slop', 'box']);
            $table->decimal('harga', 12, 2);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('harga_produk');
    }
};
