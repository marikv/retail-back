<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('bids', function (Blueprint $table) {
            $table->dateTime('payed_date_time')->nullable()->after('signed_date_time');
            $table->unsignedBigInteger('payed_user_id')->nullable()->after('signed_date_time');
        });
        Schema::table('bids', static function (Blueprint $table) {
            $table->foreign('payed_user_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('bids', function (Blueprint $table) {
            //
        });
    }
};
