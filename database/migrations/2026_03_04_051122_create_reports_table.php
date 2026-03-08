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
        Schema::create('reports', function (Blueprint $table) {
            $table->id();
            $table->string('report_number')->unique();
            $table->enum('type', ['accident', 'unsafe_action', 'unsafe_condition']);
            $table->string('reporter_name');
            $table->string('superior_email');
            $table->enum('status', [
                'pending', 'open', 'waiting_plan', 'planning', 
                'action_in_progress', 'manager_review', 'hse_final_check', 'closed'
            ])->default('pending');
            $table->foreignId('pic_id')->nullable()->constrained('users');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reports');
    }
};
