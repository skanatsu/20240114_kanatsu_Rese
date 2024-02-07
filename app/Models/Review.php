<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'shop_id',
        'reservation_id',
        'score',
        'comment',
    ];

    // リレーションシップを定義する例（必要に応じて編集）
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function shop()
    {
        return $this->belongsTo(Shop::class);
    }

    // public function review()
    // {
    //     return $this->hasOne(Review::class, 'reservation_id');
    // }

    public function reservation()
    {
        return $this->belongsTo(Reservation::class, 'reservation_id');
    }

    public function review()
    {
        return $this->hasOne(Review::class);
    }

}