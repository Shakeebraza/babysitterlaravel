<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFeedItemTable extends Migration
{
    public function up()
    {
        Schema::create('feed_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->restrictOnUpdate()->cascadeOnDelete();
            $table->foreignId('request_id')->constrained('user_requests')->restrictOnUpdate()->restrictOnDelete();
            $table->string('type');
            $table->timestamp('notified')->nullable();
            $table->timestamp('removed')->nullable();
            $table->boolean('done');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('feed_items');
    }
}
