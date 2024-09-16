<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAcceptedRequestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('accepted_requests', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('request_id');
            $table->unsignedBigInteger('user_id');
            $table->tinyInteger('request_status')->nullable();
            $table->integer('status')->default(0);
            $table->unsignedBigInteger('awarded_by')->nullable();
            $table->text('description')->nullable();
            $table->tinyInteger('payment_type')->default(0);
            $table->double('amount')->nullable();
            $table->timestamps();

            $table->foreign('request_id')->references('id')->on('kids')->restrictOnUpdate()->restrictOnDelete();
            $table->foreign('user_id')->references('id')->on('users')->restrictOnUpdate()->restrictOnDelete();
            $table->foreign('awarded_by')->references('id')->on('users')->restrictOnUpdate()->restrictOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('accepted_requests');
    }
}
