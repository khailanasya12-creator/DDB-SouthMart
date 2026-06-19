<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Membuat tabel 'products' di dalam database lokal
        Schema::connection('node_tebet')->create('products', function (Blueprint $table) {
            $table->id(); // Membuat kolom ID otomatis (Primary Key)
            $table->string('barcode')->unique();
            $table->string('name'); // Kolom untuk nama produk (contoh: 'Aqua 600ml')
            $table->integer('price'); // Kolom untuk harga produk (contoh: 5000)
            $table->integer('stock'); // Kolom untuk jumlah stok barang di toko
            $table->string('image')->nullable(); // Kolom opsional untuk menyimpan nama file foto produk
            $table->timestamps(); // Kolom otomatis 'created_at' dan 'updated_at'
        });
    }

    public function down(): void
    {
        Schema::connection('node_tebet')->dropIfExists('products');
    }
};