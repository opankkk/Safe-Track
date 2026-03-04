<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('report_actions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('report_id')->constrained()->onDelete('cascade');
            $table->foreignId('uploaded_by')->constrained('users'); // PIC yang upload
            $table->string('file_path'); // Path laporan hasil (After)
            $table->text('description')->nullable();
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->text('manager_note')->nullable(); // Alasan jika ditolak manager
            $table->text('hse_note')->nullable();     // Alasan jika ditolak HSE di tahap akhir
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('report_actions');
    }
};