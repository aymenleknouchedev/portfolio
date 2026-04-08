<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('purchases', function (Blueprint $table) {
            $table->string('promo_code')->nullable()->after('license_tier');
            $table->decimal('promo_discount', 8, 2)->nullable()->after('promo_code');
        });
    }

    public function down(): void
    {
        Schema::table('purchases', function (Blueprint $table) {
            $table->dropColumn(['promo_code', 'promo_discount']);
        });
    }
};
