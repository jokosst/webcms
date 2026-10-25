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
        Schema::table('lab_bookings', function (Blueprint $table) {
            $table->string('booking_group_code')->nullable()->index()->after('booking_code');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('lab_bookings', function (Blueprint $table) {
            $table->dropIndex(['booking_group_code']);
            $table->dropColumn('booking_group_code');
        });
    }
};
