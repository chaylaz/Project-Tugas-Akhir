<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pelanggans', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->string('nik')->unique();
            $table->text('alamat');
            $table->string('no_telepon');

            $table->foreignId('paket_layanan_id')
                  ->constrained('paket_layanan')
                  ->onDelete('cascade');

            $table->enum('status_layanan', ['aktif', 'non-aktif', 'cutoff'])->default('aktif');
            $table->date('masa_aktif');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pelanggans');
    }
};