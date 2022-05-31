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
    public function up(): void
    {
        Schema::create('dealer_type_credits', static function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('type_credit_id')->nullable();
            $table->unsignedBigInteger('dealer_id')->nullable();
            $table->float('percent_comision_magazin', 8, 4)->unsigned()->nullable();
            $table->float('percent_bonus_magazin', 8, 4)->unsigned()->nullable();
            $table->boolean('deleted')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('dealer_type_credits');
    }
};
