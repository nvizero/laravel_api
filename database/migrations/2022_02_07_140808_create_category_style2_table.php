<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCategoryStyle2Table extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('category_styles2', function (Blueprint $table) {
            $table->id();
            $table->integer('category_id')->comment('分類ID');            
            $table->string('name')->comment('類型名稱');
            $table->timestamps();
        });

        Schema::rename('category_styles', 'category_styles1');

        Schema::table('product_category_style', function(Blueprint $table) {
            $table->renameColumn('category_style_id', 'category_styles1_id')->comment('分類STYLE 1 ID');
            $table->integer('category_styles2_id')->default(0)->comment('分類STYLE 2 ID');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('category_styles2');
        Schema::rename('category_styles1', 'category_styles');
        Schema::table('product_category_style', function(Blueprint $table) {
            $table->renameColumn('category_styles1_id', 'category_style_id')->comment('分類STYLE ID');
            $table->dropColumn('category_styles2_id');
        });
    }
}
