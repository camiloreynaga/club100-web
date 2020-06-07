<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AppNotification extends Model
{
    protected $fillable = ['title', 'message', 'category_id'];

    protected $table = 'notifications';
    
    public function category(){
        return $this->belongsTo('App\Category');
    }
}
