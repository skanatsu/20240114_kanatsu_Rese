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
        // 'reservation_id',
        'score',
        'comment',
        'review_image_url',
    ];

    // public function reservation()
    // {
    //     return $this->belongsTo(Reservation::class, 'reservation_id');
    // }

    public function review()
    {
        return $this->hasOne(Review::class);
    }
}