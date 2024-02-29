<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Reservation;
use App\Models\Favorite;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Shop extends Model
{
    use HasFactory;

    protected $fillable = ['shopname', 'area', 'genre', 'description', 'image_url'];

    public function reservations()
    {
        return $this->hasMany(Reservation::class, 'shop_id');
    }

    public function getIsFavoriteAttribute()
    {
        return $this->favorites()->where('user_id', auth()->id())->exists();
    }
    public function favorites(): HasMany
    {
        return $this->hasMany(Favorite::class);
    }
}