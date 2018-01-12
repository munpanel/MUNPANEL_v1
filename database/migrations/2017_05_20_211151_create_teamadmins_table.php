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

class CreateTeamadminsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('teamadmins', function (Blueprint $table) {
            $table->integer('reg_id')->unsigned();
            $table->integer('school_id')->unsigned();
            $table->integer('conference_id')->nullable()->unsigned(); // if null -> global school admin
            $table->primary('reg_id');
            $table->foreign('reg_id')->references('id')->on('regs')->onDelete('cascade');
            $table->foreign('school_id')->references('id')->on('schools')->onDelete('cascade');
            $table->foreign('conference_id')->references('id')->on('conferences')->onDelete('cascade');
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
        Schema::dropIfExists('teamadmins');
    }
}
