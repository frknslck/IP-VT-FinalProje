<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('product_variants', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->string('sku')->unique();
            $table->string('name');
            $table->decimal('price', 10, 2);
            $table->foreignId('color_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('size_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('material_id')->nullable()->constrained()->onDelete('set null');
            $table->integer('stock')->nullable()->default(0);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('product_variants');
    }
};