<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Reservation;
use App\Models\User;

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
}