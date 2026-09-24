<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Add chair_code to barbers if not exists
        if (!Schema::hasColumn('barbers', 'chair_code')) {
            Schema::table('barbers', function (Blueprint $table) {
                $table->string('chair_code', 10)->default('A1')->after('name');
            });
        }

        // 2. Add chair_code to bookings if not exists
        if (!Schema::hasColumn('bookings', 'chair_code')) {
            Schema::table('bookings', function (Blueprint $table) {
                $table->string('chair_code', 10)->nullable()->after('barber_id');
            });
        }

        // 3. Addons Table (Drink & Grooming options)
        if (!Schema::hasTable('addons')) {
            Schema::create('addons', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('category', 50)->default('drink'); // drink, grooming
                $table->decimal('price', 10, 2)->default(0);
                $table->text('description')->nullable();
                $table->string('photo')->nullable();
                $table->boolean('is_active')->default(true);
                $table->integer('sort_order')->default(0);
                $table->timestamps();
            });
        }

        // 4. Booking Addons Table (Snapshot relation)
        if (!Schema::hasTable('booking_addons')) {
            Schema::create('booking_addons', function (Blueprint $table) {
                $table->id();
                $table->foreignId('booking_id')->constrained('bookings')->cascadeOnDelete();
                $table->foreignId('addon_id')->nullable()->constrained('addons')->nullOnDelete();
                $table->string('name');
                $table->decimal('price', 10, 2);
                $table->integer('quantity')->default(1);
                $table->timestamps();
            });
        }

        // 5. Payments Table (Midtrans Snap & Transactions)
        if (!Schema::hasTable('payments')) {
            Schema::create('payments', function (Blueprint $table) {
                $table->id();
                $table->foreignId('booking_id')->constrained('bookings')->cascadeOnDelete();
                $table->string('order_id')->unique();
                $table->string('transaction_id')->nullable();
                $table->string('payment_method')->nullable();
                $table->decimal('amount', 10, 2);
                $table->string('status', 30)->default('pending'); // pending, paid, failed, expired, cancelled
                $table->timestamp('paid_at')->nullable();
                $table->timestamp('expiry_time')->nullable();
                $table->longText('raw_response')->nullable();
                $table->timestamps();

                $table->index(['booking_id', 'status']);
                $table->index('order_id');
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('payments');
        Schema::dropIfExists('booking_addons');
        Schema::dropIfExists('addons');

        if (Schema::hasColumn('bookings', 'chair_code')) {
            Schema::table('bookings', function (Blueprint $table) {
                $table->dropColumn('chair_code');
            });
        }

        if (Schema::hasColumn('barbers', 'chair_code')) {
            Schema::table('barbers', function (Blueprint $table) {
                $table->dropColumn('chair_code');
            });
        }
    }
};
