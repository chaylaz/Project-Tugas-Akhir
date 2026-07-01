<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Ubah data yang masih 'cutoff' menjadi 'non-aktif'
        DB::table('pelanggans')->where('status_layanan', 'cutoff')->update(['status_layanan' => 'non-aktif']);

        // Ubah enum status_layanan: hapus 'cutoff', sisakan 'aktif' dan 'non-aktif'
        DB::statement("ALTER TABLE pelanggans MODIFY COLUMN status_layanan ENUM('aktif', 'non-aktif') NOT NULL DEFAULT 'aktif'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Kembalikan enum dengan 'cutoff'
        DB::statement("ALTER TABLE pelanggans MODIFY COLUMN status_layanan ENUM('aktif', 'non-aktif', 'cutoff') NOT NULL DEFAULT 'aktif'");
    }
};
