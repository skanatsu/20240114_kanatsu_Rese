<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call(ShopsTableSeeder::class);
        $this->call(UsersTableSeeder::class);
        $this->call(ReservationsTableSeeder::class);
        $this->call(ReviewsTableSeeder::class);
        $this->call(FavoritesTableSeeder::class);
    }
}
