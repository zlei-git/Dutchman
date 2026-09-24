<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Inventory & Inventory Transactions
        Schema::create('inventory', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_variant_id')->constrained('product_variants')->onDelete('cascade');
            $table->integer('quantity')->default(0);
            $table->integer('reorder_threshold')->default(5);
            $table->timestamps();
        });

        Schema::create('inventory_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_variant_id')->constrained('product_variants')->onDelete('cascade');
            $table->string('type'); // 'IN', 'OUT', 'ADJUSTMENT'
            $table->integer('quantity');
            $table->string('reference')->nullable(); // e.g. "Order #PWO-1001"
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        // 2. Promotion Products (Pivot)
        Schema::create('promotion_products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('promotion_id')->constrained('promotions')->onDelete('cascade');
            $table->foreignId('product_id')->constrained('products')->onDelete('cascade');
            $table->timestamps();
        });

        // 3. Payment Gateway Transactions (Midtrans Snap & Webhook)
        Schema::create('payment_gateway_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained('orders')->onDelete('cascade');
            $table->string('transaction_id')->nullable();
            $table->string('payment_type')->nullable(); // 'qris', 'bank_transfer', 'gopay', 'shopeepay', etc.
            $table->decimal('gross_amount', 12, 2);
            $table->string('transaction_status')->default('pending'); // 'pending', 'settlement', 'deny', 'expire', 'cancel'
            $table->string('fraud_status')->nullable();
            $table->string('snap_token')->nullable();
            $table->timestamp('paid_at')->nullable();
            $table->json('raw_payload')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payment_gateway_transactions');
        Schema::dropIfExists('promotion_products');
        Schema::dropIfExists('inventory_transactions');
        Schema::dropIfExists('inventory');
    }
};
