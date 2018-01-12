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
            $table->integer('conference_id')->unsigned();
			$table->string('name'); //内部名
			$table->string('display_name'); //代表可见名
			// TODO: 根据需要添加列，例如“标记颜色”等
            $table->timestamps();
            $table->foreign('conference_id')->references('id')->on('conferences')->onDelete('cascade');
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
