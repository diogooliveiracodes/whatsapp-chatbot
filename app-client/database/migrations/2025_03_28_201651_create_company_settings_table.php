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
        Schema::create('company_settings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained('companies');
            $table->string('name');
            $table->string('identification')->nullable();
            $table->string('phone')->nullable();
            $table->string('whatsapp_webhook_url')->nullable();
            $table->string('whatsapp_number')->nullable();
            $table->string('default_language', 5)->nullable();
            $table->string('timezone')->nullable();
            $table->time('working_hour_start')->nullable();
            $table->time('working_hour_end')->nullable();
            $table->enum('working_day_start', [1, 2, 3, 4, 5, 6, 7])->nullable();
            $table->enum('working_day_end', [1, 2, 3, 4, 5, 6, 7])->nullable();
            $table->boolean('use_ai_chatbot')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('company_settings');
    }
};
