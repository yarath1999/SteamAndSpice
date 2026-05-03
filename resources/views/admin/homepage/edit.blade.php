@extends('layouts.admin')

@section('content')
@php
    $fallbackImage = asset('images/fallback.jpg');
    $storageImageUrl = function (?string $path) use ($fallbackImage) {
        if (!$path) {
            return $fallbackImage;
        }

        $thumbPath = dirname($path) . '/thumb_' . basename($path);

        return file_exists(public_path('storage/' . $thumbPath))
            ? asset('storage/' . $thumbPath)
            : asset('storage/' . $path);
    };
@endphp
<h1>Homepage Settings</h1>
<form class="panel" method="POST" action="{{ route('admin.homepage.update') }}" enctype="multipart/form-data">
    @csrf
    @method('PUT')

    <hr style="margin: 20px 0; border: none; border-top: 1px solid #ddd;">
    <h2>Hero Section</h2>
    <hr style="margin: 10px 0; border: none; border-top: 1px solid #ddd;">

    <div class="form-group">
        <label for="hero_title">Hero Title</label>
        <input id="hero_title" name="hero_title" value="{{ old('hero_title', $homepage->hero_title) }}" required>
    </div>

    <div class="form-group">
        <label for="hero_subtitle">Hero Subtitle</label>
        <textarea id="hero_subtitle" name="hero_subtitle" rows="3">{{ old('hero_subtitle', $homepage->hero_subtitle) }}</textarea>
    </div>

    <div class="form-group">
        <label for="hero_tagline">Hero Tagline</label>
        <input id="hero_tagline" name="hero_tagline" value="{{ old('hero_tagline', $homepage->hero_tagline) }}">
    </div>

    <div class="form-group">
        <label for="hero_image">Hero Image</label>
        <input id="hero_image" type="file" name="hero_image" accept="image/*">
        @if($homepage->hero_image)
            <img class="h-48 object-cover rounded-lg" src="{{ $storageImageUrl($homepage->hero_image) }}" alt="Hero image" onerror="this.onerror=null;this.src='{{ $fallbackImage }}';" style="margin-top:8px; width:100%; max-width: 320px; height: 12rem; object-fit: cover; object-position: center; border-radius: 10px; display:block;">
        @endif
    </div>

    <hr style="margin: 20px 0; border: none; border-top: 1px solid #ddd;">
    <h2>Promo Cards</h2>
    <p style="color: #888; margin: 0 0 16px; font-size: 0.9rem;">Create up to 15 promotional cards for your homepage. Add or edit cards below.</p>
    
    <style>
        .promo-cards-container { margin-bottom: 24px; }
        .promo-counter { display: flex; align-items: center; justify-content: space-between; margin-bottom: 16px; padding: 12px; background: rgba(255, 123, 50, 0.08); border: 1px solid rgba(255, 123, 50, 0.16); border-radius: 8px; }
        .promo-counter span { font-weight: 600; color: #f5f5f5; }
        .promo-card-item { border: 1px solid rgba(241, 200, 118, 0.2); border-radius: 10px; margin-bottom: 16px; overflow: hidden; background: rgba(255, 255, 255, 0.02); }
        .promo-card-header { display: flex; align-items: center; justify-content: space-between; padding: 14px 16px; background: rgba(255, 123, 50, 0.06); border-bottom: 1px solid rgba(241, 200, 118, 0.16); cursor: pointer; }
        .promo-card-header:hover { background: rgba(255, 123, 50, 0.12); }
        .promo-card-title { flex: 1; font-weight: 600; display: flex; align-items: center; gap: 8px; }
        .promo-card-title .badge { font-size: 0.75rem; padding: 2px 8px; background: rgba(255, 123, 50, 0.2); border-radius: 4px; color: #f1c876; font-weight: 700; }
        .promo-card-actions { display: flex; gap: 8px; align-items: center; }
        .promo-card-actions button { border: none; background: transparent; color: #cfcfcf; cursor: pointer; padding: 4px 8px; border-radius: 4px; font-size: 0.9rem; }
        .promo-card-actions button:hover { background: rgba(255, 123, 50, 0.2); color: #f5f5f5; }
        .promo-card-content { padding: 16px; display: none; }
        .promo-card-content.show { display: block; }
        .promo-card-body { display: grid; grid-template-columns: 1fr 300px; gap: 20px; }
        @media (max-width: 768px) { .promo-card-body { grid-template-columns: 1fr; } }
        .promo-form-fields { display: flex; flex-direction: column; gap: 14px; }
        .promo-form-field label { display: block; font-weight: 600; font-size: 0.9rem; margin-bottom: 6px; }
        .promo-form-field input, .promo-form-field textarea { width: 100%; padding: 8px 10px; border: 1px solid rgba(241, 200, 118, 0.18); border-radius: 6px; background: rgba(255, 255, 255, 0.04); color: #f5f5f5; font-family: inherit; }
        .promo-form-field textarea { resize: vertical; min-height: 80px; }
        .promo-preview-box { border: 1px solid rgba(241, 200, 118, 0.2); border-radius: 8px; padding: 12px; background: rgba(255, 255, 255, 0.02); }
        .promo-preview-box label { font-weight: 600; font-size: 0.85rem; margin-bottom: 8px; display: block; }
        .promo-preview-img { width: 100%; height: 180px; background: #1a120b; border-radius: 6px; object-fit: cover; margin-bottom: 8px; display: block; }
        .promo-preview-info { font-size: 0.8rem; color: #cfcfcf; line-height: 1.4; }
        .promo-card-footer { padding: 12px 16px; border-top: 1px solid rgba(241, 200, 118, 0.16); display: flex; gap: 8px; justify-content: flex-end; }
        .promo-card-footer button { padding: 6px 12px; border: 1px solid rgba(241, 200, 118, 0.3); background: transparent; color: #cfcfcf; border-radius: 6px; cursor: pointer; font-size: 0.85rem; }
        .promo-card-footer button:hover { background: rgba(255, 123, 50, 0.12); color: #f5f5f5; }
        .promo-card-footer button.delete { border-color: rgba(255, 100, 100, 0.3); color: #ff6b6b; }
        .promo-card-footer button.delete:hover { background: rgba(255, 100, 100, 0.12); }
    </style>

    <div class="promo-cards-container">
        <div class="promo-counter">
            <span>Cards: <span id="promo-count">0</span>/15</span>
            <button type="button" id="add-promo-card-btn" class="btn" style="margin: 0;">+ Add Promo Card</button>
        </div>

        <div id="promo-cards-list">
            @php 
                $unifiedCards = $homepage->unified_promo_cards ?? [];
                $cardCount = count($unifiedCards);
            @endphp
            @forelse($unifiedCards as $index => $card)
                <div class="promo-card-item" data-index="{{ $index }}">
                    <div class="promo-card-header" onclick="togglePromoCard(this)">
                        <div class="promo-card-title">
                            <span>▼</span>
                            <span>{{ $card['title'] ?? 'Untitled Card' }}</span>
                            @if($card['is_legacy'] ?? false)
                                <span class="badge">LEGACY</span>
                            @endif
                        </div>
                        <div class="promo-card-actions">
                            <span style="font-size: 0.8rem; color: #cfcfcf;">#{{ $index + 1 }}</span>
                        </div>
                    </div>
                    <div class="promo-card-content show">
                        <div class="promo-card-body">
                            <div class="promo-form-fields">
                                <div class="promo-form-field">
                                    <label>Title *</label>
                                    <input type="text" name="promo_cards[{{ $index }}][title]" value="{{ old('promo_cards.' . $index . '.title', $card['title'] ?? '') }}" maxlength="255" placeholder="e.g., Summer Special" required>
                                </div>
                                <div class="promo-form-field">
                                    <label>Description</label>
                                    <textarea name="promo_cards[{{ $index }}][description]" maxlength="1000" placeholder="Describe your promo...">{{ old('promo_cards.' . $index . '.description', $card['description'] ?? '') }}</textarea>
                                </div>
                                <div class="promo-form-field">
                                    <label>Link</label>
                                    <input type="text" name="promo_cards[{{ $index }}][link]" value="{{ old('promo_cards.' . $index . '.link', $card['link'] ?? '') }}" placeholder="e.g., /menu" maxlength="255">
                                </div>
                                <div class="promo-form-field">
                                    <label>Image Upload</label>
                                    <input type="file" name="promo_cards[{{ $index }}][image]" accept="image/*">
                                    <input type="hidden" name="promo_cards[{{ $index }}][image_current]" value="{{ $card['image'] ?? '' }}">
                                    <small style="color: #888;">JPG, PNG, WebP • Max 10MB</small>
                                </div>
                            </div>
                            <div class="promo-preview-box">
                                <label>Preview</label>
                                @if(!empty($card['image']))
                                    <img src="{{ $storageImageUrl($card['image']) }}" alt="{{ $card['title'] ?? 'Card' }}" class="promo-preview-img" onerror="this.onerror=null;this.src='{{ $fallbackImage }}';">
                                @else
                                    <div style="width: 100%; height: 180px; background: #1a120b; border-radius: 6px; display: flex; align-items: center; justify-content: center; color: #666; margin-bottom: 8px;">No image</div>
                                @endif
                                <div class="promo-preview-info">
                                    <strong>{{ $card['title'] ?? 'Untitled' }}</strong><br>
                                    {{ Str::limit($card['description'] ?? '', 60) }}
                                </div>
                            </div>
                        </div>
                        <div class="promo-card-footer">
                            <button type="button" class="delete" onclick="deletePromoCard(this)">🗑 Delete</button>
                        </div>
                    </div>
                </div>
            @empty
                <div style="text-align: center; padding: 24px; color: #888;">
                    <p>No promo cards yet. Click "Add Promo Card" to get started.</p>
                </div>
            @endforelse
        </div>
    </div>

    <style>
        .gallery-cards-container { margin-bottom: 24px; }
        .gallery-counter { display: flex; align-items: center; justify-content: space-between; margin-bottom: 16px; padding: 12px; background: rgba(255, 123, 50, 0.08); border: 1px solid rgba(255, 123, 50, 0.16); border-radius: 8px; }
        .gallery-counter span { font-weight: 600; color: #f5f5f5; }
        .gallery-card-item { border: 1px solid rgba(241, 200, 118, 0.2); border-radius: 10px; margin-bottom: 16px; overflow: hidden; background: rgba(255, 255, 255, 0.02); }
        .gallery-card-header { display: flex; align-items: center; justify-content: space-between; padding: 14px 16px; background: rgba(255, 123, 50, 0.06); border-bottom: 1px solid rgba(241, 200, 118, 0.16); cursor: pointer; }
        .gallery-card-header:hover { background: rgba(255, 123, 50, 0.12); }
        .gallery-card-title { flex: 1; font-weight: 600; display: flex; align-items: center; gap: 8px; }
        .gallery-card-title .badge { font-size: 0.75rem; padding: 2px 8px; background: rgba(255, 123, 50, 0.2); border-radius: 4px; color: #f1c876; font-weight: 700; }
        .gallery-card-actions { display: flex; gap: 8px; align-items: center; }
        .gallery-card-content { padding: 16px; display: none; }
        .gallery-card-content.show { display: block; }
        .gallery-card-body { display: grid; grid-template-columns: 1fr 300px; gap: 20px; }
        @media (max-width: 768px) { .gallery-card-body { grid-template-columns: 1fr; } }
        .gallery-form-fields { display: flex; flex-direction: column; gap: 14px; }
        .gallery-form-field label { display: block; font-weight: 600; font-size: 0.9rem; margin-bottom: 6px; }
        .gallery-form-field input, .gallery-form-field textarea { width: 100%; padding: 8px 10px; border: 1px solid rgba(241, 200, 118, 0.18); border-radius: 6px; background: rgba(255, 255, 255, 0.04); color: #f5f5f5; font-family: inherit; }
        .gallery-preview-box { border: 1px solid rgba(241, 200, 118, 0.2); border-radius: 8px; padding: 12px; background: rgba(255, 255, 255, 0.02); }
        .gallery-preview-box label { font-weight: 600; font-size: 0.85rem; margin-bottom: 8px; display: block; }
        .gallery-preview-img { width: 100%; height: 180px; background: #1a120b; border-radius: 6px; object-fit: cover; margin-bottom: 8px; display: block; }
        .gallery-preview-info { font-size: 0.8rem; color: #cfcfcf; line-height: 1.4; }
        .gallery-card-footer { padding: 12px 16px; border-top: 1px solid rgba(241, 200, 118, 0.16); display: flex; gap: 8px; justify-content: flex-end; }
        .gallery-card-footer button { padding: 6px 12px; border: 1px solid rgba(241, 200, 118, 0.3); background: transparent; color: #cfcfcf; border-radius: 6px; cursor: pointer; font-size: 0.85rem; }
        .gallery-card-footer button:hover { background: rgba(255, 123, 50, 0.12); color: #f5f5f5; }
        .gallery-card-footer button.delete { border-color: rgba(255, 100, 100, 0.3); color: #ff6b6b; }
        .gallery-card-footer button.delete:hover { background: rgba(255, 100, 100, 0.12); }
    </style>

    <hr style="margin: 20px 0; border: none; border-top: 1px solid #ddd;">
    <h2>Gallery Section</h2>
    <hr style="margin: 10px 0; border: none; border-top: 1px solid #ddd;">

    <div class="form-group">
        <label for="gallery_title">Gallery Title</label>
        <input id="gallery_title" name="gallery_title" value="{{ old('gallery_title', $homepage->gallery_title) }}" required>
    </div>

    <hr style="margin: 20px 0; border: none; border-top: 1px solid #ddd;">
    <h2>Gallery Cards (Multiple)</h2>
    <p class="muted">Optional: add up to 15 gallery cards. Legacy food card images are merged into the same editor.</p>
    <div class="gallery-cards-container">
        <div class="gallery-counter">
            <span>Cards: <span id="gallery-count">0</span>/15</span>
            <button type="button" id="add-gallery-card-btn" class="btn" style="margin: 0;">+ Add Gallery Card</button>
        </div>

        <div id="gallery-cards-list">
        @php $galleryCards = $homepage->unified_gallery_cards ?? ($homepage->gallery_cards ?? []); @endphp
        @forelse($galleryCards as $i => $card)
                @php
                    $redirectEnabled = isset($card['redirect_to_menu']) ? filter_var($card['redirect_to_menu'], FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE) : true;
                    $redirectEnabled = is_null($redirectEnabled) ? true : $redirectEnabled;
                @endphp
            <div class="gallery-card-item" data-index="{{ $i }}">
                <div class="gallery-card-header" onclick="toggleGalleryCard(this)">
                    <div class="gallery-card-title">
                        <span>▼</span>
                        <span>{{ $card['title'] ?? 'Untitled Card' }}</span>
                        @if($card['is_legacy'] ?? false)
                            <span class="badge">LEGACY</span>
                        @endif
                    </div>
                    <div class="gallery-card-actions">
                        <span style="font-size: 0.8rem; color: #cfcfcf;">#{{ $i + 1 }}</span>
                    </div>
                </div>
                <div class="gallery-card-content show">
                    <div class="gallery-card-body">
                        <div class="gallery-form-fields">
                            <div class="gallery-form-field">
                                <label>Title</label>
                                <input type="text" name="gallery_cards[{{ $i }}][title]" value="{{ old('gallery_cards.' . $i . '.title', $card['title'] ?? '') }}" maxlength="255" placeholder="e.g., Momos Platter">
                            </div>
                            <div class="gallery-form-field">
                                <label>Link</label>
                                <input type="text" name="gallery_cards[{{ $i }}][link]" value="{{ old('gallery_cards.' . $i . '.link', $card['link'] ?? '') }}" placeholder="e.g., /menu" maxlength="255">
                            </div>
                            <div class="gallery-form-field">
                                <label>Image Upload</label>
                                <input type="file" name="gallery_cards[{{ $i }}][image]" accept="image/*">
                                <input type="hidden" name="gallery_cards[{{ $i }}][image_current]" value="{{ $card['image'] ?? '' }}">
                                <input type="hidden" name="gallery_cards[{{ $i }}][redirect_to_menu]" value="0">
                                <small style="color: #888;">JPG, PNG, WebP • Max 10MB</small>
                            </div>
                            <div class="gallery-form-field">
                                <label style="font-size:0.9rem;display:inline-flex;align-items:center;gap:8px;">
                                    <input type="checkbox" name="gallery_cards[{{ $i }}][redirect_to_menu]" value="1" {{ (isset($card['redirect_to_menu']) ? ($card['redirect_to_menu'] ? 'checked' : '') : 'checked') }}>
                                    Redirect image click to Menu
                                </label>
                            </div>
                        </div>
                        <div class="gallery-preview-box">
                            <label>Preview</label>
                            @if(!empty($card['image']))
                                <img src="{{ $storageImageUrl($card['image']) }}" alt="{{ $card['title'] ?? 'Gallery card' }}" class="gallery-preview-img" onerror="this.onerror=null;this.src='{{ $fallbackImage }}';">
                            @else
                                <div style="width: 100%; height: 180px; background: #1a120b; border-radius: 6px; display: flex; align-items: center; justify-content: center; color: #666; margin-bottom: 8px;">No image</div>
                            @endif
                            <div class="gallery-preview-info">
                                <strong>{{ $card['title'] ?? 'Untitled' }}</strong><br>
                                {{ $redirectEnabled ? 'Menu redirect enabled' : 'Uses custom link if provided' }}
                            </div>
                        </div>
                    </div>
                    <div class="gallery-card-footer">
                        <button type="button" class="delete" onclick="deleteGalleryCard(this)">🗑 Delete</button>
                    </div>
                </div>
            </div>
        @empty
            <div style="text-align: center; padding: 24px; color: #888;" id="gallery-empty-state">
                <p>No gallery cards yet. Click "Add Gallery Card" to get started.</p>
            </div>
        @endforelse
        </div>
    </div>

    <hr style="margin: 20px 0; border: none; border-top: 1px solid #ddd;">
    <h2>Intro Section</h2>
    <hr style="margin: 10px 0; border: none; border-top: 1px solid #ddd;">

    <div class="form-group">
        <label for="intro_title">Intro Title</label>
        <input id="intro_title" name="intro_title" value="{{ old('intro_title', $homepage->intro_title) }}" required>
    </div>

    <div class="form-group">
        <label for="intro_text">Intro Text</label>
        <textarea id="intro_text" name="intro_text" rows="5">{{ old('intro_text', $homepage->intro_text) }}</textarea>
    </div>

    <div class="form-group">
        <label for="intro_image">Intro Image (Optional)</label>
        <p style="color: #888; font-size: 0.85rem; margin: 0 0 8px;">Recommended: 4:3 aspect ratio (e.g., 1200×900px). Leave empty to show text only.</p>
        <input id="intro_image" type="file" name="intro_image" accept="image/*">
        @if($homepage->intro_image)
            <div style="margin-top: 12px;">
                <p style="margin: 0 0 8px; font-size: 0.9rem; font-weight: 600;">Current Image:</p>
                <img src="{{ $storageImageUrl($homepage->intro_image) }}" alt="Intro image" onerror="this.onerror=null;this.src='{{ $fallbackImage }}'" style="max-width: 280px; height: auto; border-radius: 8px; display: block;">
                <label style="margin-top: 10px; display: flex; align-items: center; gap: 8px;">
                    <input type="checkbox" name="remove_intro_image" value="1" style="width: auto;">
                    <span style="font-size: 0.9rem;">Remove current intro image</span>
                </label>
            </div>
        @endif
    </div>

    <hr style="margin: 20px 0; border: none; border-top: 1px solid #ddd;">
    <h2>CTA Section</h2>
    <hr style="margin: 10px 0; border: none; border-top: 1px solid #ddd;">

    <div class="form-group">
        <label for="cta_button_label">CTA Button Label</label>
        <input id="cta_button_label" name="cta_button_label" value="{{ old('cta_button_label', $homepage->cta_button_label) }}" required>
    </div>

    <div class="form-group">
        <label for="contact_phone">Contact Phone</label>
        <input id="contact_phone" name="contact_phone" value="{{ old('contact_phone', $homepage->contact_phone) }}">
    </div>

    <hr style="margin: 20px 0; border: none; border-top: 1px solid #ddd;">

    <button class="btn cta-btn" type="submit">Save Homepage</button>
</form>
@endsection

@push('scripts')
<script>
// ===== PROMO CARDS - GLOBAL SCOPE (Accessible to inline onclick handlers) =====
let promoCardCount = 0;

function updatePromoCount() {
    document.getElementById('promo-count').textContent = promoCardCount;
}

function togglePromoCard(header) {
    const content = header.nextElementSibling;
    const arrow = header.querySelector('span:first-child');
    content.classList.toggle('show');
    arrow.textContent = content.classList.contains('show') ? '▼' : '▶';
}

function deletePromoCard(button) {
    const card = button.closest('.promo-card-item');
    const title = card.querySelector('.promo-card-title span:nth-child(2)').textContent;
    
    if (confirm(`Delete promo card: "${title}"?`)) {
        card.remove();
        updatePromoCardIndices();
        promoCardCount--;
        updatePromoCount();
    }
}

function updatePromoCardIndices() {
    const cards = document.querySelectorAll('#promo-cards-list .promo-card-item');
    cards.forEach((card, index) => {
        card.querySelectorAll('[name]').forEach(input => {
            input.name = input.name.replace(/promo_cards\[\d+\]/, `promo_cards[${index}]`);
        });
        card.querySelector('.promo-card-actions span').textContent = '#' + (index + 1);
    });
}

function escapeHtml(value) {
    return String(value)
        .replace(/&/g, '&amp;')
        .replace(/</g, '&lt;')
        .replace(/>/g, '&gt;')
        .replace(/"/g, '&quot;')
        .replace(/'/g, '&#039;');
}

function bindPromoCardLiveUpdates(card) {
    const titleInput = card.querySelector('input[name$="[title]"]');
    const descriptionInput = card.querySelector('textarea[name$="[description]"]');
    const imageInput = card.querySelector('input[type="file"][name$="[image]"]');
    const headerTitle = card.querySelector('.promo-card-title span:nth-child(2)');
    const previewBox = card.querySelector('.promo-preview-box');
    const previewInfo = card.querySelector('.promo-preview-info');

    const syncText = () => {
        const title = titleInput ? titleInput.value.trim() : '';
        const description = descriptionInput ? descriptionInput.value.trim() : '';

        if (headerTitle) {
            headerTitle.textContent = title || 'Untitled Card';
        }

        if (previewInfo) {
            previewInfo.innerHTML = `<strong>${escapeHtml(title || 'Untitled')}</strong><br>${escapeHtml(description || 'No description')}`;
        }
    };

    if (titleInput) {
        titleInput.addEventListener('input', syncText);
    }

    if (descriptionInput) {
        descriptionInput.addEventListener('input', syncText);
    }

    if (imageInput && previewBox) {
        imageInput.addEventListener('change', () => {
            const file = imageInput.files && imageInput.files[0];
            if (!file) {
                return;
            }

            const reader = new FileReader();
            reader.onload = (event) => {
                const existingImage = card.querySelector('.promo-preview-img');
                const previewImage = existingImage || previewBox.querySelector(':scope > div');

                if (existingImage) {
                    existingImage.src = event.target.result;
                    return;
                }

                if (previewImage) {
                    previewImage.outerHTML = `<img src="${event.target.result}" alt="${escapeHtml(titleInput ? titleInput.value.trim() || 'Card' : 'Card')}" class="promo-preview-img">`;
                }
            };
            reader.readAsDataURL(file);
        });
    }

    syncText();
}

// ===== INITIALIZATION =====
(() => {
    // Initialize promo card count from DOM
    promoCardCount = document.querySelectorAll('#promo-cards-list .promo-card-item').length;
    updatePromoCount();

    // Add Promo Card button listener
    document.getElementById('add-promo-card-btn').addEventListener('click', () => {
        if (promoCardCount >= 15) {
            alert('Maximum 15 promo cards reached');
            return;
        }

        const list = document.getElementById('promo-cards-list');
        const newCard = document.createElement('div');
        newCard.className = 'promo-card-item';
        newCard.dataset.index = promoCardCount;
        newCard.innerHTML = `
            <div class="promo-card-header" onclick="togglePromoCard(this)">
                <div class="promo-card-title">
                    <span>▼</span>
                    <span>Untitled Card</span>
                </div>
                <div class="promo-card-actions">
                    <span style="font-size: 0.8rem; color: #cfcfcf;">#${promoCardCount + 1}</span>
                </div>
            </div>
            <div class="promo-card-content show">
                <div class="promo-card-body">
                    <div class="promo-form-fields">
                        <div class="promo-form-field">
                            <label>Title *</label>
                            <input type="text" name="promo_cards[${promoCardCount}][title]" maxlength="255" placeholder="e.g., Summer Special" required>
                        </div>
                        <div class="promo-form-field">
                            <label>Description</label>
                            <textarea name="promo_cards[${promoCardCount}][description]" maxlength="1000" placeholder="Describe your promo..."></textarea>
                        </div>
                        <div class="promo-form-field">
                            <label>Link</label>
                            <input type="text" name="promo_cards[${promoCardCount}][link]" placeholder="e.g., /menu" maxlength="255">
                        </div>
                        <div class="promo-form-field">
                            <label>Image Upload</label>
                            <input type="file" name="promo_cards[${promoCardCount}][image]" accept="image/*">
                            <small style="color: #888;">JPG, PNG, WebP • Max 10MB</small>
                        </div>
                    </div>
                    <div class="promo-preview-box">
                        <label>Preview</label>
                        <div style="width: 100%; height: 180px; background: #1a120b; border-radius: 6px; display: flex; align-items: center; justify-content: center; color: #666; margin-bottom: 8px;">No image</div>
                        <div class="promo-preview-info">
                            <strong>Untitled</strong><br>
                            No description
                        </div>
                    </div>
                </div>
                <div class="promo-card-footer">
                    <button type="button" class="delete" onclick="deletePromoCard(this)">🗑 Delete</button>
                </div>
            </div>
        `;

        list.appendChild(newCard);
        bindPromoCardLiveUpdates(newCard);
        promoCardCount++;
        updatePromoCount();
    });

    document.querySelectorAll('#promo-cards-list .promo-card-item').forEach(bindPromoCardLiveUpdates);

    // ===== GALLERY CARDS MANAGEMENT =====
    let galleryCardCount = 0;

    function toggleGalleryCard(header) {
        const content = header.nextElementSibling;
        const arrow = header.querySelector('span:first-child');
        content.classList.toggle('show');
        arrow.textContent = content.classList.contains('show') ? '▼' : '▶';
    }

    function deleteGalleryCard(button) {
        const card = button.closest('.gallery-card-item');
        const title = card.querySelector('.gallery-card-title span:nth-child(2)').textContent;

        if (confirm(`Delete gallery card: "${title}"?`)) {
            card.remove();
            updateGalleryCardIndices();
            galleryCardCount--;
            updateGalleryCount();
            syncGalleryEmptyState();
        }
    }

    function updateGalleryCount() {
        const countEl = document.getElementById('gallery-count');
        if (countEl) {
            countEl.textContent = galleryCardCount;
        }
    }

    function syncGalleryEmptyState() {
        const emptyState = document.getElementById('gallery-empty-state');
        const cards = document.querySelectorAll('#gallery-cards-list .gallery-card-item');
        if (emptyState) {
            emptyState.style.display = cards.length ? 'none' : 'block';
        } else if (!cards.length) {
            const list = document.getElementById('gallery-cards-list');
            if (list) {
                const div = document.createElement('div');
                div.id = 'gallery-empty-state';
                div.style = 'text-align:center;padding:24px;color:#888;';
                div.innerHTML = '<p>No gallery cards yet. Click "Add Gallery Card" to get started.</p>';
                list.appendChild(div);
            }
        }
    }

    function updateGalleryCardIndices() {
        const cards = document.querySelectorAll('#gallery-cards-list .gallery-card-item');
        cards.forEach((card, index) => {
            card.dataset.index = index;
            card.querySelectorAll('[name]').forEach(input => {
                input.name = input.name.replace(/gallery_cards\[\d+\]/, `gallery_cards[${index}]`);
            });
            const counter = card.querySelector('.gallery-card-actions span');
            if (counter) {
                counter.textContent = '#' + (index + 1);
            }
        });
    }

    function bindGalleryCardLiveUpdates(card) {
        const titleInput = card.querySelector('input[name$="[title]"]');
        const imageInput = card.querySelector('input[type="file"][name$="[image]"]');
        const headerTitle = card.querySelector('.gallery-card-title span:nth-child(2)');
        const previewBox = card.querySelector('.gallery-preview-box');
        const previewInfo = card.querySelector('.gallery-preview-info');

        const syncText = () => {
            const title = titleInput ? titleInput.value.trim() : '';

            if (headerTitle) {
                headerTitle.textContent = title || 'Untitled Card';
            }

            if (previewInfo) {
                const redirectCheckbox = card.querySelector('input[type="checkbox"][name$="[redirect_to_menu]"]');
                const redirectText = redirectCheckbox && redirectCheckbox.checked ? 'Menu redirect enabled' : 'Uses custom link if provided';
                previewInfo.innerHTML = `<strong>${escapeHtml(title || 'Untitled')}</strong><br>${escapeHtml(redirectText)}`;
            }
        };

        if (titleInput) {
            titleInput.addEventListener('input', syncText);
        }

        if (imageInput && previewBox) {
            imageInput.addEventListener('change', () => {
                const file = imageInput.files && imageInput.files[0];
                if (!file) {
                    return;
                }

                const reader = new FileReader();
                reader.onload = (event) => {
                    const existingImage = card.querySelector('.gallery-preview-img');
                    const previewImage = existingImage || previewBox.querySelector(':scope > div');

                    if (existingImage) {
                        existingImage.src = event.target.result;
                        return;
                    }

                    if (previewImage) {
                        previewImage.outerHTML = `<img src="${event.target.result}" alt="${escapeHtml(titleInput ? titleInput.value.trim() || 'Card' : 'Card')}" class="gallery-preview-img">`;
                    }
                };
                reader.readAsDataURL(file);
            });
        }

        syncText();
    }

    function makeTemplate(prefix) {
        return function(index) {
            const div = document.createElement('div');
            div.className = 'gallery-card-item';
            div.dataset.index = index;
            div.innerHTML = `
                <div class="gallery-card-header" onclick="toggleGalleryCard(this)">
                    <div class="gallery-card-title">
                        <span>▼</span>
                        <span>Untitled Card</span>
                    </div>
                    <div class="gallery-card-actions">
                        <span style="font-size: 0.8rem; color: #cfcfcf;">#${index + 1}</span>
                    </div>
                </div>
                <div class="gallery-card-content show">
                    <div class="gallery-card-body">
                        <div class="gallery-form-fields">
                            <div class="gallery-form-field">
                                <label>Title</label>
                                <input name="${prefix}[${index}][title]" maxlength="255" placeholder="e.g., Momos Platter">
                            </div>
                            <div class="gallery-form-field">
                                <label>Link</label>
                                <input name="${prefix}[${index}][link]" maxlength="255" placeholder="e.g., /menu">
                            </div>
                            <div class="gallery-form-field">
                                <label>Image Upload</label>
                                <input type="file" name="${prefix}[${index}][image]" accept="image/*">
                                <input type="hidden" name="${prefix}[${index}][image_current]" value="">
                                <input type="hidden" name="${prefix}[${index}][redirect_to_menu]" value="0">
                                <small style="color: #888;">JPG, PNG, WebP • Max 10MB</small>
                            </div>
                            <div class="gallery-form-field">
                                <label style="font-size:0.9rem;display:inline-flex;align-items:center;gap:8px;">
                                    <input type="checkbox" name="${prefix}[${index}][redirect_to_menu]" value="1" checked>
                                    Redirect image click to Menu
                                </label>
                            </div>
                        </div>
                        <div class="gallery-preview-box">
                            <label>Preview</label>
                            <div style="width: 100%; height: 180px; background: #1a120b; border-radius: 6px; display: flex; align-items: center; justify-content: center; color: #666; margin-bottom: 8px;">No image</div>
                            <div class="gallery-preview-info">
                                <strong>Untitled</strong><br>
                                Uses custom link if provided
                            </div>
                        </div>
                    </div>
                    <div class="gallery-card-footer">
                        <button type="button" class="delete" onclick="deleteGalleryCard(this)">🗑 Delete</button>
                    </div>
                </div>
            `;
            return div;
        };
    }

    const galleryEditor = document.getElementById('gallery-cards-list');
    const addGallery = document.getElementById('add-gallery-card-btn');
    const makeGallery = makeTemplate('gallery_cards');

    function currentCount(container) {
        return container.querySelectorAll('.gallery-card-item').length;
    }

    addGallery && addGallery.addEventListener('click', () => {
        if (currentCount(galleryEditor) >= 15) return alert('Maximum 15 gallery cards');
        const emptyState = document.getElementById('gallery-empty-state');
        if (emptyState) {
            emptyState.remove();
        }
        const idx = currentCount(galleryEditor);
        const newCard = makeGallery(idx);
        galleryEditor.appendChild(newCard);
        bindGalleryCardLiveUpdates(newCard);
        galleryCardCount++;
        updateGalleryCount();
        updateGalleryCardIndices();
        syncGalleryEmptyState();
    });

    galleryCardCount = currentCount(galleryEditor);
    updateGalleryCount();

    document.querySelectorAll('#gallery-cards-list .gallery-card-item').forEach(bindGalleryCardLiveUpdates);
    updateGalleryCardIndices();
    syncGalleryEmptyState();

    window.toggleGalleryCard = toggleGalleryCard;
    window.deleteGalleryCard = deleteGalleryCard;
    window.updateGalleryCardIndices = updateGalleryCardIndices;
})();

// Sync promo card indices before form submission to ensure correct array keys
document.querySelector('form').addEventListener('submit', (e) => {
    updatePromoCardIndices();
    updateGalleryCardIndices();
});
</script>
@endpush
