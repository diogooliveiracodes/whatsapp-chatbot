<?php

use App\Enum\AsaasCustomerTypeEnum;
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
        Schema::create('asaas_customers', function (Blueprint $table) {
            $table->id();
            $table->enum('type', AsaasCustomerTypeEnum::getValues());
            $table->foreignId('company_id')
                ->nullable()
                ->constrained('companies');
            $table->foreignId('customer_id')
                ->nullable()
                ->constrained('customers');
            $table->string('asaas_customer_id')->nullable();
            $table->string('name')->nullable();
            $table->string('cpf_cnpj')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('asaas_customers');
    }
};
