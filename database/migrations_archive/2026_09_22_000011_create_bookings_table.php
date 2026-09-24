<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('grooming_services', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->string('pet_type')->default('all'); // 'dog', 'cat', 'all'
            $table->integer('duration_minutes')->default(60);
            $table->decimal('price', 12, 2);
            $table->string('badge')->nullable(); // 'POPULAR', 'RECOMMENDED', 'PREMIUM'
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('grooming_slots', function (Blueprint $table) {
            $table->id();
            $table->foreignId('branch_id')->constrained('branches')->onDelete('cascade');
            $table->string('time_slot'); // e.g. "09:00", "10:00", "11:00", "13:00", "14:00", "15:00", "16:00", "17:00"
            $table->integer('capacity')->default(2);
            $table->boolean('is_available')->default(true);
            $table->timestamps();
        });

        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->string('booking_number')->unique();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('branch_id')->constrained('branches')->onDelete('cascade');
            $table->foreignId('grooming_service_id')->constrained('grooming_services')->onDelete('cascade');
            $table->string('pet_name');
            $table->string('pet_type')->default('Cat'); // 'Cat', 'Dog', 'Other'
            $table->string('pet_breed')->nullable();
            $table->date('booking_date');
            $table->string('booking_time'); // e.g. "13:00"
            $table->string('customer_name');
            $table->string('phone');
            $table->text('notes')->nullable();
            $table->string('status')->default('PENDING'); // 'PENDING', 'CONFIRMED', 'ARRIVED', 'IN_SERVICE', 'COMPLETED', 'CANCELLED', 'NO_SHOW'
            $table->text('admin_notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bookings');
        Schema::dropIfExists('grooming_slots');
        Schema::dropIfExists('grooming_services');
    }
};
