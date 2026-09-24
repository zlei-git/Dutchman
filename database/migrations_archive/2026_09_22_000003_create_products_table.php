<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained('categories')->onDelete('cascade');
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->decimal('price', 12, 2);
            $table->decimal('discount_price', 12, 2)->nullable();
            $table->string('brand')->default('PAWMART');
            $table->string('pet_type')->default('all'); // 'dog', 'cat', 'all'
            $table->text('ingredients')->nullable();
            $table->string('weight_info')->nullable();
            $table->text('shipping_info')->nullable();
            $table->string('badge')->nullable(); // 'NEW', 'SALE', 'BEST SELLER'
            $table->decimal('rating', 2, 1)->default(4.8);
            $table->integer('review_count')->default(24);
            $table->boolean('is_featured')->default(false);
            $table->boolean('is_new')->default(false);
            $table->boolean('status')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
