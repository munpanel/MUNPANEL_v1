<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHandinsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('handins', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->nullable()->unsigned(); //Choose one from two according to different type of subjects
            $table->integer('nation_id')->nullable()->unsigned(); //Choose one from two according to different type of subjects
	        $table->integer('assignment_id')->unsigned();
			$table->enum('handin_type', ['upload', 'text']); //If upload, assignment_content = file location
			$table->mediumtext('assignment_content');
            $table->timestamps();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('no action');
            $table->foreign('nation_id')->references('id')->on('nations')->onDelete('no action');
            $table->foreign('assignment_id')->references('id')->on('assignments')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('handins');
    }
}