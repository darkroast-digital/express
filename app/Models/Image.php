<?php

namespace App\Models;

use App\Models\Product;
use Illuminate\Database\Eloquent\Model;

class Image extends Model
{
    protected $fillable = [
        //
    ];

    public function Product()
    {
        return $this->belongsTo(Product::class);
    }
}
