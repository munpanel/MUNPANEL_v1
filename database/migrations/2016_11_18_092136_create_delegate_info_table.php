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

class CreateDelegateInfoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('delegate_info', function (Blueprint $table) {
            $table->integer('reg_id')->unsigned();
            $table->integer('conference_id')->unsigned();
            $table->integer('school_id')->unsigned()->nullable();
            $table->enum('status', ['reg','sVerified', 'oVerified', 'unpaid', 'paid', 'fail'])->default('reg');
            $table->integer('committee_id')->unsigned()->nullable();
            $table->integer('nation_id')->nullable()->unsigned();
            $table->integer('partner_reg_id')->nullable()->unsigned();
            $table->timestamps();
            $table->primary('reg_id');
            $table->foreign('reg_id')->references('id')->on('regs')->onDelete('cascade');
            $table->foreign('conference_id')->references('id')->on('conferences')->onDelete('cascade');
            $table->foreign('school_id')->references('id')->on('schools')->onDelete('set null');
            $table->foreign('committee_id')->references('id')->on('committees')->onDelete('set null');
            $table->foreign('nation_id')->references('id')->on('nations')->onDelete('set null');
            $table->foreign('partner_reg_id')->references('id')->on('regs')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('delegate_info');
    }
}
