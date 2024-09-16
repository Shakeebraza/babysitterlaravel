<?php

use App\Models\Enums\NotificationMethod;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNotificationSettingTable extends Migration
{
    public function up()
    {
        Schema::create('notification_settings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->restrictOnUpdate()->cascadeOnDelete();
            $table->string('system_notification')->default(NotificationMethod::BOTH);
            $table->string('application_updates')->default(NotificationMethod::BOTH);
            $table->string('group_requests')->default(NotificationMethod::BOTH);
            $table->string('subscription')->default(NotificationMethod::BOTH);
            $table->string('recommendation')->default(NotificationMethod::BOTH);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('notification_setting');
    }
}
