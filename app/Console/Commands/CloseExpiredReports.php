<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class CloseExpiredReports extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'reports:close-expired';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Menutup laporan yang sudah melewati batas waktu (due date) 2x24 jam secara otomatis.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $expiredReports = \App\Models\Report::where('sub_status', \App\Models\Report::SUB_PIC_WORKING)
            ->where('due_date', '<', now())
            ->get();

        foreach ($expiredReports as $report) {
            $report->update([
                'status'     => 'closed',
                'sub_status' => \App\Models\Report::SUB_CLOSED,
            ]);

            \App\Models\ReportLog::create([
                'report_id'   => $report->id,
                'user_id'     => 1, // System
                'status_from' => \App\Models\Report::SUB_PIC_WORKING,
                'status_to'   => \App\Models\Report::SUB_CLOSED,
                'message'     => 'Sistem secara otomatis menutup laporan karena melewati batas waktu pengerjaan (2x24 jam).',
            ]);

            $this->info("Report {$report->report_number} closed automatically.");
        }

        $this->info('Check expired reports completed.');
    }
}
