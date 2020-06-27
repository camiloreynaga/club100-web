<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UserNotification extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('user_notification', function (Blueprint $table) {
            $table->increments('id');
            $table->varchar('title');
            $table->varchar('message');       
            $table->integer('category_id');
            $table->varchar('user_id');  
            $table->integer('status');
            $table->timestamps();         
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
