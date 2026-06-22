<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up() {
    Schema::create('report_unsafe_details', function (Blueprint $table) {
        $table->id();
        $table->foreignId('report_id')->constrained()->onDelete('cascade');
        $table->date('tanggal_pengamatan');
        $table->time('waktu_pengamatan');
        $table->string('status_pengamat');
        $table->string('departemen');
        $table->string('perusahaan')->nullable();
        $table->text('deskripsi_pengamatan'); 
        $table->string('lokasi');
        $table->text('dampak');
        $table->text('perbaikan');
        $table->timestamps();
    });
}

    public function down(): void
    {
        Schema::dropIfExists('report_unsafe_details');
    }
};
