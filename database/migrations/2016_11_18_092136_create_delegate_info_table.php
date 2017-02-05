<?php

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
            $table->integer('user_id')->unsigned();
	        $table->integer('school_id')->unsigned();
            $table->enum('status', ['reg','sVerified', 'oVerified', 'paid'])->default('reg');
            $table->enum('gender', ['male','female']);
            $table->string('sfz');
            $table->integer('grade');
            $table->string('email');
            $table->string('qq');
            $table->string('wechat');
            $table->string('parenttel');
            $table->string('tel');
            $table->integer('committee_id')->unsigned();
            $table->integer('nation_id')->nullable();
            $table->boolean('accomodate');
            $table->string('partnername')->nullable();
            $table->string('roommatename')->nullable();
            $table->integer('partner_user_id')->nullable()->unsigned();
            $table->integer('roommate_user_id')->nullable()->unsigned();
            $table->string('notes')->nullable();
            $table->timestamps();
            $table->primary('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('school_id')->references('id')->on('schools')->onDelete('cascade');
            $table->foreign('committee_id')->references('id')->on('committees')->onDelete('cascade');
            $table->foreign('nation_id')->references('id')->on('nations')->onDelete('set null');
            $table->foreign('partner_user_id')->references('id')->on('users')->onDelete('set null');
            $table->foreign('roommate_user_id')->references('id')->on('users')->onDelete('set null');
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
