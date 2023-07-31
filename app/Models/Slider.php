<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Slider extends Model{
    use HasFactory;

    protected $table = "slider";

    public function manga(): BelongsTo{
        return $this->belongsTo(Manga::class);
    }
}
