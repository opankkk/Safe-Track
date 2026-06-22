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
            $table->string('nip')->nullable()->after('no_handphone');
            $table->string('no_telepon')->nullable()->after('nip');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('report_accident_details', function (Blueprint $table) {
            $table->dropColumn(['nip', 'no_telepon']);
        });
    }
};
