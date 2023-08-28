<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements MustVerifyEmail{
    use HasApiTokens, HasFactory, Notifiable, HasRoles;
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'username',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
    public function link(){
        $link = route('profile.index', ['username' => $this->username]);
        return $link;
    }
    public function profile(){
        return $this->hasOne(Profile::class);
    }
    public function followedMangas(){
        return $this->belongsToMany(Manga::class, UserFollowManga::class)->where('manga.status', '=', 'published');
    }
    public function favoriteMangas(){
        return $this->belongsToMany(Manga::class, UserHasFavorite::class)->where('manga.status', '=', 'published');
    }
    public function shortcutMangas(){
        return $this->belongsToMany(Manga::class, UserShortcut::class)->where('manga.status', '=', 'published')->limit(20);
    }
    public function latestChapters(){
        return $this->followedMangas->map(function ($manga) {
            return $manga->lastChapter;
        })->filter(function ($chapter) {
            return $chapter !== null;
        })->sortByDesc(function ($chapter) {
            return optional($chapter)->created_at; // Get the creation date of the latest chapter
        })->values();
    }
    public function verifiedEmail(){
        return ($this instanceof MustVerifyEmail);
    }
}
