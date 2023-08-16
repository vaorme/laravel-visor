<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\URL;

class MangaType extends Model{
    use HasFactory;

    protected $table = "manga_type";

    public function url(){
        return URL::route('library.index', [
            'type' => $this->slug
        ]);
    }
}
