<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCommitteesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('committees', function (Blueprint $table) {
            $table->increments('id');
			$table->string('name');
			$table->string('display_name');
			$table->string('topic_0');
			$table->string('topic_1')->nullable();
			$table->enum('topic_sel', ['Topic0', 'Topic1', 'Unchosen']);
			$table->enum('language', ['ChineseS', 'English']);
			$table->integer('session')->unsigned();
            $table->timestamps()->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('committees');
    }
}
