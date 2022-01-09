<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductStocksNumTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_stocks_num', function (Blueprint $table) {
            $table->id();
            $table->integer('product_stock_id')->comment('產品型號');
            $table->integer('num')->comment('產品數量');
            // $table->charset = 'utf8mb4';
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
        Schema::dropIfExists('product_stocks_num');
    }
}
