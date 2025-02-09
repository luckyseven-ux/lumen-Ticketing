<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade'); // User yang memesan
            $table->foreignId('ticket_id')->constrained('tickets')->onDelete('cascade'); // Tiket yang dipesan
            $table->integer('quantity'); // Jumlah tiket yang dipesan
            $table->enum('status', ['pending', 'paid', 'canceled'])->default('pending'); // Status order
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
