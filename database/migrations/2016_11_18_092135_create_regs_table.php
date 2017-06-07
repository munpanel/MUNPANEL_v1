<?php
/**
* Copyright (C) Console iT
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

class CreateRegsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('regs', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned();
            $table->integer('conference_id')->unsigned()->nullable(); // null -> global things
            $table->integer('school_id')->unsigned()->nullable();
            $table->string('order_id')->nullable();
            $table->enum('type', ['unregistered', 'ot', 'dais', 'teamadmin', 'delegate', 'observer', 'volunteer', 'interviewer']);
            $table->enum('gender', ['male', 'female'])->nullable();
            $table->string('password')->nullable();
            $table->boolean('enabled');
            $table->boolean('accomodate')->nullable();
            $table->integer('roommate_user_id')->nullable()->unsigned();
            $table->text('reginfo')->nullable();
            $table->timestamps();
            $table->unique(['user_id', 'conference_id', 'type']);
            $table->foreign('user_id')->references('id')->on('users')->onDelete('no action');
            $table->foreign('conference_id')->references('id')->on('conferences')->onDelete('cascade');
            $table->foreign('school_id')->references('id')->on('schools')->onDelete('set null');
            $table->foreign('roommate_user_id')->references('id')->on('users')->onDelete('set null');
            $table->foreign('order_id')->references('id')->on('orders')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('regs');
    }
}
