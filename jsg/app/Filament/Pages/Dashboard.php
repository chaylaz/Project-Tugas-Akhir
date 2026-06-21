<?php

namespace App\Filament\Pages;

use Filament\Actions\Action;
use Filament\Pages\Dashboard as BaseDashboard;
use Filament\Forms\Components\Select;
use App\Models\Tagihan;
use Barryvdh\DomPDF\Facade\Pdf;

class Dashboard extends BaseDashboard
{
    protected function getHeaderActions(): array
    {
        return [
            Action::make('export')
                ->label('Export Pendapatan')
                ->icon('heroicon-o-arrow-down-tray')
                ->color('primary')
                ->form([
                    Select::make('tipe_export')
                        ->label('Tipe Export')
                        ->options([
                            'bulan' => 'Per Bulan',
                            'tahun' => 'Per Tahun',
                        ])
                        ->required()
                        ->reactive()
                        ->default('bulan'),

                    Select::make('bulan')
                        ->label('Bulan')
                        ->options([
                            '01' => 'Januari',
                            '02' => 'Februari',
                            '03' => 'Maret',
                            '04' => 'April',
                            '05' => 'Mei',
                            '06' => 'Juni',
                            '07' => 'Juli',
                            '08' => 'Agustus',
                            '09' => 'September',
                            '10' => 'Oktober',
                            '11' => 'November',
                            '12' => 'Desember',
                        ])
                        ->visible(fn ($get) => $get('tipe_export') === 'bulan')
                        ->required(fn ($get) => $get('tipe_export') === 'bulan')
                        ->default(now()->format('m')),

                    Select::make('tahun_bulan')
                        ->label('Tahun')
                        ->options(array_combine(range(2025, 2035), range(2025, 2035)))
                        ->visible(fn ($get) => $get('tipe_export') === 'bulan')
                        ->required(fn ($get) => $get('tipe_export') === 'bulan')
                        ->default(now()->format('Y')),

                    Select::make('tahun')
                        ->label('Tahun')
                        ->options(array_combine(range(2025, 2035), range(2025, 2035)))
                        ->visible(fn ($get) => $get('tipe_export') === 'tahun')
                        ->required(fn ($get) => $get('tipe_export') === 'tahun')
                        ->default(now()->format('Y')),
                ])
                ->action(function (array $data) {
                    $tipe = $data['tipe_export'];
                    
                    $query = Tagihan::query()
                        ->where('status_pembayaran', 'lunas')
                        ->with(['pelanggan', 'paket']);

                    if ($tipe === 'bulan') {
                        $bulan = $data['bulan'];
                        $tahun = $data['tahun_bulan'];
                        $query->whereYear('updated_at', $tahun)
                              ->whereMonth('updated_at', $bulan);
                        
                        $namaBulan = [
                            '01' => 'Januari', '02' => 'Februari', '03' => 'Maret', '04' => 'April',
                            '05' => 'Mei', '06' => 'Juni', '07' => 'Juli', '08' => 'Agustus',
                            '09' => 'September', '10' => 'Oktober', '11' => 'November', '12' => 'Desember'
                        ][$bulan];
                        $filename = "laporan-pendapatan-{$namaBulan}-{$tahun}.pdf";
                        $periodHeader = "Periode Laporan: {$namaBulan} {$tahun}";
                    } else {
                        $tahun = $data['tahun'];
                        $query->whereYear('updated_at', $tahun);
                        $filename = "laporan-pendapatan-{$tahun}.pdf";
                        $periodHeader = "Periode Laporan: Tahun {$tahun}";
                    }

                    $tagihans = $query->orderBy('updated_at', 'asc')->get();
                    $totalPendapatan = $tagihans->sum('jumlah');
                    $downloadTime = now('Asia/Jakarta')->format('d-m-Y H:i');

                    $pdf = Pdf::loadView('exports.laporan-pendapatan', [
                        'tagihans' => $tagihans,
                        'totalPendapatan' => $totalPendapatan,
                        'periodHeader' => $periodHeader,
                        'downloadTime' => $downloadTime,
                    ]);

                    return response()->streamDownload(function () use ($pdf) {
                        echo $pdf->output();
                    }, $filename, [
                        'Content-Type' => 'application/pdf',
                        'Content-Disposition' => "attachment; filename=\"{$filename}\"",
                    ]);
                }),
        ];
    }
}
