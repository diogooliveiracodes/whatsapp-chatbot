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
            $table->string('whatsapp_verify_token')->nullable();
            $table->string('default_language')->nullable();
            $table->string('timezone')->nullable();
            $table->boolean('use_ai_chatbot')->default(false);
            $table->boolean('active')->default(true);

            // Payment gateway fields
            $table->integer('payment_gateway')->nullable();
            $table->string('gateway_api_key')->nullable();

            // PIX fields
            $table->string('pix_key')->nullable();
            $table->integer('pix_key_type')->nullable();

            // Bank account fields
            $table->string('bank_code', 10)->nullable();
            $table->string('bank_agency', 20)->nullable();
            $table->string('bank_account', 20)->nullable();
            $table->string('bank_account_digit', 5)->nullable();
            $table->integer('bank_account_type')->nullable();
            $table->string('account_holder_name', 255)->nullable();
            $table->string('account_holder_document', 20)->nullable();

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
