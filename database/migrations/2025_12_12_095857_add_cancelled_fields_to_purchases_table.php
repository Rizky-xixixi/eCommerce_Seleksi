<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('purchases', function (Blueprint $table) {
            // Tambah kolom cancelled_at jika belum ada
            if (!Schema::hasColumn('purchases', 'cancelled_at')) {
                $table->timestamp('cancelled_at')->nullable()->after('purchase_date');
            }
            
            // Tambah kolom cancelled_by jika belum ada
            if (!Schema::hasColumn('purchases', 'cancelled_by')) {
                $table->unsignedBigInteger('cancelled_by')->nullable()->after('cancelled_at');
                $table->foreign('cancelled_by')->references('id')->on('admins')->onDelete('set null');
            }
        });
    }

    public function down()
    {
        Schema::table('purchases', function (Blueprint $table) {
            // Drop foreign key dan kolom
            if (Schema::hasColumn('purchases', 'cancelled_by')) {
                $table->dropForeign(['cancelled_by']);
                $table->dropColumn('cancelled_by');
            }
            
            if (Schema::hasColumn('purchases', 'cancelled_at')) {
                $table->dropColumn('cancelled_at');
            }
        });
    }
};