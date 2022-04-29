<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->softDeletes();
            $table->text('name');
            $table->text('description');
            $table->integer('quantity');
            $table->float('price');
            $table->float('discount')->default(0);
            $table->text('image')->nullable();
            $table->boolean('available')->default(false);
            $table->unsignedBigInteger('seller_id');
            $table->unsignedBigInteger('category_id')->default(1);

            $table->foreign('seller_id')
                  ->references('id')
                  ->on('users')
                  ->onupdate('cascade')
                  ->onDelete('cascade');

            // $table->foreign('category_id')
            //       ->references('id')
            //       ->on('categories')
            //       ->onupdate('cascade')
            //       ->onDelete('set default');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('products');
    }
};
