<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('sizes', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('code')->nullable();
            $table->decimal('chance', 5, 2)->default(1.00);
            $table->string('category');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('sizes');
    }
};