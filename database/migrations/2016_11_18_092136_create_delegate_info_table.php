<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDelegateInfoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('delegate_info', function (Blueprint $table) {
            $table->integer('user_id')->unsigned();
	    $table->integer('school_id')->unsigned();
            $table->enum('status', ['reg','sVerified', 'oVerified'])->default('reg');
            $table->enum('gender', ['male','female']);
            $table->string('sfz');
	    $table->integer('grade');
	    $table->string('email');
	    $table->string('qq');
	    $table->string('wechat');
	    $table->string('partnername');
	    $table->string('parenttel');
	    $table->string('tel');
	    $table->integer('committee_id')->unsigned();
	    $table->boolean('accomodate');
	    $table->string('roommatename');
            $table->timestamps();
            $table->primary('user_id');
	    $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
	    $table->foreign('school_id')->references('id')->on('schools')->onDelete('cascade');
	    $table->foreign('committee_id')->references('id')->on('committees')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('delegate_info');
    }
}
