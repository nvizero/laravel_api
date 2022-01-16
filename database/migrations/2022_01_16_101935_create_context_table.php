<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateContextTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('context', function (Blueprint $table) {
            $table->id();
            $table->string('title')->nullable()->default(NULL)->comment('標題');
            $table->string('short')->nullable()->default(NULL)->comment('簡寫');
            $table->text('content')->nullable()->comment('內容');
            $table->integer('status')->default(0)->comment('狀態');
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
        Schema::dropIfExists('context');
    }
}
