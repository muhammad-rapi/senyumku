<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use App\Models\Pasien;
use App\Models\Pendaftaran;
use App\Models\Obat;

class DashboardStatsOverview extends BaseWidget
{

    public function canAccess(): bool
    {
        return auth()->user()->can('view_dashboard');
    }

    protected function getStats(): array
    {
        return [
            Stat::make('Total Pasien', Pasien::count())
                ->description('Jumlah pasien')
                ->descriptionIcon('heroicon-o-users')
                ->color('info'),
            Stat::make('Pendaftaran Hari Ini', Pendaftaran::whereDate('tanggal_pendaftaran', today())->count())
                ->description('Pendaftaran baru hari ini')
                ->descriptionIcon('heroicon-o-document-plus')
                ->color('success'),
            Stat::make('Obat Tersedia', Obat::sum('stok')) // Asumsi ada kolom 'stok' di model Obat
                ->description('Total stok obat di inventaris')
                ->descriptionIcon('heroicon-o-archive-box')
                ->color('warning'),
            // Tambahkan stat lainnya sesuai kebutuhan
        ];
    }
}
