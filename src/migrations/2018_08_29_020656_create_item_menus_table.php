<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateItemMenusTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('item_menus', function (Blueprint $table) {
            $table->increments('id');
            $table->string('menu_id');
            $table->string('label');
            $table->string('link');
            $table->string('icon')->nullable();
            $table->integer('order_by')->default(1);
            $table->string('type');
            $table->integer('parent_id')->nullable();
            $table->timestamps();
            $table->foreign('menu_id')->references('id')->on('menus')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('item_menus');
    }
}
