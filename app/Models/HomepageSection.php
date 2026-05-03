<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HomepageSection extends Model
{
    use HasFactory;

    protected $fillable = [
        'hero_title',
        'hero_subtitle',
        'hero_tagline',
        'hero_image',
        'contact_phone',
        'intro_title',
        'intro_text',
        'intro_image',
        // Promo cards
        'promo1_title',
        'promo1_description',
        'promo1_image',
        'promo1_link',
        'promo2_title',
        'promo2_description',
        'promo2_image',
        'promo2_link',
        // Gallery & Food cards
        'gallery_title',
        'food_card1_image',
        'food_card2_image',
        'food_card3_image',
        'gallery_cards',
        'promo_cards',
        // CTA
        'cta_button_label',
    ];

    protected $casts = [
        'promo_cards' => 'array',
        'gallery_cards' => 'array',
    ];
}
