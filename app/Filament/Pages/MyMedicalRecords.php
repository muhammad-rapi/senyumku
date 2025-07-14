<?php

namespace App\Filament\Pages;

use App\Models\RekamMedis;
use Filament\Pages\Page;
use Filament\Support\Exceptions\Halt;
use Filament\Infolists\Infolist;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Collection; // Import Collection
use Carbon\Carbon; // Import Carbon untuk tanggal

class MyMedicalRecords extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-folder-open';
    protected static string $view = 'filament.pages.my-medical-records';
    protected static ?string $navigationLabel = 'Rekam Medis Saya';
    protected static ?int $navigationSort = 2;

    public Collection $medicalRecords;
    
    // Properti baru untuk filter
    public ?string $filterStartDate = null;
    public ?string $filterEndDate = null;

    public function mount(): void
    {
        $this->medicalRecords = new Collection();

        if (!Auth::check() || !Auth::user()->hasRole('pasien') || !Auth::user()->pasien) {
            return;
        }

        // Ambil nilai filter dari request jika ada
        // Filament Livewire secara otomatis akan mengisi properti publik ini dari URL query string
        $this->filterStartDate = request()->query('start_date', $this->filterStartDate);
        $this->filterEndDate = request()->query('end_date', $this->filterEndDate);

        $this->loadMedicalRecords(); // Panggil metode terpisah untuk memuat data
    }

    public function loadMedicalRecords(): void
    {
        if (!Auth::check() || !Auth::user()->hasRole('pasien') || !Auth::user()->pasien) {
            $this->medicalRecords = new Collection();
            return;
        }

        $pasienId = Auth::user()->pasien->id;

        $query = RekamMedis::where('pasien_id', $pasienId)
                            ->with([
                                'pemeriksaan',
                                'pemeriksaan.resepObat',
                                'pemeriksaan.resepObat.resepObatDetails.obat'
                            ]);

        // Terapkan filter tanggal jika ada
        if ($this->filterStartDate) {
            $query->whereDate('tanggal_rekam_medis', '>=', Carbon::parse($this->filterStartDate));
        }

        if ($this->filterEndDate) {
            $query->whereDate('tanggal_rekam_medis', '<=', Carbon::parse($this->filterEndDate));
        }

        $this->medicalRecords = $query->orderBy('tanggal_rekam_medis', 'desc')->get();
    }

    // Metode untuk mereset filter
    public function resetFilters(): void
    {
        $this->filterStartDate = null;
        $this->filterEndDate = null;
        $this->loadMedicalRecords(); // Muat ulang data tanpa filter
    }

    // Metode untuk menerapkan filter (dipanggil dari form)
    public function applyFilters(): void
    {
        // Livewire akan secara otomatis memperbarui properti $filterStartDate dan $filterEndDate
        $this->loadMedicalRecords(); // Muat ulang data dengan filter baru
    }

    public static function shouldRegisterNavigation(): bool
    {
        return Auth::check() && Auth::user()->hasRole('pasien');
    }
}