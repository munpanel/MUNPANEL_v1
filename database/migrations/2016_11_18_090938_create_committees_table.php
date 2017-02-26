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
            $table->integer('conference_id')->unsigned();
            $table->string('name');
            $table->string('display_name');
            $table->string('topic_0');
            $table->string('topic_1')->nullable();
            $table->enum('topic_sel', ['Topic0', 'Topic1', 'Unchosen']);
            $table->enum('language', ['ChineseS', 'English']);
            $table->string('rule');
            $table->integer('father_committee_id')->unsigned()->nullable();
            $table->integer('option_limit')->unsigned->default(99);
			//时间节点默认取会议起止日
			$table->date('timeframe_start');
			$table->date('timeframe_end');
            $table->boolean('is_allocated')->default(false);
			$table->integer('session')->unsigned();
			$table->text('description');
            $table->timestamps()->nullable();
            $table->foreign('conference_id')->references('id')->on('conferences')->onDelete('cascade');
            $table->foreign('father_committee_id')->references('id')->on('committees')->onDelete('set null');
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
