<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateComplaintsTable extends Migration
{
    public function up(): void
    {
        Schema::create('request_complaints', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->enum('RorC', ['Request', 'Complaint']);
            $table->string('subject');
            $table->enum('category', ['Category', 'Brand', 'Product', 'User', 'Review', 'Order', 'Campaign', 'Other']);
            $table->text('message');
            $table->enum('status', ['Pending', 'Reviewed', 'Resolved'])->default('Pending');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('complaints');
    }
}
