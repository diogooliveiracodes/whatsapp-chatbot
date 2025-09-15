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
        Schema::create('unit_service_types', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained()->onDelete('cascade');
            $table->foreignId('unit_id')->constrained()->onDelete('cascade');
            $table->string('name', 100);
            $table->string('description', 255)->nullable();
            $table->decimal('price', 10, 2);
            $table->boolean('active')->default(true);
            $table->boolean('monday')->default(true);
            $table->boolean('tuesday')->default(true);
            $table->boolean('wednesday')->default(true);
            $table->boolean('thursday')->default(true);
            $table->boolean('friday')->default(true);
            $table->boolean('saturday')->default(true);
            $table->boolean('sunday')->default(true);
            $table->string('image_name')->nullable();
            $table->string('image_path')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('unit_service_types');
    }
};
