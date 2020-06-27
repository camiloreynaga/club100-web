<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserNotification extends Model
{
    //
    protected $fillable = ['title', 'message', 'category_id' , 'user_id'];

    protected $table = 'usernotification';
    
}
