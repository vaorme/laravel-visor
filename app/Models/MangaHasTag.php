<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MangaHasTag extends Model{
    use HasFactory;
    
    protected $table = "manga_has_tags";
}
