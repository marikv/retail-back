<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDealersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dealers', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('full_name')->nullable();
            $table->text('address_fiz')->nullable();
            $table->text('address_jju')->nullable();
            $table->string('phone1')->nullable();
            $table->string('phone2')->nullable();
            $table->string('fax')->nullable();
            $table->string('email')->nullable();
            $table->string('website')->nullable();
            $table->string('idno')->nullable();
            $table->string('logo')->nullable();
            $table->string('administrator')->nullable();
            $table->string('director_general')->nullable();
            $table->string('director_executiv')->nullable();
            $table->text('description')->nullable();
            $table->text('tip_capital')->nullable();
            $table->string('bank_name')->nullable();
            $table->string('bank_cb')->nullable();
            $table->string('bank_iban')->nullable();
            $table->string('bank_valuta')->nullable();
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
        Schema::dropIfExists('dealers');
    }
}
