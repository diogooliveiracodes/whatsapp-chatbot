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
            $table->time('working_hour_start')->nullable();
            $table->time('working_hour_end')->nullable();
            $table->boolean('sunday')->default(false);
            $table->boolean('monday')->default(true);
            $table->boolean('tuesday')->default(false);
            $table->boolean('wednesday')->default(false);
            $table->boolean('thursday')->default(false);
            $table->boolean('friday')->default(true);
            $table->boolean('saturday')->default(false);
            $table->boolean('use_ai_chatbot')->default(false);
            $table->string('default_language', 5)->nullable();
            $table->string('timezone')->nullable();
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
