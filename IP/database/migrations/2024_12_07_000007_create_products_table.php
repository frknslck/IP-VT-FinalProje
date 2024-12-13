<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description');
            $table->decimal('price', 10, 2);
            $table->foreignId('brand_id')->constrained()->onDelete('cascade');
            $table->boolean('is_active')->default(true);
            $table->boolean('best_seller')->default(false);
            $table->timestamps();
        });

        Schema::create('product_variants', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->string('sku')->unique();
            $table->string('name');
            $table->decimal('price', 10, 2);
            $table->foreignId('color_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('size_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('material_id')->nullable()->constrained()->onDelete('set null');
            $table->timestamps();
        });

        Schema::create('product_reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->integer('rating');
            $table->text('comment')->nullable();
            $table->boolean('is_approved')->default(false);
            $table->timestamps();
        });

        Schema::table('product_reviews', function (Blueprint $table) {
            $table->unique(['product_id', 'user_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('product_reviews');
        Schema::dropIfExists('product_variants');
        Schema::dropIfExists('products');
    }
};