<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Shop;

class Favorite extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'shop_id',
    ];

    // 他のモデルとのリレーションシップを定義する場合はここに記述する

    // 例：FavoriteモデルとUserモデルのリレーションシップ
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // 例：FavoriteモデルとShopモデルのリレーションシップ
    public function shop()
    {
        return $this->belongsTo(Shop::class);
    }
}