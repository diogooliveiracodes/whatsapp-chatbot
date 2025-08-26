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
        Schema::table('unit_service_types', function (Blueprint $table) {
            $table->boolean('monday')->default(true)->after('active');
            $table->boolean('tuesday')->default(true)->after('monday');
            $table->boolean('wednesday')->default(true)->after('tuesday');
            $table->boolean('thursday')->default(true)->after('wednesday');
            $table->boolean('friday')->default(true)->after('thursday');
            $table->boolean('saturday')->default(true)->after('friday');
            $table->boolean('sunday')->default(true)->after('saturday');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('unit_service_types', function (Blueprint $table) {
            $table->dropColumn([
                'monday',
                'tuesday',
                'wednesday',
                'thursday',
                'friday',
                'saturday',
                'sunday'
            ]);
        });
    }
};
