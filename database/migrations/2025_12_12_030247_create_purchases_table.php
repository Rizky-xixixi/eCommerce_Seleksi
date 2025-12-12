<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('purchases', function (Blueprint $table) {
            $table->id();
            $table->string('invoice_number')->unique();
            $table->foreignId('admin_id')->constrained('admins');
            $table->string('customer_name')->nullable();
            $table->string('customer_email')->nullable();
            $table->decimal('total_amount', 10, 2);
            $table->enum('status', ['pending', 'completed', 'cancelled'])->default('completed');
            $table->timestamp('purchase_date')->useCurrent();
            $table->timestamp('cancelled_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('purchases');
    }
};