<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRegisteredUserTable extends Migration
{
    public function up()
    {
        Schema::create('registered_user', function (Blueprint $table) {
            $table->integer('u_id')->unsigned()->primary(); // Primary key
            $table->string('u_name');                       // Full name
            $table->string('u_email')->unique();            // Email
            $table->string('user_name')->unique();          // Username
            $table->string('pass_word');                    // Password
            $table->integer('u_phoneNum')->nullable();      // Phone number
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('registered_user');
    }
}