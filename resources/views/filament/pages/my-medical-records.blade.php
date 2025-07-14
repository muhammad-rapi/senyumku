<x-filament-panels::page>
    <div class="space-y-6">
        <div class="bg-gradient-to-r from-blue-600 to-blue-700 rounded-xl p-6 text-black">
            <p class="text-blue-100 mt-2">Riwayat lengkap pemeriksaan dan perawatan medis Anda</p>
        </div>

        <div class="bg-black rounded-xl shadow-sm p-6 mb-6">
            <h3 class="text-xl font-semibold text-gray-900 mb-4">Filter Rekam Medis</h3>
            <form wire:submit.prevent="applyFilters" class="grid grid-cols-1 md:grid-cols-3 gap-4 items-end">
                <div>
                    <label for="filterStartDate" class="block text-sm font-medium text-gray-700 bg-black">Dari Tanggal</label>
                    <input type="date" id="filterStartDate" wire:model.live="filterStartDate"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm bg-black text-gray-900">
                </div>
                <div>
                    <label for="filterEndDate" class="block text-sm font-medium text-gray-700">Sampai Tanggal</label>
                    <input type="date" id="filterEndDate" wire:model.live="filterEndDate"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm bg-black text-gray-900">
                </div>
                <div class="flex gap-2">
                    <button type="submit"
                        class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md shadow-sm text-black bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        <x-heroicon-o-funnel class="h-5 w-5 mr-2" />
                        Terapkan Filter
                    </button>
                    <button type="button" wire:click="resetFilters"
                        class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md shadow-sm text-gray-700 bg-black hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        <x-heroicon-o-x-mark class="h-5 w-5 mr-2" />
                        Reset
                    </button>
                </div>
            </form>
        </div>

        @if (($medicalRecords ?? collect())->isEmpty())
            <div class="text-center py-16 bg-black rounded-xl shadow-sm"> {{-- Ganti bg-black ke bg-black --}}
                <div class="bg-gray-50 rounded-full w-20 h-20 mx-auto mb-4 flex items-center justify-center">
                    <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                        </path>
                    </svg>
                </div>
                <h3 class="text-xl font-semibold text-gray-900 mb-2">Belum Ada Rekam Medis</h3>
                <p class="text-gray-600">Rekam medis akan muncul setelah Anda melakukan pemeriksaan.</p>
            </div>
        @else
            @foreach ($medicalRecords as $record)
                <div class="bg-black rounded-xl shadow-lg border border-gray-200 p-6"> {{-- Ganti bg-black ke bg-black --}}
                    <div class="flex items-center justify-between pb-4 border-b border-gray-200 mb-6">
                        <div>
                            <h2 class="text-xl font-bold text-gray-900">
                                @if (isset($record->tanggal_rekam_medis))
                                    {{ $record->tanggal_rekam_medis->format('d F Y') }}
                                @else
                                    Tanggal tidak tersedia
                                @endif
                            </h2>
                            <p class="text-gray-600 mt-1">
                                <span class="font-medium">Dokter:</span>
                                {{ $record->pemeriksaan->dokter->nama ?? 'Tidak tersedia' }}
                            </p>
                        </div>
                        <span class="bg-green-100 text-green-800 px-3 py-1 rounded-full text-sm font-medium">
                            Selesai
                        </span>
                    </div>

                    <div class="mb-6">
                        <div class="bg-blue-50 rounded-lg p-4">
                            <h3 class="font-semibold text-blue-900 mb-2">👤 Informasi Pasien</h3>
                            <p class="text-blue-800">{{ $record->pemeriksaan->pasien->nama ?? 'Tidak tersedia' }}</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                        <div class="bg-orange-50 rounded-lg p-4">
                            <h3 class="font-semibold text-orange-900 mb-2">🕒 Riwayat Penyakit</h3>
                            <div class="text-orange-800 text-sm">
                                {{ $record->riwayat_penyakit ?: 'Tidak ada riwayat penyakit' }}
                            </div>
                        </div>

                        <div class="bg-green-50 rounded-lg p-4">
                            <h3 class="font-semibold text-green-900 mb-2">✅ Hasil Pemeriksaan</h3>
                            <div class="text-green-800 text-sm">
                                {{ $record->hasil_pemeriksaan ?: 'Tidak ada hasil pemeriksaan' }}
                            </div>
                        </div>
                    </div>

                    <div class="space-y-4">
                        <div class="bg-purple-50 rounded-lg p-4">
                            <h3 class="font-semibold text-purple-900 mb-2">🔧 Tindakan Medis</h3>
                            <div class="text-purple-800 text-sm">
                                {{ $record->tindakan ?: 'Tidak ada tindakan khusus' }}
                            </div>
                        </div>

                        <div class="bg-red-50 rounded-lg p-4">
                            <h3 class="font-semibold text-red-900 mb-2">💊 Resep Obat</h3>
                            @if ($record->pemeriksaan && $record->pemeriksaan->resepObat)
                                <div class="text-red-800 text-sm space-y-3">
                                    <p><strong>Instruksi Umum:</strong>
                                        {{ $record->pemeriksaan->resepObat->instruksi_umum ?: 'Tidak ada instruksi umum.' }}
                                    </p>

                                    @if ($record->pemeriksaan->resepObat->resepObatDetails->isNotEmpty())
                                        <h4 class="font-semibold text-red-700 mt-3">Detail Resep:</h4>
                                        <ul class="list-disc list-inside space-y-1">
                                            @foreach ($record->pemeriksaan->resepObat->resepObatDetails as $detail)
                                                <li>
                                                    <strong>{{ $detail->obat->nama_obat ?? 'Obat tidak tersedia' }}</strong>:
                                                    {{ $detail->jumlah }} {{ $detail->obat->satuan ?? 'unit' }} - Dosis:
                                                    {{ $detail->dosis }}
                                                </li>
                                            @endforeach
                                        </ul>
                                    @else
                                        <p>Tidak ada detail obat dalam resep ini.</p>
                                    @endif
                                </div>
                            @else
                                <div class="text-red-800 text-sm">Tidak ada resep obat terkait dengan rekam medis ini.
                                </div>
                            @endif
                        </div>
                    </div>

                    {{-- <div class="mt-4 pt-4 border-t border-gray-200">
                        <details class="text-xs text-gray-500">
                            <summary class="cursor-pointer">Debug Info</summary>
                            <pre class="mt-2 bg-gray-100 p-2 rounded text-xs overflow-auto">{{ print_r($record->toArray(), true) }}</pre>
                        </details>
                    </div> --}}
                </div>
            @endforeach
        @endif
    </div>
</x-filament-panels::page>