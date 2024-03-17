<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class UsersTableSeeder extends Seeder
{
    public function run(): void
    {
        User::query()->delete();

        $usersData = [
            [
                'name' => 'User1',
                'email' => 'user1@example.com',
                'password' => 'password1',
                'type' => 'general',
            ],
            [
                'name' => 'User2',
                'email' => 'user2@example.com',
                'password' => 'password2',
                'type' => 'general',
            ],
            [
                'name' => 'User3',
                'email' => 'user3@example.com',
                'password' => 'password3',
                'type' => 'general',
            ],
            [
                'name' => 'User4',
                'email' => 'user4@example.com',
                'password' => 'password4',
                'type' => 'general',
            ],
            [
                'name' => 'User5',
                'email' => 'user5@example.com',
                'password' => 'password5',
                'type' => 'general',
            ],
            [
                'name' => 'User6',
                'email' => 'user6@example.com',
                'password' => 'password6',
                'type' => 'general',
            ],
            [
                'name' => 'User7',
                'email' => 'user7@example.com',
                'password' => 'password7',
                'type' => 'general',
            ],
            [
                'name' => 'User8',
                'email' => 'user8@example.com',
                'password' => 'password8',
                'type' => 'general',
            ],
            [
                'name' => 'User9',
                'email' => 'user9@example.com',
                'password' => 'password9',
                'type' => 'general',
            ],
            [
                'name' => 'User10',
                'email' => 'user10@example.com',
                'password' => 'password10',
                'type' => 'general',
            ],
            [
                'name' => 'User11',
                'email' => 'user11@example.com',
                'password' => 'password11',
                'type' => 'shop',
            ],
            [
                'name' => 'User12',
                'email' => 'user12@example.com',
                'password' => 'password12',
                'type' => 'shop',
            ],
            [
                'name' => 'User13',
                'email' => 'user13@example.com',
                'password' => 'password13',
                'type' => 'shop',
            ],
            [
                'name' => 'User14',
                'email' => 'user14@example.com',
                'password' => 'password14',
                'type' => 'shop',
            ],
            [
                'name' => 'User15',
                'email' => 'user15@example.com',
                'password' => 'password15',
                'type' => 'shop',
            ],
            [
                'name' => 'User16',
                'email' => 'user16@example.com',
                'password' => 'password16',
                'type' => 'manage',
            ],
            [
                'name' => 'User17',
                'email' => 'user17@example.com',
                'password' => 'password17',
                'type' => 'manage',
            ],
            [
                'name' => 'User18',
                'email' => 'user18@example.com',
                'password' => 'password18',
                'type' => 'manage',
            ],
            [
                'name' => 'User19',
                'email' => 'user19@example.com',
                'password' => 'password19',
                'type' => 'manage',
            ],
            [
                'name' => 'User20',
                'email' => 'user20@example.com',
                'password' => 'password20',
                'type' => 'manage',
            ],
        ];

        foreach ($usersData as $userData) {
            User::create([
                'name' => $userData['name'],
                'email' => $userData['email'],
                'email_verified_at' => now(),
                'password' => bcrypt($userData['password']),
                'type' => $userData['type'],
            ]);
        }
    }
}