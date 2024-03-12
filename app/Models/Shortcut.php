<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\URL;

class Shortcut extends Model{

    use HasFactory;

    protected $table = "user_shortcuts";

    public function url(){
        return URL::route('comic_detail.index', [
            'slug' => $this->slug
        ]);
    }
}
