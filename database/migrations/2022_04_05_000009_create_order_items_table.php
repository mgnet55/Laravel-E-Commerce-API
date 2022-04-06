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
            $table->float('discount')->default(0);
            $table->unsignedBigInteger('user_id')->nullable();
            $table->boolean('fulfilled')->default(false);
            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onupdate('cascade')
                ->onDelete('set null');

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
