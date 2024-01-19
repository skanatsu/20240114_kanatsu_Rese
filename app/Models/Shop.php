<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Reservation;
use App\Models\User;
use App\Models\Favorite;

class Shop extends Model
{
    use HasFactory;

    protected $fillable = ['shopname', 'area', 'genre', 'description', 'image_id'];

    public function image()
    {
        return $this->belongsTo(Image::class);
    }
    public function reservations()
    {
        return $this->hasMany(Reservation::class, 'shop_id');
    }

    public function getIsFavoriteAttribute()
    {
        // 現在のユーザーがこの店舗をお気に入りにしているかどうかをチェック
        return $this->favorites()->where('user_id', auth()->id())->exists();
    }

    public function favorites()
    {
        // この店舗をお気に入りにしたユーザーとのリレーションシップ
        return $this->belongsToMany(User::class, 'favorites');
    }
}