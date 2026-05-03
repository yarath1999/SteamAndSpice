<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('homepage_sections', function (Blueprint $table) {
            // Promo cards (2 sections)
            $table->string('promo1_title')->nullable()->after('hero_image');
            $table->text('promo1_description')->nullable()->after('promo1_title');
            $table->string('promo1_image')->nullable()->after('promo1_description');
            $table->string('promo1_link')->nullable()->after('promo1_image');

            $table->string('promo2_title')->nullable()->after('promo1_link');
            $table->text('promo2_description')->nullable()->after('promo2_title');
            $table->string('promo2_image')->nullable()->after('promo2_description');
            $table->string('promo2_link')->nullable()->after('promo2_image');

            // Gallery section
            $table->string('gallery_title')->nullable()->after('promo2_link');
            $table->string('food_card1_image')->nullable()->after('gallery_title');
            $table->string('food_card2_image')->nullable()->after('food_card1_image');
            $table->string('food_card3_image')->nullable()->after('food_card2_image');

            // CTA section
            $table->string('cta_button_label')->default('Order Online')->after('food_card3_image');
        });
    }

    public function down(): void
    {
        Schema::table('homepage_sections', function (Blueprint $table) {
            $table->dropColumn([
                'promo1_title',
                'promo1_description',
                'promo1_image',
                'promo1_link',
                'promo2_title',
                'promo2_description',
                'promo2_image',
                'promo2_link',
                'gallery_title',
                'food_card1_image',
                'food_card2_image',
                'food_card3_image',
                'cta_button_label',
            ]);
        });
    }
};
