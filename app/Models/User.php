<?php

namespace App\Models;

use App\Models\Order;
use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    protected $fillable = [
        'email',
        'password',
        'first_name',
        'last_name',
        'phone',
        'company',
    ];

    public function orders()
    {
        return $this->hasMany(Order::class);
    }
}
