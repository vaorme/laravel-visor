<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Models\Role;

class Profile extends Model{
    use HasFactory;

    // protected $table = "vo_profile";
    protected $primaryKey = "user_id";

    // public $incrementing = false;
    // protected $keyType = 'string';

    // public $timestamps = false;
    
    // const CREATED_AT = 'fecha_alta';
    // const UPDATED_AT = 'fecha_modifica';
    public function getCountry(){
        $country = Countries::where('id', '=', $this->country_id)->first();
        return $country;
    }
    public function getRole(){
        $role = UserHasRole::where('model_id', '=', $this->user_id)
        ->join('roles', 'roles.id', '=', 'model_has_roles.role_id')->get('name')->first();
        return $role->name;
    }
}
