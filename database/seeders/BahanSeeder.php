<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BahanSeeder extends Seeder
{
    public function run(): void
    {
        $bahans = [
            ['nama' => 'Daging Sapi', 'kategori' => 'Daging & Protein', 'stok' => 50, 'satuan' => 'kg', 'stok_minimal' => 10, 'harga_beli' => 120000],
            ['nama' => 'Daging Ayam', 'kategori' => 'Daging & Protein', 'stok' => 75, 'satuan' => 'kg', 'stok_minimal' => 15, 'harga_beli' => 35000],
            ['nama' => 'Telur Ayam', 'kategori' => 'Dairy & Egg', 'stok' => 200, 'satuan' => 'butir', 'stok_minimal' => 50, 'harga_beli' => 2000],
            ['nama' => 'Beras Premium', 'kategori' => 'Karbohidrat', 'stok' => 100, 'satuan' => 'kg', 'stok_minimal' => 20, 'harga_beli' => 15000],
            ['nama' => 'Minyak Goreng', 'kategori' => 'Bahan Pelengkap', 'stok' => 30, 'satuan' => 'liter', 'stok_minimal' => 10, 'harga_beli' => 18000],
            ['nama' => 'Bawang Merah', 'kategori' => 'Bumbu & Rempah', 'stok' => 15, 'satuan' => 'kg', 'stok_minimal' => 5, 'harga_beli' => 25000],
            ['nama' => 'Bawang Putih', 'kategori' => 'Bumbu & Rempah', 'stok' => 12, 'satuan' => 'kg', 'stok_minimal' => 4, 'harga_beli' => 30000],
            ['nama' => 'Wortel', 'kategori' => 'Sayuran', 'stok' => 20, 'satuan' => 'kg', 'stok_minimal' => 5, 'harga_beli' => 12000],
            ['nama' => 'Bayam', 'kategori' => 'Sayuran', 'stok' => 10, 'satuan' => 'ikat', 'stok_minimal' => 3, 'harga_beli' => 5000],
            ['nama' => 'Susu UHT', 'kategori' => 'Dairy & Egg', 'stok' => 50, 'satuan' => 'liter', 'stok_minimal' => 10, 'harga_beli' => 18000],
        ];

        foreach ($bahans as $bahan) {
            DB::table('bahans')->insert(array_merge($bahan, [
                'created_at' => now(),
                'updated_at' => now(),
            ]));
        }
    }
}