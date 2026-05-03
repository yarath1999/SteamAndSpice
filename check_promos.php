<?php
require 'vendor/autoload.php';
require 'bootstrap/app.php';
$app = app();
$h = \App\Models\HomepageSection::first();
echo "promo1_image: " . ($h->promo1_image ?? 'NULL') . "\n";
echo "promo2_image: " . ($h->promo2_image ?? 'NULL') . "\n";
echo "promo_cards: " . json_encode($h->promo_cards, JSON_PRETTY_PRINT) . "\n";
