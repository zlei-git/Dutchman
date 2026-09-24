<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Services
        Schema::create('services', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->decimal('price', 10, 2);
            $table->integer('duration_minutes')->default(45);
            $table->string('badge')->nullable(); // e.g. SIGNATURE, POPULAR
            $table->string('photo')->nullable();
            $table->boolean('is_active')->default(true);
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });

        // 2. Barbers
        Schema::create('barbers', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('specialty'); // e.g. "Fade & Texture", "Classic & Styling"
            $table->integer('experience_years')->default(5);
            $table->text('bio')->nullable();
            $table->string('photo')->nullable();
            $table->boolean('is_active')->default(true);
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });

        // 3. Bookings
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->string('booking_number')->unique(); // e.g. DTC-XXXXXX
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('barber_id')->nullable()->constrained('barbers')->nullOnDelete();
            $table->date('booking_date');
            $table->string('booking_time', 10); // e.g. 10:00, 14:30
            $table->integer('duration_minutes')->default(45);
            $table->decimal('total_price', 10, 2)->default(0);
            $table->string('status', 20)->default('confirmed'); // pending, confirmed, completed, cancelled
            $table->string('customer_name');
            $table->string('customer_phone');
            $table->string('customer_email')->nullable();
            $table->text('notes')->nullable();
            $table->text('admin_notes')->nullable();
            $table->timestamps();

            $table->index(['booking_date', 'booking_time']);
            $table->index(['barber_id', 'booking_date']);
        });

        // 4. Booking Items (Historical snapshot)
        Schema::create('booking_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('booking_id')->constrained('bookings')->cascadeOnDelete();
            $table->foreignId('service_id')->constrained('services')->cascadeOnDelete();
            $table->decimal('price', 10, 2); // Snapshot price
            $table->integer('duration'); // Snapshot duration in minutes
            $table->timestamps();
        });

        // 5. Promotions
        Schema::create('promotions', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->integer('discount_percent')->default(0);
            $table->decimal('discount_amount', 10, 2)->default(0);
            $table->date('valid_from')->nullable();
            $table->date('valid_until')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // 6. Reviews
        Schema::create('reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('booking_id')->nullable()->constrained('bookings')->nullOnDelete();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('barber_id')->constrained('barbers')->cascadeOnDelete();
            $table->integer('rating')->default(5);
            $table->text('comment')->nullable();
            $table->boolean('is_approved')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reviews');
        Schema::dropIfExists('promotions');
        Schema::dropIfExists('booking_items');
        Schema::dropIfExists('bookings');
        Schema::dropIfExists('barbers');
        Schema::dropIfExists('services');
    }
};
