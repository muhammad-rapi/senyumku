<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;
use App\Models\Pendaftaran;
use Flowbite\Flowbite; // Pastikan ini diinstal dan diimpor jika menggunakan Flowbite untuk chart.

class RegistrationsChart extends ChartWidget
{
    protected static ?string $heading = 'Pendaftaran Per Bulan';
    protected static ?int $sort = 2; // Mengatur posisi widget

    protected function getType(): string
    {
        return 'line'; // Atau 'bar'
    }

    protected function getData(): array
    {
        $data = Pendaftaran::selectRaw('MONTH(tanggal_pendaftaran) as month, COUNT(*) as count')
                            ->groupBy('month')
                            ->orderBy('month')
                            ->get();

        $labels = [];
        $counts = [];

        foreach (range(1, 12) as $month) {
            $monthName = date('M', mktime(0, 0, 0, $month, 10)); // Nama bulan singkat
            $labels[] = $monthName;
            $counts[] = $data->where('month', $month)->first()->count ?? 0;
        }

        return [
            'labels' => $labels,
            'datasets' => [
                [
                    'label' => 'Jumlah Pendaftaran',
                    'data' => $counts,
                    'borderColor' => '#2196F3', // Warna biru untuk garis
                    'backgroundColor' => 'rgba(33, 150, 243, 0.2)', // Warna latar belakang area
                    'fill' => true,
                ],
            ],
        ];
    }
}
