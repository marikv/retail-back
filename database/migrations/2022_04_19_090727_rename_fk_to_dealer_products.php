<?php

use App\Models\DealerProduct;
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
        DealerProduct::where('product_id',  '>', 0)->delete();

        Schema::table('dealer_products', function (Blueprint $table) {
            // $table->dropForeign('dealer_type_credits_type_credit_id_foreign');
            // $table->dropForeign('type_credit_id');
        });

        Schema::table('dealer_products', static function (Blueprint $table) {
            $table->foreign('product_id')->references('id')->on('products');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('dealer_products', function (Blueprint $table) {
            //
        });
    }
};
