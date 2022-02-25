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
        Schema::table('contracts', function (Blueprint $table) {

            $table->float('total_comision_admin', 16, 8)->nullable()->after('total');
            $table->float('total_comision', 16, 8)->nullable()->after('total');
            $table->float('total_dobinda', 16, 8)->nullable()->after('total');
            $table->integer('sum_max_permis')->nullable()->unsigned()->after('imprumut');
        });
        Schema::table('contracts', static function (Blueprint $table) {
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
        Schema::table('contracts', function (Blueprint $table) {
            //
        });
    }
};
