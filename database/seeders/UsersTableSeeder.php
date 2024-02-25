<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        // 既存のユーザーデータを削除
        User::query()->delete();

        // 20名分のユーザーデータを生成
        $usersData = [
            [
                'name' => 'User1',
                'email' => 'user1@example.com',
                'password' => 'password1',
            ],
            [
                'name' => 'User2',
                'email' => 'user2@example.com',
                'password' => 'password2',
            ],
            [
                'name' => 'User3',
                'email' => 'user3@example.com',
                'password' => 'password3',
            ],
            [
                'name' => 'User4',
                'email' => 'user4@example.com',
                'password' => 'password4',
            ],
            [
                'name' => 'User5',
                'email' => 'user5@example.com',
                'password' => 'password5',
            ],
            [
                'name' => 'User6',
                'email' => 'user6@example.com',
                'password' => 'password6',
            ],
            [
                'name' => 'User7',
                'email' => 'user7@example.com',
                'password' => 'password7',
            ],
            [
                'name' => 'User8',
                'email' => 'user8@example.com',
                'password' => 'password8',
            ],
            [
                'name' => 'User9',
                'email' => 'user9@example.com',
                'password' => 'password9',
            ],
            [
                'name' => 'User10',
                'email' => 'user10@example.com',
                'password' => 'password10',
            ],
            [
                'name' => 'User11',
                'email' => 'user11@example.com',
                'password' => 'password11',
            ],
            [
                'name' => 'User12',
                'email' => 'user12@example.com',
                'password' => 'password12',
            ],
            [
                'name' => 'User13',
                'email' => 'user13@example.com',
                'password' => 'password13',
            ],
            [
                'name' => 'User14',
                'email' => 'user14@example.com',
                'password' => 'password14',
            ],
            [
                'name' => 'User15',
                'email' => 'user15@example.com',
                'password' => 'password15',
            ],
            [
                'name' => 'User16',
                'email' => 'user16@example.com',
                'password' => 'password16',
            ],
            [
                'name' => 'User17',
                'email' => 'user17@example.com',
                'password' => 'password17',
            ],
            [
                'name' => 'User18',
                'email' => 'user18@example.com',
                'password' => 'password18',
            ],
            [
                'name' => 'User19',
                'email' => 'user19@example.com',
                'password' => 'password19',
            ],
            [
                'name' => 'User20',
                'email' => 'user20@example.com',
                'password' => 'password20',
            ],
        ];

        // 生成したユーザーデータをデータベースに挿入
        foreach ($usersData as $userData) {
            User::create([
                'name' => $userData['name'],
                'email' => $userData['email'],
                'email_verified_at' => now(), // メール認証済みの状態として設定
                'password' => bcrypt($userData['password']), // パスワードをハッシュ化
            ]);
        }
    }
}