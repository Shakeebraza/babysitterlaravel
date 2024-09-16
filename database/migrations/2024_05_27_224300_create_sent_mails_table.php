<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSentMailsTable extends Migration
{
    public function up()
    {
        Schema::create('sent_mails', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('email_type');
            $table->string('message_name');
            $table->string('language');
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->restrictOnUpdate()->restrictOnDelete();
        });
    }

    public function down()
    {
        Schema::dropIfExists('sent_emails');
    }
}
