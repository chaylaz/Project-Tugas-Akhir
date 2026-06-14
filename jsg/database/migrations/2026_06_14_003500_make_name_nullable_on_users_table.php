<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('name')->nullable()->change();
        });

        // Set name = null untuk user yang punya pelanggan (nama diambil dari relasi)
        DB::table('users')
            ->whereNotNull('pelanggan_id')
            ->update(['name' => null]);
    }

    public function down(): void
    {
        // Kembalikan name dari pelanggan
        $users = DB::table('users')
            ->whereNotNull('pelanggan_id')
            ->whereNull('name')
            ->get();

        foreach ($users as $user) {
            $pelanggan = DB::table('pelanggans')->where('id', $user->pelanggan_id)->first();
            if ($pelanggan) {
                DB::table('users')->where('id', $user->id)->update(['name' => $pelanggan->nama]);
            }
        }

        Schema::table('users', function (Blueprint $table) {
            $table->string('name')->nullable(false)->change();
        });
    }
};
