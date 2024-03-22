<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ShopCsvSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $filePath = storage_path('app/temp/shops.csv');
        $csvData = array_map('str_getcsv', file($filePath));
        unset($csvData[0]);
        foreach ($csvData as $data) {
            DB::table('shops')->insert([
                'shopname' => $data[0],
                'area' => $data[1],
                'genre' => $data[2],
                'description' => $data[3],
                'image_url' => $data[4],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}