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
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained('companies');
            $table->foreignId('schedule_id')
                ->nullable()
                ->constrained('schedules');
            $table->foreignId('plan_id')
                ->nullable()
                ->constrained('plans');
            $table->foreignId('customer_id')
                ->nullable()
                ->constrained('customers');
            $table->foreignId('user_id')
                ->nullable()
                ->constrained('users');
            $table->enum('payment_method', PaymentMethodEnum::getValues());
            $table->enum('gateway', PaymentGatewayEnum::getValues());
            $table->enum('service', PaymentServiceEnum::getValues());
            $table->enum('status', PaymentStatusEnum::getValues());
            $table->decimal('amount', 10, 2);
            $table->text('pix_copy_paste')->nullable();
            $table->string('credit_card_payment_link')->nullable();
            $table->string('payment_receipt_path')->nullable();
            $table->dateTime('paid_at')->nullable();
            $table->dateTime('expires_at')->nullable();
            $table->string('gateway_payment_id')->nullable();
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
