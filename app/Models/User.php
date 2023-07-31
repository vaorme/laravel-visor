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
    
    public function profile(){
        return $this->hasOne(Profile::class);
    }
    public function followedMangas(){
        return $this->belongsToMany(Manga::class, UserFollowManga::class);
    }
    public function favoriteMangas(){
        return $this->belongsToMany(Manga::class, UserHasFavorite::class);
    }
    public function shortcutMangas(){
        return $this->belongsToMany(Manga::class, UserShortcut::class);
    }
    public function verifiedEmail(){
        return ($this instanceof MustVerifyEmail);
    }
}
