<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('company_profiles', function (Blueprint $table) {
            $table->string('leader_name')->nullable()->after('history');
            $table->string('leader_position')->nullable()->after('leader_name');
            $table->string('leader_term')->nullable()->after('leader_position');
            $table->string('leader_photo_path')->nullable()->after('leader_term');
        });
    }

    public function down(): void
    {
        Schema::table('company_profiles', function (Blueprint $table) {
            $table->dropColumn(['leader_name', 'leader_position', 'leader_term', 'leader_photo_path']);
        });
    }
};
