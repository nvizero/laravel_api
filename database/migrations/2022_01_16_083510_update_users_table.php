<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('product_attributes', function (Blueprint $table) {
            $table->text('content')->nullable()->change();
            $table->string('style1')->nullable()->change();
            $table->string('style2')->nullable()->default(NULL)->change();
            $table->string('price')->nullable()->default(NULL)->change();
            $table->integer('num')->nullable()->change();
            $table->text('image')->nullable()->change();
        });

        Schema::table('products', function (Blueprint $table) {            
            $table->string('start_time')->nullable()->default(NULL)->change();
            $table->string('end_time')->nullable()->default(NULL)->change();            
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
         
    }
}
