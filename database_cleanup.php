<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

DB::statement('SET FOREIGN_KEY_CHECKS=0;');
DB::table('report_logs')->truncate();
DB::table('report_actions')->truncate();
DB::table('report_plans')->truncate();
DB::table('report_attachments')->truncate();
DB::table('report_accident_details')->truncate();
DB::table('report_unsafe_details')->truncate();
DB::table('reports')->truncate();
DB::statement('SET FOREIGN_KEY_CHECKS=1;');

echo "Berhasil menghapus semua data laporan.\n";
