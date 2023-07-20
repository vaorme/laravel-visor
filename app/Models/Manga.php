<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\URL;

class Manga extends Model{
    use HasFactory;

    protected $table = "manga";

    public function url(){
        return URL::route('manga_detail.index', [
            'slug' => $this->slug
        ]);
    }
    public function chapters(){
        return $this->hasMany(Chapter::class)->latest();
    }
    public function lastChapter(){
        $week = date('Y-m-d', strtotime("-6 day"));
        return $this->hasOne(Chapter::class)->where('chapters.created_at', '>=', $week);
    }
    public function categories(){
        return $this->belongsToMany(Category::class, 'manga_has_categories', 'manga_id');
    }
    public function userFollowManga(){
        return $this->hasOne(UserFollowManga::class)->where('user_follow_manga.user_id', '=', Auth::id());
    }
    public function demography(){
        return $this->belongsTo(MangaDemography::class);
    }
    public function type(){
        return $this->belongsTo(MangaType::class);
    }
}
