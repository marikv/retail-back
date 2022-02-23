<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('files', static function (Blueprint $table) {
            $table->id();
            $table->bigInteger('type_id')->nullable()->unsigned();
            $table->string('name')->nullable();
            $table->text('path')->nullable();
            $table->text('web_path')->nullable();
            $table->bigInteger('size')->nullable()->unsigned();
            $table->string('extension', 10)->nullable();
            $table->string('mimetype')->nullable();
            $table->bigInteger('dealer_id')->nullable()->unsigned();
            $table->bigInteger('client_id')->nullable()->unsigned();
            $table->bigInteger('payment_id')->nullable()->unsigned();
            $table->bigInteger('bid_id')->nullable()->unsigned();
            $table->bigInteger('user_id')->nullable()->unsigned();
            $table->bigInteger('added_by_user_id')->nullable()->unsigned();
            $table->boolean('deleted')->nullable();
            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('files');
    }
}
