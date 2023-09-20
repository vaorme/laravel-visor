<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model{
    use HasFactory;

    public function products(){
        return $this->hasMany(OrderItems::class, 'order_id', 'id');
    }
    public function user(){
        return $this->hasOne(User::class, 'id', 'user_id');
    }
}
