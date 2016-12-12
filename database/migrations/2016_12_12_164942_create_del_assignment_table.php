<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAssignmentDelegateTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('assignment_delegate', function (Blueprint $table) {
            $table->integer('user_id')->unsigned();
	        $table->integer('assignment_id')->unsigned();
			$table->mediumtext('assignment_content');
            $table->timestamps()->nullable();
            $table->primary('user_id');
            $table->foreign('user_id')->references('user_id')->on('nation_delegate')->onDelete('cascade');
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
        Schema::dropIfExists('assignment_delegate');
    }
}