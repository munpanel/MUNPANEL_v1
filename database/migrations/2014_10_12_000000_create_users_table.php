<?php
/**
* Copyright (C) MUNPANEL
* This file is part of MUNPANEL System.
*
* Unauthorized copying of this file, via any medium is strictly prohibited
* Proprietary and confidential
*
* Developed by Adam Yi <xuan@yiad.am>
*/

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
	        $table->string('email')->unique();
            $table->string('tel')->nullable();
            //$table->enum('type', ['unregistered', 'ot', 'dais', 'school', 'delegate', 'observer', 'volunteer']);
            $table->string('password');
            $table->string('emailVerificationToken');
            $table->integer('telVerifications')->default(15);
            $table->string('google2fa_secret')->nullable();
            $table->boolean('google2fa_enabled')->default(false);
            $table->rememberToken();
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
        Schema::dropIfExists('users');
    }
}
