<?php

namespace App\Filament\Widgets;

use App\Models\Pendaftaran;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;
use Illuminate\Support\Facades\Auth; // Import Auth

class RecentRegistrationsOverview extends TableWidget
{
    protected int | string | array $columnSpan = 'full';
    protected static ?int $sort = 1;

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Pendaftaran::query()->latest()->limit(5)
            )
            ->heading('5 Pendaftaran Terbaru')
            ->columns([
                TextColumn::make('pasien.nama')->label('Nama Pasien'),
                TextColumn::make('tanggal_pendaftaran')->label('Tanggal Pendaftaran')->date('d/m/Y'),
                TextColumn::make('waktu_pendaftaran')->label('Waktu')->time('H:i'),
                TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'Menunggu Konfirmasi' => 'warning',
                        'Dikonfirmasi' => 'info',
                        'Selesai' => 'success',
                        'Dibatalkan' => 'danger',
                    })
                    ->label('Status'),
            ]);
    }

    public static function canView(): bool
    {
        // Widget ini hanya terlihat oleh admin, staf_administrasi, dan staf_pengelola_obat
        return Auth::check() && Auth::user()->hasAnyRole(['admin', 'staff_administrasi', 'staf_pengelola_obat']);
    }
}