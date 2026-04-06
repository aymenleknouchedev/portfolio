<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('addons', function (Blueprint $table) {
            $table->json('license_tiers')->nullable()->after('license_price');
        });

        Schema::table('purchases', function (Blueprint $table) {
            $table->string('license_tier')->nullable()->after('quantity');
        });
    }

    public function down(): void
    {
        Schema::table('addons', function (Blueprint $table) {
            $table->dropColumn('license_tiers');
        });

        Schema::table('purchases', function (Blueprint $table) {
            $table->dropColumn('license_tier');
        });
    }
};
