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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->constrained('customers')->onDelete('set null');
            $table->foreignId('product_id')->contrained('products');
            $table->string('order_number')->unique();
            $table->enum('status', ['Pending', 'Processing', 'Completed', 'Declined'])->default('Pending');
            $table->integer('quantity');
            $table->decimal('unit_price', 10, 2);
            $table->longText('notes');
            $table->longText('download_data');
            $table->string('download_token')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
