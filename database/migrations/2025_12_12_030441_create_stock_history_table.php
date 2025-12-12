<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('stock_history', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained();
            $table->enum('change_type', ['restock', 'purchase', 'cancel', 'manual_adjust']);
            $table->integer('previous_quantity');
            $table->integer('new_quantity');
            $table->integer('change_amount');
            $table->text('notes')->nullable();
            $table->foreignId('admin_id')->constrained('admins');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('stock_history');
    }
};