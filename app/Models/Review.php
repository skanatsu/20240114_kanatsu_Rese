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
        'score',
        'comment',
        'review_image_url',
    ];

    public function review()
    {
        return $this->hasOne(Review::class);
    }
}