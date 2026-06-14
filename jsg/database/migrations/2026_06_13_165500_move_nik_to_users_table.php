<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Tambahkan kolom nik dan pelanggan_id ke tabel users
        Schema::table('users', function (Blueprint $table) {
            $table->string('nik')->unique()->nullable()->after('name');
            $table->foreignId('pelanggan_id')->nullable()->after('nik')
                  ->constrained('pelanggans')->onDelete('cascade');
        });

        // 2. Migrasi data: buat user untuk setiap pelanggan yang belum punya user
        $pelanggans = DB::table('pelanggans')->get();

        foreach ($pelanggans as $pelanggan) {
            // Cek apakah sudah ada user dengan pelanggan_id ini
            $existingUser = DB::table('users')->where('pelanggan_id', $pelanggan->id)->first();

            if (!$existingUser) {
                DB::table('users')->insert([
                    'name' => $pelanggan->nama,
                    'nik' => $pelanggan->nik,
                    'pelanggan_id' => $pelanggan->id,
                    'email' => $pelanggan->nik . '@pelanggan.local',
                    'password' => Hash::make('123'),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }

        // 3. Hapus kolom nik dan password dari tabel pelanggans
        Schema::table('pelanggans', function (Blueprint $table) {
            $table->dropColumn(['nik', 'password']);
        });
    }

    public function down(): void
    {
        // 1. Tambahkan kembali kolom nik dan password ke pelanggans
        Schema::table('pelanggans', function (Blueprint $table) {
            $table->string('nik')->unique()->after('nama');
            $table->string('password')->nullable()->after('no_telepon');
        });

        // 2. Migrasi data kembali dari users ke pelanggans
        $users = DB::table('users')->whereNotNull('pelanggan_id')->get();

        foreach ($users as $user) {
            DB::table('pelanggans')
                ->where('id', $user->pelanggan_id)
                ->update([
                    'nik' => $user->nik,
                    'password' => $user->password,
                ]);
        }

        // 3. Hapus kolom nik dan pelanggan_id dari users
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['pelanggan_id']);
            $table->dropColumn(['nik', 'pelanggan_id']);
        });
    }
};
