<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('promo_codes', function (Blueprint $table) {
            $table->string('description')->nullable()->after('code');
            $table->decimal('min_order', 8, 2)->nullable()->after('addon_id');
            $table->decimal('max_discount', 8, 2)->nullable()->after('min_order');
            $table->dateTime('starts_at')->nullable()->after('max_uses');
        });
    }

    public function down(): void
    {
        Schema::table('promo_codes', function (Blueprint $table) {
            $table->dropColumn(['description', 'min_order', 'max_discount', 'starts_at']);
        });
    }
};
