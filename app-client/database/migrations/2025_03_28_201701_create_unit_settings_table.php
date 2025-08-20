<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('unit_settings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained('companies');
            $table->foreignId('unit_id')->constrained('units')->onDelete('cascade');
            $table->string('name');
            $table->string('phone')->nullable();
            $table->string('street')->nullable();
            $table->string('number')->nullable();
            $table->string('complement')->nullable();
            $table->string('neighborhood')->nullable();
            $table->string('city')->nullable();
            $table->string('state')->nullable();
            $table->string('zipcode')->nullable();
            $table->string('whatsapp_webhook_url')->nullable();
            $table->string('whatsapp_number')->nullable();
            $table->unsignedInteger('appointment_duration_minutes')->default(30);
            $table->time('sunday_start')->nullable();
            $table->time('sunday_end')->nullable();
            $table->boolean('sunday')->default(false);
            $table->boolean('sunday_has_break')->default(false);
            $table->time('sunday_break_start')->nullable();
            $table->time('sunday_break_end')->nullable();
            $table->time('monday_start')->nullable();
            $table->time('monday_end')->nullable();
            $table->boolean('monday')->default(false);
            $table->boolean('monday_has_break')->default(false);
            $table->time('monday_break_start')->nullable();
            $table->time('monday_break_end')->nullable();
            $table->time('tuesday_start')->nullable();
            $table->time('tuesday_end')->nullable();
            $table->boolean('tuesday')->default(false);
            $table->boolean('tuesday_has_break')->default(false);
            $table->time('tuesday_break_start')->nullable();
            $table->time('tuesday_break_end')->nullable();
            $table->time('wednesday_start')->nullable();
            $table->time('wednesday_end')->nullable();
            $table->boolean('wednesday')->default(false);
            $table->boolean('wednesday_has_break')->default(false);
            $table->time('wednesday_break_start')->nullable();
            $table->time('wednesday_break_end')->nullable();
            $table->time('thursday_start')->nullable();
            $table->time('thursday_end')->nullable();
            $table->boolean('thursday')->default(false);
            $table->boolean('thursday_has_break')->default(false);
            $table->time('thursday_break_start')->nullable();
            $table->time('thursday_break_end')->nullable();
            $table->time('friday_start')->nullable();
            $table->time('friday_end')->nullable();
            $table->boolean('friday')->default(false);
            $table->boolean('friday_has_break')->default(false);
            $table->time('friday_break_start')->nullable();
            $table->time('friday_break_end')->nullable();
            $table->time('saturday_start')->nullable();
            $table->time('saturday_end')->nullable();
            $table->boolean('saturday')->default(false);
            $table->boolean('saturday_has_break')->default(false);
            $table->time('saturday_break_start')->nullable();
            $table->time('saturday_break_end')->nullable();
            $table->boolean('use_ai_chatbot')->default(false);
            $table->string('default_language', 5)->nullable();
            $table->string('timezone')->nullable();
            $table->boolean('active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('unit_settings');
    }
};
