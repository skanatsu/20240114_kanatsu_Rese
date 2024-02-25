<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Favorite;

class FavoritesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $userIds = range(1, 20);
        $shopIds = range(1, 20);

        $favorites = [];

        // 60件のデータを生成する
        for ($i = 0; $i < 60; $i++) {
            $userId = $this->getUniqueRandomId($userIds, $favorites);
            $shopId = $this->getUniqueRandomId($shopIds, $favorites);

            $favorites[] = [
                'user_id' => $userId,
                'shop_id' => $shopId,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        // 生成したデータを挿入する
        Favorite::insert($favorites);
    }

    /**
     * 重複しないランダムなIDを取得する
     *
     * @param array $ids
     * @param array $existing
     * @return int
     */
    private function getUniqueRandomId($ids, $existing)
    {
        $id = $ids[array_rand($ids)];
        return $id;
    }

}
