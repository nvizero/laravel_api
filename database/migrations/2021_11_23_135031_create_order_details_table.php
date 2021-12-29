<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrderDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_details', function (Blueprint $table) {
            $table->id();
            $table->integer('order_id')->comment('訂單ID');
            $table->integer('user_id')->comment('使用者ID');
            $table->string('user_name')->comment('使用者名稱');
            $table->integer('price')->comment('價格');
            $table->integer('num')->comment('數量');
            $table->text('text')->comment('簡述');
            $table->integer('category_id')->comment('分類ID');
            $table->integer('category_style_id')->comment('分類類型');

            $table->timestamps();
        });
        Schema::table('orders', function (Blueprint $table) {
            $table->string('user_name')->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('order_details');
        Schema::table('orders', function (Blueprint $table) {
            $table->integer('user_name')->change();
        });
    }
}
