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

class CreateLinkingCodesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('linking_codes', function (Blueprint $table) {
            $table->string('id');
            $table->enum('type', ['roommate', 'partner']);
            $table->integer('reg_id')->unsigned();
            $table->timestamps();
            $table->unique(['reg_id', 'type']);
            $table->unique(['id', 'type']);
            $table->foreign('reg_id')->references('id')->on('regs')
                ->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('linking_codes');
    }
}
