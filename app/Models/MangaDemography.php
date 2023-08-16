<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\URL;

class MangaDemography extends Model{
    use HasFactory;

    protected $table = "manga_demography";

    public function url(){
        return URL::route('library.index', [
            'demography' => $this->slug
        ]);
    }
}
