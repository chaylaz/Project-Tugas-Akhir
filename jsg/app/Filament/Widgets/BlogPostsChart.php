<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;

class BlogPostsChart extends ChartWidget
{
    protected static ?int $sort = 2;
    protected ?string $heading = 'Grafik Pendapatan Tahun Ini';
    protected int | string | array $columnSpan = 'full';

    protected function getData(): array
    {
        $data = [];
        $months = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
        $currentYear = \Carbon\Carbon::now()->year;

        for ($i = 1; $i <= 12; $i++) {
            $sum = \App\Models\Tagihan::where('status_pembayaran', 'lunas')
                ->whereYear('updated_at', $currentYear)
                ->whereMonth('updated_at', $i)
                ->sum('jumlah');
            
            $data[] = $sum;
        }

        return [
            'datasets' => [
                [
                    'label' => 'Pendapatan (Rp)',
                    'data' => $data,
                    'backgroundColor' => '#eab308', // yellow-500
                ],
            ],
            'labels' => $months,
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}
