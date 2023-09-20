<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserBuyCoins extends Model{
    use HasFactory;

    protected $table = "user_buy_coins";

    protected $fillable = [
        'user_id',
        'coins',
        'username', 
    ];
}
