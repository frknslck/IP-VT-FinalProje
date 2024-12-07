<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('returns_exchanges', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->onDelete('cascade');
            $table->foreignId('product_variant_id')->constrained();
            $table->enum('type', ['return', 'exchange']);
            $table->text('reason');
            $table->enum('status', ['pending', 'approved', 'rejected', 'completed']);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('returns_exchanges');
    }
};