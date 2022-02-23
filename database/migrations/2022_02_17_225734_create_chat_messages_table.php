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
        Schema::create('chat_messages', static function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('bid_id')->nullable();
            $table->unsignedBigInteger('from_user_id')->nullable();
            $table->unsignedBigInteger('to_user_id')->nullable();
            $table->unsignedBigInteger('file_id')->nullable();
            $table->text('message')->nullable();
            $table->unsignedBigInteger('deleted_by_user_id')->nullable();
            $table->boolean('deleted')->nullable();
            $table->timestamps();
        });
        Schema::table('chat_messages', static function (Blueprint $table) {
            $table->foreign('bid_id')->references('id')->on('bids');
            $table->foreign('from_user_id')->references('id')->on('users');
            $table->foreign('to_user_id')->references('id')->on('users');
            $table->foreign('file_id')->references('id')->on('files');
            $table->foreign('deleted_by_user_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('chat_messages');
    }
};
