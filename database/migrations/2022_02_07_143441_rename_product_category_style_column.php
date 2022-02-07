<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RenameProductCategoryStyleColumn extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('product_category_style', function(Blueprint $table) {
            $table->renameColumn('category_styles2_id', 'type')->comment('STYLE 是1還是2');
            $table->renameColumn('category_styles1_id', 'category_styles_id')->comment('分類 STYLE ID');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('product_category_style', function(Blueprint $table) {
            $table->renameColumn('category_styles_id', 'category_styles1_id')->comment('分類STYLE 1 ID');
            $table->renameColumn('type', 'category_styles2_id')->comment('分類STYLE 2 ID');
        });
    }
}
