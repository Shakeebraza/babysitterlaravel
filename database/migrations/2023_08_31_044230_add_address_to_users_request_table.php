<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAddressToUsersRequestTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('user_requests', function (Blueprint $table) {
            $table->string('address_type')->nullable()->after('public_visibility');
            $table->string('address')->nullable()->after('address_type');
            $table->string('street')->nullable()->after('address');
            $table->string('zip')->nullable()->after('street');
            $table->string('city')->nullable()->after('zip');
            $table->string('latitude')->nullable()->after('city');
            $table->string('longitude')->nullable()->after('latitude');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('user_requests', function (Blueprint $table) {
            //
        });
    }
}
