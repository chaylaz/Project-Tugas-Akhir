<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Drop unique index lama, ubah kolom jadi nullable, tambah unique lagi
        Schema::table('users', function (Blueprint $table) {
            $table->dropUnique('users_email_unique');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->string('email')->nullable()->change();
            $table->unique('email');
        });

        // 2. Set email = null untuk user yang punya email @pelanggan.local
        DB::table('users')
            ->where('email', 'like', '%@pelanggan.local')
            ->update(['email' => null]);
    }

    public function down(): void
    {
        // Kembalikan email placeholder untuk user yang null
        $users = DB::table('users')->whereNull('email')->get();
        foreach ($users as $user) {
            DB::table('users')
                ->where('id', $user->id)
                ->update(['email' => ($user->nik ?? $user->id) . '@pelanggan.local']);
        }

        Schema::table('users', function (Blueprint $table) {
            $table->string('email')->nullable(false)->unique()->change();
        });
    }
};
