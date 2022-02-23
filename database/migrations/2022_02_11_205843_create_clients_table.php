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
        Schema::create('clients', function (Blueprint $table) {
            $table->id();
            $table->string('first_name')->nullable();
            $table->string('last_name')->nullable();
            $table->string('phone1')->nullable();
            $table->string('birth_date')->nullable();
            $table->string('buletin_sn')->nullable();
            $table->string('buletin_idnp')->nullable();
            $table->string('buletin_date_till')->nullable();
            $table->string('buletin_office')->nullable();
            $table->string('region')->nullable();
            $table->string('localitate')->nullable();
            $table->string('street')->nullable();
            $table->string('house')->nullable();
            $table->string('flat')->nullable();
            $table->text('description')->nullable();
            $table->boolean('deleted')->nullable();
            $table->timestamps();
        });
        Schema::table('bids', static function (Blueprint $table) {
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
        Schema::dropIfExists('clients');
    }
};
