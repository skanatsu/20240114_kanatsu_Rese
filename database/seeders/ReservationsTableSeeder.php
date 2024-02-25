<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Reservation;
use Faker\Factory as Faker;
use Carbon\Carbon;

class ReservationsTableSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create();

        $startDate = Carbon::now()->subDays(7);
        $endDate = Carbon::now()->addDays(7);

        for ($i = 0; $i < 60; $i++) {
            $userId = $faker->numberBetween(1, 20);
            $shopId = $faker->numberBetween(1, 20);
            $number = $faker->numberBetween(1, 10);
            $date = $faker->dateTimeBetween($startDate, $endDate)->format('Y-m-d');
            $hour = $faker->numberBetween(0, 23);
            $minute = $faker->randomElement([0, 30]);
            $time = sprintf('%02d:%02d', $hour, $minute);

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
