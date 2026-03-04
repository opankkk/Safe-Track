<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up() {
    Schema::create('report_accident_details', function (Blueprint $table) {
        $table->id();
        $table->foreignId('report_id')->constrained()->onDelete('cascade');
        $table->string('jenis_insiden');
        $table->string('jenis_kelamin');
        $table->string('lokasi_kerja');
        $table->string('departemen');
        $table->string('nama_korban')->nullable();
        $table->string('tempat');
        $table->date('tanggal');
        $table->time('pukul');
        $table->text('uraian_insiden');
        $table->string('apd'); // Ya / Tidak
        $table->string('apd_alasan')->nullable();
        $table->json('kondisi_korban'); // Untuk checkbox array
        $table->text('kerusakan_property')->nullable();
        $table->text('pencemaran_lingkungan')->nullable();
        $table->string('tindak_lanjut');
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('report_accident_details');
    }
};
