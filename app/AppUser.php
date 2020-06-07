<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AppUser extends Model
{
    protected $fillable = ['category_id','email', 'name', 'password', 'token', 'status'];

    protected $table = 'app_user';

    public function category(){
        return $this->belongsTo('App\Category');
    }
}
