<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Ubah enum metode: hapus 'midtrans', sisakan 'cash' dan 'transfer'
        DB::statement("ALTER TABLE pembayaran MODIFY COLUMN metode ENUM('cash', 'transfer') NOT NULL");

        // Hapus kolom terkait midtrans
        Schema::table('pembayaran', function (Blueprint $table) {
            $table->dropColumn(['midtrans_order_id', 'midtrans_status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Kembalikan enum metode dengan 'midtrans'
        DB::statement("ALTER TABLE pembayaran MODIFY COLUMN metode ENUM('cash', 'transfer', 'midtrans') NOT NULL");

        // Kembalikan kolom midtrans
        Schema::table('pembayaran', function (Blueprint $table) {
            $table->string('midtrans_order_id')->nullable()->after('jumlah_bayar');
            $table->string('midtrans_status')->nullable()->after('midtrans_order_id');
        });
    }
};
