<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('rekam_medis', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pasien_id')->constrained('pasiens')->onDelete('cascade');
            $table->foreignId('dokter_id')->constrained('dokters')->onDelete('cascade');
            $table->foreignId('pemeriksaan_id')->nullable()->constrained('pemeriksaans')->onDelete('set null'); // Bisa null jika rekam medis dibuat tanpa pemeriksaan langsung
            $table->date('tanggal_rekam_medis');
            $table->text('riwayat_penyakit')->nullable();
            $table->text('hasil_pemeriksaan');
            $table->text('tindakan')->nullable();
            $table->text('resep_obat_text')->nullable(); // Untuk menyimpan resep dalam bentuk teks jika tidak ada relasi langsung ke ResepObat
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rekam_medis');
    }
};
