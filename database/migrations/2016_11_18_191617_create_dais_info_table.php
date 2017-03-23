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

class CreateDaisInfoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dais_info', function (Blueprint $table) {
            $table->integer('reg_id')->unsigned();
            $table->integer('conference_id')->unsigned();
            $table->integer('school_id')->unsigned()->nullable();
            $table->integer('committee_id')->unsigned();
            $table->enum('status', ['reg', 'oVerified', 'success', 'fail'])->default('reg');
            $table->string('position');
            $table->timestamps();
            $table->primary('reg_id');
            $table->foreign('reg_id')->references('id')->on('regs')->onDelete('cascade');
            $table->foreign('conference_id')->references('id')->on('conferences')->onDelete('cascade');
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
        Schema::dropIfExists('dais_info');
    }
}
