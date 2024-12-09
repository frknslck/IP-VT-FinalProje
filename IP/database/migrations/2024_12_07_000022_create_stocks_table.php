<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('stocks', function (Blueprint $table) {
            $table->id();
            $table->string('sku');
            $table->integer('quantity');
            $table->timestamps();

            $table->foreign('sku')->references('sku')->on('product_variants')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('stocks');
    }
};