<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductAttributesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_attributes', function (Blueprint $table) {
            $table->id();
            $table->integer("product_id")->comment('產品ID');
            $table->integer("category_id")->nullable()->comment('分類ID');
            $table->string("style1")->comment('size/大小/尺寸');
            $table->string("style2")->comment('顏色/型號');
            $table->integer("num")->comment('庫存/數量');
            $table->integer("price")->comment('價格');
            $table->string("image")->comment('圖片');
            $table->string("content")->comment('備註');
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
        Schema::dropIfExists('product_attributes');
    }
}
