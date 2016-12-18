<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNationDelegateTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('nation_delegate', function (Blueprint $table) {
            $table->integer('user_id')->unsigned();
	        $table->integer('school_id')->unsigned();
            $table->integer('committee_id')->unsigned();
			$table->string('nation_id');
            $table->timestamps()->nullable();
            $table->primary('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('no action');
            $table->foreign('school_id')->references('id')->on('schools')->onDelete('no action');
            $table->foreign('nation_id')->references('id')->on('nations')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('nation_delegate');
    }
}