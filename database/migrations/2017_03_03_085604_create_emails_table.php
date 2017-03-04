<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEmailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('emails', function (Blueprint $table) {
            $table->string('id');
            $table->integer('conference_id')->unsigned();
            $table->string('title');
            $table->string('sender');
            $table->string('receiver');
            $table->longText('content');
            $table->timestamps();
            $table->primary('id');
            $table->foreign('conference_id')->references('id')->on('conferences')->onDelete('no action');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('emails');
    }
}
