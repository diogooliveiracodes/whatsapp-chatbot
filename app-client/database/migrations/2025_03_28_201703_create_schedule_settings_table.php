<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('schedule_settings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('unit_id')->constrained()->onDelete('cascade');
            $table->time('working_hours_start');
            $table->time('working_hours_end');
            $table->integer('slot_duration')->default(30); // em minutos
            $table->time('break_start')->nullable();
            $table->time('break_end')->nullable();
            $table->integer('max_appointments_per_day')->default(20);
            $table->integer('min_notice_hours')->default(24);
            $table->integer('max_advance_days')->default(30);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('schedule_settings');
    }
};
