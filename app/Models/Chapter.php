<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\URL;

class Chapter extends Model{
    use \Staudenmeir\EloquentEagerLimit\HasEagerLimit;
    use HasFactory;
    
    public function manga(): BelongsTo{
        return $this->belongsTo(Manga::class)->where('status', '=', 'published');
    }
    public function url(){
        return URL::route('chapter_viewer.index', [
            'manga_slug' => $this->manga->slug,
            'chapter_slug' => $this->slug
        ]);
    }
    public function next($mangaid){
        // GET NEXT
        $id = Chapter::where('manga_id', '=', $mangaid)->where('id', '>', $this->id)->min('id');
        return Chapter::where('id', '=', $id)->get('slug')->first();
    }
    public  function prev($mangaid){
        // GET PREVIOUS
        $id = Chapter::where('manga_id', '=', $mangaid)->where('id', '<', $this->id)->max('id');
        return Chapter::where('id', '=', $id)->get('slug')->first();
    }
}
