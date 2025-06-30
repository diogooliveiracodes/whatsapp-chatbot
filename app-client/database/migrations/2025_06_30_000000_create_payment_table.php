<?php

use App\Enum\PaymentGatewayEnum;
use App\Enum\PaymentMethodEnum;
use App\Enum\PaymentServiceEnum;
use App\Enum\PaymentStatusEnum;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('payment', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained('companies');
            $table->foreignId('schedule_id')->constrained('schedules')->nullable();
            $table->foreignId('signature_id')->constrained('signatures')->nullable();
            $table->foreignId('plan_id')->constrained('plans');
            $table->foreignId('customer_id')->constrained('customers')->nullable();
            $table->foreignId('user_id')->constrained('users')->nullable();
            $table->enum('payment_method', PaymentMethodEnum::getValues());
            $table->enum('gateway', PaymentGatewayEnum::getValues());
            $table->enum('service', PaymentServiceEnum::getValues());
            $table->enum('status', PaymentStatusEnum::getValues());
            $table->decimal('amount', 10, 2);
            $table->dateTime('paid_at')->nullable();
            $table->dateTime('expires_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payment');
    }
};
