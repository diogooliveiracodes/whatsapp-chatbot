<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('unit_settings', function (Blueprint $table) {
            $table->string('whatsapp_api_base_url')->nullable()->after('whatsapp_number');
            $table->string('whatsapp_api_token')->nullable()->after('whatsapp_api_base_url');
            $table->string('whatsapp_webhook_secret')->nullable()->after('whatsapp_webhook_url');
        });
    }

    public function down(): void
    {
        Schema::table('unit_settings', function (Blueprint $table) {
            $table->dropColumn('whatsapp_api_base_url');
            $table->dropColumn('whatsapp_api_token');
            $table->dropColumn('whatsapp_webhook_secret');
        });
    }
};


