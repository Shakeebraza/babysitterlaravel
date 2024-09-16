<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNotificationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('sender');
            $table->unsignedBigInteger('receiver');
            $table->unsignedBigInteger('request_id')->nullable();
            $table->text('title');
            $table->longText('notification');
            $table->integer('is_read')->default(0);
            $table->timestamps();

            $table->foreign('sender')->references('id')->on('users')->restrictOnUpdate()->restrictOnDelete();
            $table->foreign('receiver')->references('id')->on('users')->restrictOnUpdate()->restrictOnDelete();
            $table->foreign('request_id')->references('id')->on('user_requests')->restrictOnUpdate()->restrictOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('notifications');
    }
}
