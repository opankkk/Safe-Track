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
        Schema::table('report_accident_details', function (Blueprint $table) {
            $table->string('no_handphone')->nullable()->after('jenis_insiden');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('report_accident_details', function (Blueprint $table) {
            $table->dropColumn('no_handphone');
        });
    }
};
