<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('report_plans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('report_id')->constrained()->onDelete('cascade');
            $table->foreignId('uploaded_by')->constrained('users'); // PIC yang upload
            $table->string('file_path'); // Path dokumen rencana
            $table->text('description')->nullable();
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->text('manager_note')->nullable(); // Alasan jika ditolak manager
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('report_plans');
    }
};