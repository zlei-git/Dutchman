<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('product_variants', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained('products')->onDelete('cascade');
            $table->string('size'); // e.g. '500g', '1kg', '2kg', 'S', 'M', 'L'
            $table->string('color')->nullable(); // e.g. 'Original', 'Salmon', 'Navy'
            $table->string('color_hex')->nullable(); // e.g. '#111111', '#FF6B35'
            $table->integer('stock')->default(10);
            $table->string('sku')->unique()->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('product_variants');
    }
};
