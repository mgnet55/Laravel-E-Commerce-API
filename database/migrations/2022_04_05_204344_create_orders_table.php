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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('city_id');
            $table->string('street');
            $table->enum('status',['Processing','On the Way','Done'])
                  ->default('Processing');
            $table->float('total_price');
            $table->unsignedBigInteger('shipping_id');
            $table->unsignedBigInteger('user_id');
            $table->string('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('shipping_id')
                  ->references('id')
                  ->on('shipping_companies')
                  ->onDelete('cascade')
                  ->onUpdate('cascade');
            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade')
                ->onUpdate('cascade');
            $table->foreign('city_id')
                ->references('id')
                ->on('cities')
                ->onDelete('cascade')
                ->onUpdate('cascade');
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('orders');
    }
};
