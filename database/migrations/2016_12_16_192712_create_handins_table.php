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
            $table->integer('reg_id')->unsigned(); //无论何种提交类型，均强制记录提交者
            $table->integer('nation_id')->nullable()->unsigned(); //对非国家单位的学测可留空
            $table->integer('assignment_id')->unsigned();
            $table->enum('handin_type', ['upload', 'text']); //If upload, assignment_content = file location
            $table->mediumtext('content');
            $table->string('remark');
            $table->timestamps();
            $table->foreign('reg_id')->references('id')->on('regs')->onDelete('no action');
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
