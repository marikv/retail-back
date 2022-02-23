<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColsToUsers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('avatar')->nullable()->after('password');
            $table->bigInteger('role_id')->nullable()->after('email')->unsigned()->index();
            $table->bigInteger('dealer_id')->nullable()->after('email')->unsigned()->index();
        });
        Schema::table('users', function (Blueprint $table) {
            $table->foreign('dealer_id')->references('id')->on('dealers');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            //
        });
    }
}
