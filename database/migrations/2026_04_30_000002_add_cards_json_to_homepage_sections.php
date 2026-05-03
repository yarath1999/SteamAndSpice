<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class() extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('homepage_sections')) {
            Schema::table('homepage_sections', function (Blueprint $table) {
                if (! Schema::hasColumn('homepage_sections', 'promo_cards')) {
                    $table->json('promo_cards')->nullable()->after('promo2_link');
                }

                if (! Schema::hasColumn('homepage_sections', 'gallery_cards')) {
                    $table->json('gallery_cards')->nullable()->after('food_card3_image');
                }
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('homepage_sections')) {
            Schema::table('homepage_sections', function (Blueprint $table) {
                if (Schema::hasColumn('homepage_sections', 'promo_cards')) {
                    $table->dropColumn('promo_cards');
                }

                if (Schema::hasColumn('homepage_sections', 'gallery_cards')) {
                    $table->dropColumn('gallery_cards');
                }
            });
        }
    }
};
