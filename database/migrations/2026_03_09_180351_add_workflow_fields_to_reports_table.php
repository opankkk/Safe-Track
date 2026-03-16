<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Sub-status alur kerja:
     *  pending_hse            → laporan baru, menunggu HSE
     *  waiting_pic            → HSE setuju, menunggu PIC upload Plan
     *  plan_verification      → PIC upload Plan, menunggu Manager
     *  plan_rejected_manager  → Manager tolak Plan (ada catatan manager)
     *  plan_approved_manager  → Manager setuju Plan, PIC konfirmasi
     *  pic_working            → PIC dalam pengerjaan di luar sistem
     *  report_pending_hse     → Manager setuju Hasil, menunggu HSE final
     *  report_rejected_manager→ Manager tolak Report/Hasil PIC
     *  closed                 → HSE final approve → selesai
     */
    public function up(): void
    {
        Schema::table('reports', function (Blueprint $table) {
            // Sub-status detail alur workflow
            $table->string('sub_status')->default('pending_hse')->after('status');

            // Catatan dari HSE saat menolak report final (tampil di PIC)
            $table->text('hse_note')->nullable()->after('sub_status');

            // Catatan tindak lanjut dari HSE saat setujui laporan (dikirim ke PIC)
            $table->text('hse_tindak_lanjut')->nullable()->after('hse_note');
        });
    }

    public function down(): void
    {
        Schema::table('reports', function (Blueprint $table) {
            $table->dropColumn(['sub_status', 'hse_note', 'hse_tindak_lanjut']);
        });
    }
};
