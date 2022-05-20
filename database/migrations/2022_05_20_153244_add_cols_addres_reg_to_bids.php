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
        Schema::table('bids', function (Blueprint $table) {
            $table->string('flat_reg', 10)->nullable()->after('flat');
            $table->string('house_reg', 10)->nullable()->after('flat');
            $table->string('street_reg', 30)->nullable()->after('flat');
            $table->string('localitate_reg', 25)->nullable()->after('flat');
            $table->string('region_reg', 25)->nullable()->after('flat');
        });
        Schema::table('clients', function (Blueprint $table) {
            $table->string('flat_reg', 10)->nullable()->after('flat');
            $table->string('house_reg', 10)->nullable()->after('flat');
            $table->string('street_reg', 30)->nullable()->after('flat');
            $table->string('localitate_reg', 25)->nullable()->after('flat');
            $table->string('region_reg', 25)->nullable()->after('flat');
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
