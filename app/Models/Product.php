<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model{
    use HasFactory;

    public function type(){
        return $this->hasOne(ProductType::class, 'id', 'product_type_id');
    }
}
