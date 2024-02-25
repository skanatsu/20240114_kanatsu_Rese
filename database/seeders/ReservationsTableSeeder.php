<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Reservation;
use Faker\Factory as Faker;
use Carbon\Carbon;

class ReservationsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Fakerインスタンスを作成
        $faker = Faker::create();

        // Seederファイルを実行した日から前後1週間以内の日付を取得
        $startDate = Carbon::now()->subDays(7);
        $endDate = Carbon::now()->addDays(7);

        for ($i = 0; $i < 60; $i++) {
            // 1から20までのランダムなユーザーIDを生成
            $userId = $faker->numberBetween(1, 20);

            // 1から20までのランダムな店舗IDを生成
            $shopId = $faker->numberBetween(1, 20);

            // 1から10までのランダムな人数を生成
            $number = $faker->numberBetween(1, 10);

            // 前後1週間以内のランダムな日付を生成
            $date = $faker->dateTimeBetween($startDate, $endDate)->format('Y-m-d');

            // 時刻を30分刻みでランダムに生成（00分か30分のみ）
            $hour = $faker->numberBetween(0, 23);
            $minute = $faker->randomElement([0, 30]);
            $time = sprintf('%02d:%02d', $hour, $minute);

            // データを生成してReservationsテーブルに挿入
            Reservation::create([
                'user_id' => $userId,
                'shop_id' => $shopId,
                'date' => $date,
                'time' => $time,
                'number' => $number,
            ]);
        }
    }
}