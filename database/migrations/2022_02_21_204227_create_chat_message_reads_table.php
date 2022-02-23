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
        Schema::create('chat_message_reads', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('chat_message_id');
            $table->unsignedBigInteger('user_id')->nullable();
            $table->boolean('read')->nullable();
            $table->timestamps();
        });
        Schema::table('chat_message_reads', static function (Blueprint $table) {
            $table->foreign('chat_message_id')->references('id')->on('chat_messages');
            $table->foreign('user_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('chat_message_reads');
    }
};
