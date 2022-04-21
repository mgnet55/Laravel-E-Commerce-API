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
            $table->timestamps();
            $table->softDeletes();
            $table->string('payment_ref');
            $table->unsignedBigInteger('city_id')->nullable();
            $table->string('street');
            $table->enum('status',['Processing','On way','Done'])->default('Processing');
            $table->unsignedBigInteger('shipping_company_id')->nullable();
            $table->unsignedBigInteger('customer_id')->nullable();
            $table->string('notes')->nullable();

            $table->foreign('shipping_company_id')
                  ->references('id')
                  ->on('shipping_companies')
                  ->onDelete('set null')
                  ->onUpdate('cascade');
            $table->foreign('customer_id')
                ->references('id')
                ->on('users')
                ->onDelete('set null')
                ->onUpdate('cascade');
            $table->foreign('city_id')
                ->references('id')
                ->on('cities')
                ->onDelete('set null')
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
