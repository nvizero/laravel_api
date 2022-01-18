<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrderDetailTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::drop('order_details');
        Schema::create('order_details', function (Blueprint $table) {
            $table->id();            
            $table->integer('order_id')->comment('訂單ID');
            $table->integer('product_id')->comment('產品ID');
            $table->string('product_name')->comment('產品名稱');
            $table->integer('category_id')->default(0)->comment('分類ID');
            $table->string('category_name')->nullable()->default(NULL)->comment('分類名稱');
            $table->integer('category_style_id_1')->default(0)->comment('分類尺寸SIZE1 ID');
            $table->string('category_style_1_name')->nullable()->default(NULL)->comment('分類尺寸SIZE1 名稱');
            $table->integer('category_style_id_2')->default(0)->comment('分類尺寸SIZE2 ID');
            $table->string('category_style_2_name')->nullable()->default(NULL)->comment('分類尺寸SIZE2 名稱');
            $table->integer('num')->comment('數量');
            $table->integer('one_price')->comment('單價');
            $table->integer('total_price')->comment('小計');
            $table->timestamps();
        });

        Schema::table('orders', function (Blueprint $table) {
            $table->integer('order_status')->comment('訂單狀態');
            $table->string('order_status_name')->comment('產品狀態名稱');

            $table->integer('ship_status')->comment('運送狀態');
            $table->string('ship_status_name')->comment('運送狀態名稱');

            $table->integer('pay_status')->comment('支付狀態');
            $table->string('pay_status_name')->comment('支付狀態名稱');

            $table->string('user_context')->comment('使用者備註');
            $table->string('admin_context')->comment('使用者備註');

            $table->dropColumn('status');           
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('order_detail');
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn('order_status');
            $table->dropColumn('order_status_name');

            $table->dropColumn('ship_status');
            $table->dropColumn('ship_status_name');

            $table->dropColumn('pay_status');
            $table->dropColumn('pay_status_name');
            $table->dropColumn('user_context');
            $table->dropColumn('admin_context');

            $table->integer('status')->comment('狀態');
            
        });
    }
}
