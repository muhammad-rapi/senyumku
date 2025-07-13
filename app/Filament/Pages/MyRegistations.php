<?php

namespace App\Filament\Pages;

use App\Models\Pendaftaran;
use Filament\Pages\Page;
use Filament\Support\Exceptions\Halt;
use Illuminate\Support\Facades\Auth; // Import Auth

class MyRegistrations extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-check';
    protected static string $view = 'filament.pages.my-registrations';
    protected static ?string $navigationLabel = 'Pendaftaran Saya';
    protected static ?string $title = 'Pendaftaran Saya';
    protected static ?int $navigationSort = 1;

    public $pendaftarans;

    public function mount(): void
    {
        if (!Auth::user()->hasRole('pasien') || !Auth::user()->pasien) {
            redirect()->to(url('/admin'));
            throw new Halt();
        }

        $pasienId = Auth::user()->pasien->id;

        $this->pendaftarans = Pendaftaran::where('pasien_id', $pasienId)
                                        ->orderBy('tanggal_pendaftaran', 'desc')
                                        ->orderBy('waktu_pendaftaran', 'desc')
                                        ->get();
    }

    public static function shouldRegisterNavigation(): bool
    {
        // Halaman ini hanya tampil di sidebar untuk user dengan role 'pasien'
        return Auth::check() && Auth::user()->hasRole('pasien');
    }
}