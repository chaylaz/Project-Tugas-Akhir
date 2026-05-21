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
        Schema::create('tagihan', function (Blueprint $table) {
            $table->id();

            $table->foreignId('pelanggan_id')
                  ->constrained('pelanggans')
                  ->onDelete('cascade');

            $table->foreignId('paket_layanan_id')
                  ->constrained('paket_layanan')
                  ->onDelete('cascade');

            $table->foreignId('created_by')
                  ->constrained('users')
                  ->onDelete('cascade');

            $table->string('periode');
            $table->decimal('jumlah', 12, 2);
            $table->date('due_date');

            $table->enum('status_pembayaran', ['belum', 'lunas'])
                  ->default('belum');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tagihan');
    }
};