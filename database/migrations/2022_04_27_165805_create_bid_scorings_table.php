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
        Schema::create('bid_scorings', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('bid_id');
            $table->text('json_date')->nullable();
            $table->timestamps();
        });
        Schema::table('bid_scorings', static function (Blueprint $table) {
            $table->foreign('bid_id')->references('id')->on('bids');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('bid_scorings');
    }
};
