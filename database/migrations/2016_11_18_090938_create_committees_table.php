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
			//议事规则：美规、欧规、暂规
			//如果是经修改的规则，将rule_modified设为true，
			//使委员会主页中议事规则一项增加“经修改的/Modified”前缀
			$table->enum('rule', ['robert', 'eu', 'sc']);
			$table->boolean('rule_modified')->default(false);
			//危机推动类型：无、仅新闻（半危机推动？不需要写DD）、有、有联动
			$table->enum('crisis', ['none', 'news_only', 'yes', 'joint']);
			//时间节点默认取会议起止日
			$table->date('timeframe_start');
			$table->date('timeframe_end');
			$table->integer('session')->unsigned();
			$table->text('description');
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
