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
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->integer('type');
            $table->integer('pko_number')->nullable();
            $table->dateTime('date_time')->nullable();
            $table->dateTime('date_time_fact')->nullable();
            $table->boolean('beznal')->nullable();
            $table->unsignedBigInteger('payment_kind_id')->nullable();
            $table->unsignedBigInteger('payment_cash_id')->nullable();
            $table->unsignedBigInteger('bid_id')->nullable();
            $table->unsignedBigInteger('dealer_id')->nullable();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->unsignedBigInteger('client_id')->nullable();
            $table->double('payment_sum', 10, 2)->nullable();
            $table->double('payment_sum_fact', 10, 2)->nullable();
            $table->text('description')->nullable();
            $table->boolean('deleted')->nullable();
            $table->timestamps();
        });
        Schema::table('payments', static function (Blueprint $table) {
            $table->foreign('bid_id')->references('id')->on('bids');
            $table->foreign('dealer_id')->references('id')->on('dealers');
            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('client_id')->references('id')->on('clients');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('payments');
    }
};
