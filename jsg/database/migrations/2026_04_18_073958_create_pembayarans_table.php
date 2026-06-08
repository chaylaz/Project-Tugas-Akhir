<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (! Schema::hasTable('pembayaran')) {
            Schema::create('pembayaran', function (Blueprint $table) {
                $table->id();

                $table->foreignId('tagihan_id')
                    ->constrained('tagihan')
                    ->onDelete('cascade');

                $table->dateTime('tanggal_pembayaran');
                $table->enum('metode', ['cash', 'transfer', 'midtrans']);
                $table->decimal('jumlah_bayar', 12, 2);

                $table->string('midtrans_order_id')->nullable();
                $table->string('midtrans_status')->nullable();

                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pembayaran');
    }
};