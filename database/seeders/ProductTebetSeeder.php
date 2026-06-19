<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductTebetSeeder extends Seeder
{
    public function run(): void
    {
        // Memasukkan 5 data produk retail versi ringkas ke database southmart_tebet
        DB::connection('node_tebet')->table('products')->insert([
            [
                'barcode' => '8999906102001',
                'name' => 'Indomie Goreng Spesial 85g',
                'price' => 3100,
                'stock' => 120,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'barcode' => '8998866103002',
                'name' => 'Aqua Air Mineral Botol 600ml',
                'price' => 2500,
                'stock' => 80,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'barcode' => '8992761004003',
                'name' => 'Pepsodent Gigi Berlubang 190g',
                'price' => 14500,
                'stock' => 15,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'barcode' => '8991001105004',
                'name' => 'Chitato Sapi Panggang 68g',
                'price' => 11000,
                'stock' => 45,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'barcode' => '8999906106005',
                'name' => 'Biore Guard Body Wash Pouch 450ml',
                'price' => 28900,
                'stock' => 25,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);
    }
}