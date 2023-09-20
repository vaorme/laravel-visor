<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserBuyDays extends Model{
    use HasFactory;

    protected $table = "user_buy_days";

    protected $fillable = [
        'user_id',
        'days_without_ads',
        'last_updated',
        'username', 
    ];
}
