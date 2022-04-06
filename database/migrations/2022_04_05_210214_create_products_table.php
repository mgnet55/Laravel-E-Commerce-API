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
            $table->text('name');
            $table->text('description');
            $table->integer('quantity');
            $table->float('price');
            $table->text('image');
            $table->boolean('available')->default(1);
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')
                  ->references('id')
                  ->on('users')
                  ->onupdate('cascade')
                  ->onDelete('cascade');
            $table->unsignedBigInteger('cat_id')->default(1);
            $table->foreign('cat_id')
                  ->references('id')
                  ->on('categories')
                  ->onupdate('cascade')
                  ->onDelete('set default');
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
