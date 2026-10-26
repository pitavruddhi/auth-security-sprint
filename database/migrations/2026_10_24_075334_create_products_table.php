<?php

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
        Schema::create('products', function (Blueprint $table) {
            $table->id();

            $table->foreignId('category_id')
                  ->constrained('categories')
                  ->restrictOnDelete();
            
            $table->string('title');
            $table->string('slug')->unique();
            $table->string('sku',100)->unique();
            $table->decimal('base_price', 12,2);
            $table->unsignedInteger('stock_quantity')->default(0);

            $table->text('description')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index('category_id');
            $table->index('base_price');
        });
    }
 
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
