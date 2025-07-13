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
        Schema::create('resep_obats', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pemeriksaan_id')->constrained('pemeriksaans')->onDelete('cascade');
            $table->foreignId('dokter_id')->constrained('dokters')->onDelete('cascade');
            $table->date('tanggal_resep');
            $table->text('instruksi_umum')->nullable();
            $table->timestamps();
        });

        // Tabel pivot untuk relasi many-to-many antara ResepObat dan Obat
        Schema::create('resep_obat_detail', function (Blueprint $table) {
            $table->id();
            $table->foreignId('resep_obat_id')->constrained('resep_obats')->onDelete('cascade');
            $table->foreignId('obat_id')->constrained('obats')->onDelete('cascade');
            $table->integer('jumlah');
            $table->string('dosis'); // Contoh: 1x sehari, 2x sehari setelah makan
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('resep_obats');
    }
};
