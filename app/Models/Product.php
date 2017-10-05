<?php

namespace App\Models;

use App\Models\Detail;
use App\Models\Image;
use App\Models\Option;
use App\Models\Order;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'price',
        'category',
        'image',
    ];

    public function User()
    {
        return $this->belongsTo(User::class);
    }

    public function Options()
    {
        return $this->hasMany(Option::class);
    }

    public function Details()
    {
        return $this->hasOne(Detail::class);
    }

    public function Images()
    {
        return $this->hasMany(Image::class);
    }

    public function order()
    {
        return $this->belongsToMany(Order::class, 'orders_products');
    }
}
