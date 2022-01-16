<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductCategoryStyleTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_category_style', function (Blueprint $table) {
            $table->id();
            $table->integer('category_id')->comment('分類');
            $table->integer('category_style_id')->comment('分類STYLE ID');
            $table->integer('product_id')->comment('產品ID');
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
        Schema::dropIfExists('product_category_style');
    }
}
