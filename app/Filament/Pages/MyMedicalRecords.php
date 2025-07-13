<?php

namespace App\Filament\Pages;

use App\Models\RekamMedis;
use Filament\Pages\Page;
use Filament\Support\Exceptions\Halt;
use Filament\Infolists\Infolist;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Illuminate\Support\Facades\Auth;

class MyMedicalRecords extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-folder-open';
    protected static string $view = 'filament.pages.my-medical-records';
    protected static ?string $navigationLabel = 'Rekam Medis Saya';
    protected static ?int $navigationSort = 2;

    public $medicalRecords;

    public function mount(): void
    {
        // Periksa apakah user login, memiliki role 'pasien', DAN memiliki relasi 'pasien'
        if (!Auth::check() || !Auth::user()->hasRole('pasien') || !Auth::user()->pasien) {
            // Jika salah satu kondisi tidak terpenuhi, lempar Halt dan hentikan eksekusi
            // throw new Halt();
            return; // Tambahkan ini untuk memastikan tidak ada kode lain yang dieksekusi
        }

        // Jika sampai sini, berarti Auth::user() ada, memiliki role 'pasien', dan memiliki relasi 'pasien'
        $pasienId = Auth::user()->pasien->id;

        $this->medicalRecords = RekamMedis::where('pasien_id', $pasienId)
                                        ->orderBy('tanggal_rekam_medis', 'desc')
                                        ->get();
    }

    public static function shouldRegisterNavigation(): bool
    {
        return Auth::check() && Auth::user()->hasRole('pasien');
    }

    public function medicalRecordInfolist(RekamMedis $record): Infolist
    {
        return Infolist::make($record)
            ->schema([
                Section::make('Detail Rekam Medis')
                    ->schema([
                        TextEntry::make('pasien.nama')
                            ->label('Nama Pasien'),
                        TextEntry::make('dokter.nama')
                            ->label('Nama Dokter'),
                        TextEntry::make('tanggal_rekam_medis')
                            ->label('Tanggal Rekam Medis')
                            ->date('d/m/Y'),
                        TextEntry::make('riwayat_penyakit')
                            ->label('Riwayat Penyakit')
                            ->markdown()
                            ->columnSpanFull(),
                        TextEntry::make('hasil_pemeriksaan')
                            ->label('Hasil Pemeriksaan')
                            ->markdown()
                            ->columnSpanFull(),
                        TextEntry::make('tindakan')
                            ->label('Tindakan')
                            ->markdown()
                            ->columnSpanFull(),
                        TextEntry::make('resep_obat_text')
                            ->label('Resep Obat (Teks)')
                            ->markdown()
                            ->columnSpanFull(),
                    ])->columns(2),
            ]);
    }
}