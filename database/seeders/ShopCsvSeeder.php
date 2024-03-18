<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ShopCsvSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // CSVファイルのパス
        $filePath = storage_path('app/temp/shops.csv');

        // CSVファイルを読み込む
        $csvData = array_map('str_getcsv', file($filePath));

        // 1行目はヘッダーなのでスキップ
        unset($csvData[0]);

        // データをshopsテーブルに挿入
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