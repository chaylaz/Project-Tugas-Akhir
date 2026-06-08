<?php

namespace App\Console\Commands;

use App\Models\Pelanggan;
use App\Models\Tagihan;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Console\Command;

class CheckMasaAktifPelanggan extends Command
{
    protected $signature = 'app:check-masa-aktif-pelanggan';

    protected $description = 'Periksa masa aktif pelanggan, ubah status menjadi non-aktif, dan buat tagihan otomatis.';

    public function handle(): int
    {
        $this->info('Memulai pengecekan masa aktif pelanggan...');

        $today = Carbon::today();

        $adminId = User::orderBy('id')->first()?->id ?? 1;

        $indonesianMonths = [
            'January' => 'Januari',
            'February' => 'Februari',
            'March' => 'Maret',
            'April' => 'April',
            'May' => 'Mei',
            'June' => 'Juni',
            'July' => 'Juli',
            'August' => 'Agustus',
            'September' => 'September',
            'October' => 'Oktober',
            'November' => 'November',
            'December' => 'Desember',
        ];

        $pelanggans = Pelanggan::with('paket')
            ->whereDate('masa_aktif', '<=', $today)
            ->get();

        if ($pelanggans->isEmpty()) {
            $this->info('Tidak ada pelanggan yang masa aktifnya habis.');

            return Command::SUCCESS;
        }

        foreach ($pelanggans as $pelanggan) {
            /** @var Pelanggan $pelanggan */

            $masaAktif = Carbon::parse($pelanggan->masa_aktif);

            $englishMonth = $masaAktif->format('F');
            $year = $masaAktif->format('Y');
            $indonesianMonth = $indonesianMonths[$englishMonth] ?? $englishMonth;
            $periode = "{$indonesianMonth} {$year}";

            $this->info("Masa aktif pelanggan {$pelanggan->nama} sudah habis pada {$masaAktif->toDateString()}.");

            $tagihanExist = Tagihan::generateForPelanggan($pelanggan, $masaAktif);

            if ($tagihanExist->wasRecentlyCreated) {
                Pelanggan::whereKey($pelanggan->id)->update([
                    'status_layanan' => 'non-aktif',
                ]);

                $this->info("Berhasil membuat tagihan untuk {$pelanggan->nama} periode {$periode}.");
                $this->info("Status layanan {$pelanggan->nama} diubah menjadi non-aktif.");

                continue;
            }

            if ($tagihanExist->status_pembayaran === 'belum') {
                Pelanggan::whereKey($pelanggan->id)->update([
                    'status_layanan' => 'non-aktif',
                ]);

                $this->info("Tagihan untuk {$pelanggan->nama} periode {$periode} sudah ada dan belum dibayar.");
                $this->info("Status layanan {$pelanggan->nama} diubah menjadi non-aktif.");

                continue;
            }

            if ($tagihanExist->status_pembayaran === 'lunas') {
                $masaAktifBaru = $masaAktif->copy()->addMonth()->toDateString();

                Pelanggan::whereKey($pelanggan->id)->update([
                    'status_layanan' => 'aktif',
                    'masa_aktif' => $masaAktifBaru,
                ]);

                $this->info("Tagihan untuk {$pelanggan->nama} periode {$periode} sudah lunas.");
                $this->info("Status layanan {$pelanggan->nama} diubah menjadi aktif dan masa aktif diperpanjang sampai {$masaAktifBaru}.");

                continue;
            }
        }

        $this->info('Pengecekan masa aktif pelanggan selesai.');

        return Command::SUCCESS;
    }
}