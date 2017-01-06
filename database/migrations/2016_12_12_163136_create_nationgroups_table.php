<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNationgroupsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('nationgroups', function (Blueprint $table) {
			$table->increments('id');
			$table->string('name'); //内部名
			$table->string('display_name'); //代表可见名
			// TODO: 根据需要添加列，例如“标记颜色”等
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
        Schema::dropIfExists('nationgroups');
    }
}