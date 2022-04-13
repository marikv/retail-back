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
        Schema::create('dealer_type_credits', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('dealer_id');
            $table->unsignedBigInteger('type_credit_id');
            $table->boolean('deleted')->nullable();
            $table->timestamps();
        });
        Schema::table('dealer_type_credits', static function (Blueprint $table) {
            $table->foreign('dealer_id')->references('id')->on('dealers');
            $table->foreign('type_credit_id')->references('id')->on('type_credits');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('dealer_type_credits');
    }
};
