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
        Schema::table('bids', static function (Blueprint $table) {
            $table->string('who_is_cont_pers2')->nullable()->after('flat');
            $table->string('phone_cont_pers2', 25)->nullable()->after('flat');
            $table->string('last_name_cont_pers2', 20)->nullable()->after('flat');
            $table->string('first_name_cont_pers2', 20)->nullable()->after('flat');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('bids', function (Blueprint $table) {
            //
        });
    }
};
