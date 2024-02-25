<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Favorite;

class FavoritesTableSeeder extends Seeder
{
    public function run()
    {
        $userIds = range(1, 20);
        $shopIds = range(1, 20);
        $favorites = [];

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

        Favorite::insert($favorites);
    }

    private function getUniqueRandomId($ids, $existing)
    {
        $id = $ids[array_rand($ids)];
        return $id;
    }
}
