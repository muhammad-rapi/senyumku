<x-filament-panels::page>
    <div class="space-y-6">
        <!-- Header -->
        <div class="bg-gradient-to-r from-blue-600 to-blue-700 rounded-xl p-6 text-black">
            <h1 class="text-3xl font-bold">Rekam Medis Saya</h1>
            <p class="text-blue-100 mt-2">Riwayat lengkap pemeriksaan dan perawatan medis Anda</p>
        </div>
        @if ($medicalRecords->isEmpty())
            <div class="text-center py-16 bg-black rounded-xl shadow-sm">
                <div class="bg-gray-50 rounded-full w-20 h-20 mx-auto mb-4 flex items-center justify-center">
                    <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                </div>
                <h3 class="text-xl font-semibold text-gray-900 mb-2">Belum Ada Rekam Medis</h3>
                <p class="text-gray-600">Rekam medis akan muncul setelah Anda melakukan pemeriksaan.</p>
            </div>
        @else
            @foreach ($medicalRecords as $record)
                <div class="bg-black rounded-xl shadow-lg border border-gray-200 p-6">
                    <!-- Header Card -->
                    <div class="flex items-center justify-between pb-4 border-b border-gray-200 mb-6">
                        <div>
                            <h2 class="text-xl font-bold text-gray-900">
                                @if(isset($record->tanggal_rekam_medis))
                                    {{ $record->tanggal_rekam_medis->format('d F Y') }}
                                @else
                                    Tanggal tidak tersedia
                                @endif
                            </h2>
                            <p class="text-gray-600 mt-1">
                                <span class="font-medium">Dokter:</span> 
                                {{ $record->dokter->nama ?? 'Tidak tersedia' }}
                            </p>
                        </div>
                        <span class="bg-green-100 text-green-800 px-3 py-1 rounded-full text-sm font-medium">
                            Selesai
                        </span>
                    </div>

                    <!-- Patient Info -->
                    <div class="mb-6">
                        <div class="bg-blue-50 rounded-lg p-4">
                            <h3 class="font-semibold text-blue-900 mb-2">👤 Informasi Pasien</h3>
                            <p class="text-blue-800">{{ $record->pasien->nama ?? 'Tidak tersedia' }}</p>
                        </div>
                    </div>

                    <!-- Medical Information -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                        <!-- Riwayat Penyakit -->
                        <div class="bg-orange-50 rounded-lg p-4">
                            <h3 class="font-semibold text-orange-900 mb-2">🕒 Riwayat Penyakit</h3>
                            <div class="text-orange-800 text-sm">
                                {{ $record->riwayat_penyakit ?: 'Tidak ada riwayat penyakit' }}
                            </div>
                        </div>

                        <!-- Hasil Pemeriksaan -->
                        <div class="bg-green-50 rounded-lg p-4">
                            <h3 class="font-semibold text-green-900 mb-2">✅ Hasil Pemeriksaan</h3>
                            <div class="text-green-800 text-sm">
                                {{ $record->hasil_pemeriksaan ?: 'Tidak ada hasil pemeriksaan' }}
                            </div>
                        </div>
                    </div>

                    <!-- Full Width Sections -->
                    <div class="space-y-4">
                        <!-- Tindakan -->
                        <div class="bg-purple-50 rounded-lg p-4">
                            <h3 class="font-semibold text-purple-900 mb-2">🔧 Tindakan Medis</h3>
                            <div class="text-purple-800 text-sm">
                                {{ $record->tindakan ?: 'Tidak ada tindakan khusus' }}
                            </div>
                        </div>

                        <!-- Resep Obat -->
                        <div class="bg-red-50 rounded-lg p-4">
                            <h3 class="font-semibold text-red-900 mb-2">💊 Resep Obat</h3>
                            <div class="text-red-800 text-sm">
                                {{ $record->resep_obat_text ?: 'Tidak ada resep obat' }}
                            </div>
                        </div>
                    </div>

                    {{-- <!-- Debug untuk setiap record -->
                    <div class="mt-4 pt-4 border-t border-gray-200">
                        <details class="text-xs text-gray-500">
                            <summary class="cursor-pointer">Debug Info</summary>
                            <pre class="mt-2 bg-gray-100 p-2 rounded text-xs overflow-auto">{{ print_r($record->toArray(), true) }}</pre>
                        </details>
                    </div>
                </div> --}}
            @endforeach
        @endif
    </div>
</x-filament-panels::page>