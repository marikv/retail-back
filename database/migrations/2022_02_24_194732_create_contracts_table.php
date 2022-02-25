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
        Schema::create('contracts', function (Blueprint $table) {
            $table->id();
            $table->integer('status_id')->nullable();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->unsignedBigInteger('dealer_id')->nullable();
            $table->unsignedBigInteger('bid_id')->nullable();
            $table->unsignedBigInteger('client_id')->nullable();
            $table->unsignedBigInteger('type_credit_id')->nullable();
            $table->string('type_credit_name')->nullable();

            $table->date('first_pay_date')->nullable();
            $table->integer('months')->nullable()->unsigned();
            $table->float('imprumut', 10, 2)->nullable()->default(0);
            $table->float('total', 10, 2)->nullable()->default(0);
            $table->float('apr', 10, 2)->nullable()->default(0);
            $table->float('apy', 10, 2)->nullable()->default(0);
            $table->float('coef', 8, 2)->nullable()->default(0);

            $table->integer('months_fix')->unsigned()->nullable()->default(0);
            $table->integer('months_min')->unsigned()->nullable()->default(0);
            $table->integer('months_max')->unsigned()->nullable()->default(0);
            $table->float('sum_min', 10, 4)->unsigned()->nullable()->default(0);
            $table->float('sum_max', 10, 4)->unsigned()->nullable()->default(0);
            $table->float('dobinda', 19,16)->unsigned()->nullable()->default(0);
            $table->boolean('dobinda_is_percent')->nullable();
            $table->float('comision', 19,16)->unsigned()->nullable()->default(0);
            $table->boolean('comision_is_percent')->nullable();
            $table->float('comision_admin', 19,16)->unsigned()->nullable()->default(0);
            $table->boolean('comision_admin_is_percent')->nullable();
            $table->float('percent_comision_magazin', 8, 4)->unsigned()->nullable()->default(0);
            $table->float('percent_bonus_magazin', 8, 4)->unsigned()->nullable()->default(0);

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

            $table->boolean('deleted')->nullable();
            $table->timestamps();
        });
        Schema::table('contracts', static function (Blueprint $table) {
            $table->foreign('dealer_id')->references('id')->on('dealers');
            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('type_credit_id')->references('id')->on('type_credits');
        });
        Schema::table('bids', function (Blueprint $table) {
            $table->unsignedBigInteger('contract_id')->nullable()->after('id');
        });
        Schema::table('bids', static function (Blueprint $table) {
            $table->foreign('contract_id')->references('id')->on('contracts');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('contracts');
    }
};
