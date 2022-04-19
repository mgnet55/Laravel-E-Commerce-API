<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->text('name');
            $table->text('description');
            $table->integer('quantity');
            $table->float('price');
            $table->text('image');
            $table->boolean('picked')->default(false);
            $table->float('discount')->default(0);
            $table->unsignedBigInteger('order_id');
            $table->unsignedBigInteger('product_id')->nullable();
            $table->boolean('fulfilled')->default(false);
            $table->foreign('product_id')
                ->references('id')
                ->on('products')
                ->onupdate('cascade')
                ->onDelete('set null');
            $table->foreign('order_id')
                ->references('id')
                ->on('orders')
                ->onupdate('cascade')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('order_items');
    }
};
