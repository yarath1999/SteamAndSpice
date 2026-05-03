<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Helpers\ImageHelper;
use App\Models\HomepageSection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

class HomepageContentController extends Controller
{
    private function homepageDefaults(): array
    {
        return [
            'hero_title' => 'Steam & Spice',
            'hero_subtitle' => 'Nepali Fusion Kitchen',
            'hero_tagline' => 'Every dish full of life',
            'contact_phone' => '+44 20 1234 5678',
            'intro_title' => 'A Warm Welcome',
            'intro_text' => 'We serve handcrafted dishes inspired by street flavors and elevated cuisine.',
            'promo1_title' => 'Branches in London',
            'promo1_description' => 'Find us in top locations across the city.',
            'promo1_link' => '/contact',
            'promo2_title' => 'Try our Drinks',
            'promo2_description' => 'Refreshing iced teas and beverages.',
            'promo2_link' => '/menu',
            'gallery_title' => 'Explore Our Food',
            'cta_button_label' => 'Order Online',
        ];
    }

    private function getOrCreateHomepage(): HomepageSection
    {
        return HomepageSection::query()->latest()->first()
            ?: HomepageSection::query()->create($this->homepageDefaults());
    }

    public function edit()
    {
        if (!Schema::hasTable('homepage_sections')) {
            return redirect()->route('admin.dashboard')->with('error', 'Homepage content table is unavailable.');
        }

        $homepage = $this->getOrCreateHomepage();

        // Merge legacy promo cards with new array format for unified editor
        $homepage = $this->mergePromoCardsForEdit($homepage);
        // Merge legacy gallery food_card fields with gallery_cards for unified editor
        $homepage = $this->mergeGalleryForEdit($homepage);

        return view('admin.homepage.edit', compact('homepage'));
    }

    /**
     * Merge legacy food_card1/2/3 fields with gallery_cards array
     * Produces unified_gallery_cards on the $homepage instance for view rendering
     */
    private function mergeGalleryForEdit($homepage)
    {
        $unified = [];

        // Prefer the unified gallery array when it exists so legacy cards are not duplicated.
        if (!empty($homepage->gallery_cards) && is_array($homepage->gallery_cards)) {
            foreach ($homepage->gallery_cards as $i => $card) {
                $unified[] = array_merge($card, [
                    'id' => 'card_' . $i,
                    'is_legacy' => false,
                ]);
            }
        } else {
            // Fall back to legacy food card images only when the array has no data yet.
            if (!empty($homepage->food_card1_image)) {
                $unified[] = [
                    'id' => 'legacy_food_1',
                    'title' => null,
                    'link' => null,
                    'image' => $homepage->food_card1_image,
                    'redirect_to_menu' => true,
                    'is_legacy' => true,
                ];
            }

            if (!empty($homepage->food_card2_image)) {
                $unified[] = [
                    'id' => 'legacy_food_2',
                    'title' => null,
                    'link' => null,
                    'image' => $homepage->food_card2_image,
                    'redirect_to_menu' => true,
                    'is_legacy' => true,
                ];
            }

            if (!empty($homepage->food_card3_image)) {
                $unified[] = [
                    'id' => 'legacy_food_3',
                    'title' => null,
                    'link' => null,
                    'image' => $homepage->food_card3_image,
                    'redirect_to_menu' => true,
                    'is_legacy' => true,
                ];
            }
        }

        // Store unified array temporarily for view
        $homepage->unified_gallery_cards = $unified;

        return $homepage;
    }

    /**
     * Merge legacy promo1/promo2 fields with promo_cards array
     * Creates unified array with is_legacy flag for frontend
     */
    private function mergePromoCardsForEdit($homepage)
    {
        $unified = [];

        // Legacy card 1
        if (!empty($homepage->promo1_title)) {
            $unified[] = [
                'id' => 'legacy_1',
                'title' => $homepage->promo1_title,
                'description' => $homepage->promo1_description,
                'image' => $homepage->promo1_image,
                'link' => $homepage->promo1_link,
                'is_legacy' => true,
            ];
        }

        // Legacy card 2
        if (!empty($homepage->promo2_title)) {
            $unified[] = [
                'id' => 'legacy_2',
                'title' => $homepage->promo2_title,
                'description' => $homepage->promo2_description,
                'image' => $homepage->promo2_image,
                'link' => $homepage->promo2_link,
                'is_legacy' => true,
            ];
        }

        // Add existing promo_cards array items (if any)
        if (!empty($homepage->promo_cards) && is_array($homepage->promo_cards)) {
            foreach ($homepage->promo_cards as $i => $card) {
                $unified[] = array_merge($card, [
                    'id' => 'card_' . $i,
                    'is_legacy' => false,
                ]);
            }
        }

        // Store unified array temporarily for view
        $homepage->unified_promo_cards = $unified;

        return $homepage;
    }

public function update(Request $request)
{
    if (!Schema::hasTable('homepage_sections')) {
        return redirect()->route('admin.dashboard')
            ->with('error', 'Homepage content table is unavailable.');
    }

    $homepage = $this->getOrCreateHomepage();

    $data = $request->validate([
        'hero_title' => ['required', 'string', 'max:255'],
        'hero_subtitle' => ['nullable', 'string', 'max:1000'],
        'hero_tagline' => ['nullable', 'string', 'max:255'],
        'contact_phone' => ['nullable', 'string', 'max:30'],
        'intro_title' => ['required', 'string', 'max:255'],
        'intro_text' => ['nullable', 'string', 'max:2000'],
        'intro_image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:10240',
        'hero_image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:10240', //10MB max
        // Unified promo cards (replaces individual promo1/promo2)
        'promo_cards' => 'nullable|array|max:15',
        'promo_cards.*.title' => ['nullable', 'string', 'max:255'],
        'promo_cards.*.description' => ['nullable', 'string', 'max:1000'],
        'promo_cards.*.link' => ['nullable', 'string', 'max:255'],
        'promo_cards.*.image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:10240',
        // Gallery
        'gallery_title' => ['required', 'string', 'max:255'],
        'food_card1_image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:10240',
        'food_card2_image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:10240',
        'food_card3_image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:10240',
        'gallery_cards' => 'nullable|array|max:15',
        'gallery_cards.*.title' => ['nullable', 'string', 'max:255'],
        'gallery_cards.*.link' => ['nullable', 'string', 'max:255'],
        'gallery_cards.*.image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:10240',
        'gallery_cards.*.redirect_to_menu' => 'nullable|boolean',
        // CTA
        'cta_button_label' => ['required', 'string', 'max:100'],
    ]);

    // Handle image uploads
    $imageFields = [
        'hero_image' => 'homepage',
        'intro_image' => 'intro',
        'promo1_image' => 'promo-cards',
        'promo2_image' => 'promo-cards',
        'food_card1_image' => 'gallery',
        'food_card2_image' => 'gallery',
        'food_card3_image' => 'gallery',
    ];

    foreach ($imageFields as $fieldName => $folderName) {
        if ($request->hasFile($fieldName)) {
            $file = $request->file($fieldName);

            if (!$file->isValid()) {
                return back()->withErrors([$fieldName => 'Invalid upload']);
            }

            // Delete old image if exists
            if (!empty($homepage->$fieldName)) {
                \Storage::disk('public')->delete($homepage->$fieldName);
            }

            // Store new image
            $path = ImageHelper::upload($file, $folderName);

            if (!$path) {
                return back()->withErrors([$fieldName => 'Storage failed']);
            }

            $data[$fieldName] = $path;
        }
    }

    // Handle intro_image removal checkbox
    if ($request->has('remove_intro_image') && !$request->hasFile('intro_image')) {
        if (!empty($homepage->intro_image)) {
            \Storage::disk('public')->delete($homepage->intro_image);
            $data['intro_image'] = null;
        }
    }

    // Process unified promo_cards array and split into legacy + new
    $submittedPromos = $request->input('promo_cards', []);
    $legacyCards = [];     // promo1/promo2
    $newPromoCards = [];   // promo_cards array

    foreach ($submittedPromos as $i => $card) {
        $title = trim($card['title'] ?? '');
        $description = trim($card['description'] ?? '');
        $link = trim($card['link'] ?? '');
        $imagePath = null;

        // Handle file upload for this card
        if ($request->hasFile("promo_cards.$i.image")) {
            $file = $request->file("promo_cards.$i.image");
            if ($file && $file->isValid()) {
                $path = ImageHelper::upload($file, 'promo-cards');
                if ($path) {
                    $imagePath = $path;
                }
            }
        } else {
            // No new file selected - preserve the current image path
            $imagePath = $request->input("promo_cards.$i.image_current") ?? null;
        }

        $cardData = [
            'title' => $title,
            'description' => $description,
            'link' => $link,
            'image' => $imagePath,
        ];

        // First two cards go to legacy fields (for backward compatibility)
        if ($i === 0) {
            $data['promo1_title'] = $title;
            $data['promo1_description'] = $description;
            $data['promo1_link'] = $link;
            if ($imagePath) {
                // Delete old legacy image if exists
                if (!empty($homepage->promo1_image)) {
                    \Storage::disk('public')->delete($homepage->promo1_image);
                }
                $data['promo1_image'] = $imagePath;
            }
            $legacyCards[0] = $cardData;
        } elseif ($i === 1) {
            $data['promo2_title'] = $title;
            $data['promo2_description'] = $description;
            $data['promo2_link'] = $link;
            if ($imagePath) {
                // Delete old legacy image if exists
                if (!empty($homepage->promo2_image)) {
                    \Storage::disk('public')->delete($homepage->promo2_image);
                }
                $data['promo2_image'] = $imagePath;
            }
            $legacyCards[1] = $cardData;
        } else {
            // Rest go to promo_cards array
            $newPromoCards[] = $cardData;
        }
    }

    // Delete any removed promo images from array
    $oldPromoImages = collect($homepage->promo_cards ?? [])->pluck('image')->filter()->all();
    $newPromoImages = collect($newPromoCards)->pluck('image')->filter()->all();
    $toDelete = array_diff($oldPromoImages, $newPromoImages);
    foreach ($toDelete as $oldPath) {
        if ($oldPath) {
            \Storage::disk('public')->delete($oldPath);
        }
    }

    $data['promo_cards'] = $newPromoCards;

    // Process gallery_cards array (new format)
    $newGalleryCards = [];
    $submittedGallery = $request->input('gallery_cards', []);
    foreach ($submittedGallery as $i => $card) {
        $title = trim($card['title'] ?? '');
        $link = trim($card['link'] ?? '');

        // Preserve current image path when no new file is uploaded
        $imagePath = $request->input("gallery_cards.$i.image_current") ?? ($card['image'] ?? null);

        if ($request->hasFile("gallery_cards.$i.image")) {
            $file = $request->file("gallery_cards.$i.image");
            if ($file && $file->isValid()) {
                $path = ImageHelper::upload($file, 'gallery');
                if ($path) {
                    $imagePath = $path;
                }
            }
        }

        // Redirect flag: default to true for backward compatibility
        $redirect = true;
        if (array_key_exists('redirect_to_menu', $card)) {
            $redirect = filter_var($card['redirect_to_menu'], FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE);
            $redirect = is_null($redirect) ? true : $redirect;
        }

        $newGalleryCards[] = [
            'title' => $title,
            'link' => $link,
            'image' => $imagePath,
            'redirect_to_menu' => $redirect,
        ];
    }

    $oldGalleryImages = collect($homepage->gallery_cards ?? [])->pluck('image')->filter()->all();
    $newGalleryImages = collect($newGalleryCards)->pluck('image')->filter()->all();
    $toDeleteG = array_diff($oldGalleryImages, $newGalleryImages);
    foreach ($toDeleteG as $oldPath) {
        if ($oldPath) {
            \Storage::disk('public')->delete($oldPath);
        }
    }

    $data['gallery_cards'] = $newGalleryCards;

    // Map first three gallery cards back to legacy food_card1/2/3 fields
    $legacyOld = [
        0 => $homepage->food_card1_image ?? null,
        1 => $homepage->food_card2_image ?? null,
        2 => $homepage->food_card3_image ?? null,
    ];

    for ($i = 0; $i < 3; $i++) {
        $newImage = $newGalleryCards[$i]['image'] ?? null;
        $field = "food_card" . ($i + 1) . "_image";

        if ($newImage) {
            // If legacy had a different image, delete it
            if (!empty($legacyOld[$i]) && $legacyOld[$i] !== $newImage) {
                \Storage::disk('public')->delete($legacyOld[$i]);
            }
            $data[$field] = $newImage;
        } else {
            // If legacy existed but is no longer referenced anywhere in new gallery images, remove it
            if (!empty($legacyOld[$i]) && !in_array($legacyOld[$i], $newGalleryImages)) {
                \Storage::disk('public')->delete($legacyOld[$i]);
                $data[$field] = null;
            }
        }
    }

    // Save all data
    $homepage->fill($data);
    $homepage->save();

    return redirect()->route('admin.homepage.edit')
        ->with('success', 'Homepage updated.');
}
}
