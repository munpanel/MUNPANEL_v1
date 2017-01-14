<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned();
            $table->enum('status', ['unpaid', 'paid', 'done'])->default('unpaid');
            $table->enum('shipment_method', ['mail', 'conference', 'none']); //快递；会议领取；虚拟商品
            $table->string('address')->nullable();
            $table->string('shipment_no')->nullable();
            $table->string('content'); //JSON
            $table->dateTime('shipped_at');
            $table->timestamps();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('no action');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('orders');
    }
}
