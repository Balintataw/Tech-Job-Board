<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Profile extends Model
{

    public function getRouteKeyName() {
        return 'slug';
    }

    protected $fillable = ['user_id', 'gender', 'dob', 'address', 'phone_number'];

}
