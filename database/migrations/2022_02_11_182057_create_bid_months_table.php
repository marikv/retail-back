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
        Schema::create('bid_months', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('bid_id');
            $table->date('date');
            $table->float('imprumut_per_luna')->nullable()->default(0);
            $table->float('dobinda_per_luna')->nullable()->default(0);
            $table->float('comision_per_luna')->nullable()->default(0);
            $table->float('comision_admin_per_luna')->nullable()->default(0);
            $table->float('total_per_luna')->nullable()->default(0);
            $table->timestamps();
        });
        Schema::table('bid_months', static function (Blueprint $table) {
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
        Schema::dropIfExists('bid_months');
    }
};
