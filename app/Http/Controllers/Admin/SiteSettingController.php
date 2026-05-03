<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SiteSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

class SiteSettingController extends Controller
{
    private function defaults(): array
    {
        return [
            'phone' => '+44 20 1234 5678',
            'email' => 'hello@steamandspice.co.uk',
            'address' => '221B Baker Street, London, UK',
        ];
    }

    private function getOrCreateSettings(): SiteSetting
    {
        return SiteSetting::query()->latest()->first()
            ?: SiteSetting::query()->create($this->defaults());
    }

    public function edit()
    {
        if (!Schema::hasTable('site_settings')) {
            return redirect()->route('admin.dashboard')->with('error', 'Site settings table is unavailable.');
        }

        $siteSettings = $this->getOrCreateSettings();

        return view('admin.settings.edit', compact('siteSettings'));
    }

    public function update(Request $request)
    {
        if (!Schema::hasTable('site_settings')) {
            return redirect()->route('admin.dashboard')->with('error', 'Site settings table is unavailable.');
        }

        $siteSettings = $this->getOrCreateSettings();

        $validated = $request->validate([
            'phone' => ['nullable', 'string', 'max:30'],
            'email' => ['nullable', 'email', 'max:255'],
            'address' => ['nullable', 'string', 'max:500'],
        ]);

        $siteSettings->update($validated);

        return redirect()->route('admin.settings.edit')->with('success', 'Contact details updated.');
    }
}
