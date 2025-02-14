<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersTable extends Migration
{
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_id')->unique(); // ID unik transaksi
            $table->unsignedBigInteger('product_id'); // Produk yang dipesan
            $table->integer('quantity'); // Jumlah produk
            $table->decimal('gross_amount', 10, 2); // Total harga
            $table->string('transaction_token')->nullable(); // Token Midtrans
            $table->timestamp('expired_at');
            $table->json('customer_details'); // Data pelanggan
            $table->enum('status', ['pending', 'paid', 'canceled'])->default('pending'); // Status pesanan
            $table->timestamps();

            // Foreign key ke tabel produk (jika ada tabel produk)
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('orders');
    }
}
