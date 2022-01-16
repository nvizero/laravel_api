<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDateToProductTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dateTime('start_time')->comment('開始時間');
            $table->dateTime('end_time')->comment('結束時間');
            $table->integer('status')->comment('狀態');            
            $table->text('other_price')->nullable()->change();
            $table->dropColumn('attrib');           
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dateTime('start_time');
            $table->dateTime('end_time');
            $table->integer('status');
            $table->text('attrib')->comment('属性');
        });
    }
}
