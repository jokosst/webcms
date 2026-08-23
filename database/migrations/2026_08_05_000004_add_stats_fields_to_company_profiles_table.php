<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('company_profiles', function (Blueprint $table) {
            $table->string('stat_population')->nullable()->after('history');
            $table->string('stat_area')->nullable()->after('stat_population');
            $table->string('stat_subdistricts')->nullable()->after('stat_area');
            $table->string('stat_umkm')->nullable()->after('stat_subdistricts');
        });
    }

    public function down(): void
    {
        Schema::table('company_profiles', function (Blueprint $table) {
            $table->dropColumn(['stat_population', 'stat_area', 'stat_subdistricts', 'stat_umkm']);
        });
    }
};
