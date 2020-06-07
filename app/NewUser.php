<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class NewUser extends Model
{
    protected $fillable = ['name', 'dni', 'email', 'phone', 'user', 'password', 'turn', 'day'];

    protected $table = 'new_user';

}
