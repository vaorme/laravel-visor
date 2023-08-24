<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\URL;

class Category extends Model{
    use HasFactory;

    protected $table = "categories";

    public function url(){
        return URL::route('library.index', [
            'categories' => $this->slug
        ]);
    }
    public function categories(): BelongsTo{
        return $this->belongsTo(Manga::class);
    }
    public function mangas(){
        return $this->belongsToMany(Manga::class, 'manga_has_categories', 'category_id')->where('status', '=', 'published');
    }
}
