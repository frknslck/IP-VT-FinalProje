<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('supplied_products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->foreignId('supplier_id')->constrained()->onDelete('cascade');
            $table->decimal('cost_price', 10, 2);
            $table->integer('minimum_order_quantity');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('supplied_products');
    }
};