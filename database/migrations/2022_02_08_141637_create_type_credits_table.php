<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTypeCreditsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('type_credits', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->integer('months_fix')->nullable()->default(0);
            $table->integer('months_min')->nullable()->default(0);
            $table->integer('months_max')->nullable()->default(0);
            $table->float('sum_min', 10, 4)->nullable()->default(0);
            $table->float('sum_max', 10, 4)->nullable()->default(0);
            $table->float('dobinda', 19,16)->nullable()->default(0);
            $table->boolean('dobinda_is_percent')->nullable();
            $table->float('comision', 19,16)->nullable()->default(0);
            $table->boolean('comision_is_percent')->nullable();
            $table->float('comision_admin', 19,16)->nullable()->default(0);
            $table->boolean('comision_admin_is_percent')->nullable();
            $table->float('percent_comision_magazin', 8, 4)->nullable()->default(0);
            $table->float('percent_bonus_magazin', 8, 4)->nullable()->default(0);
            $table->boolean('is_shop_fee')->nullable();
            $table->text('description')->nullable();
            $table->text('description_mini')->nullable();
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
        Schema::dropIfExists('type_credits');
    }
}
