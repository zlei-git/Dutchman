<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained('orders')->onDelete('cascade');
            $table->string('payment_reference')->unique();
            $table->string('method'); // 'Bank Transfer', 'QRIS Demo', 'E-Wallet Demo', 'Cash on Delivery'
            $table->decimal('amount', 12, 2);
            $table->string('status')->default('PENDING'); // 'PENDING', 'PAID', 'FAILED'
            $table->text('details')->nullable(); // simulated payment payload
            $table->timestamp('paid_at')->nullable();
            $table->timestamps();
        });

        Schema::create('audit_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('action'); // e.g. 'UPDATE_ORDER_STATUS', 'APPROVE_BOOKING'
            $table->string('module'); // 'Order', 'Booking', 'Product'
            $table->unsignedBigInteger('record_id')->nullable();
            $table->json('old_data')->nullable();
            $table->json('new_data')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('audit_logs');
        Schema::dropIfExists('payments');
    }
};
