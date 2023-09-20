<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserBuyChapter extends Model{
    use HasFactory;

    protected $fillable = [
        'price',
        'chapter_id',
        'user_id'
    ];
}
