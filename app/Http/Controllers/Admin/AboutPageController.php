<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Helpers\ImageHelper;
use App\Models\AboutPage;
use Illuminate\Http\Request;

class AboutPageController extends Controller
{
    public function edit()
    {
        $about = AboutPage::first();
        return view('admin.about.edit', compact('about')); 
    }

    public function update(Request $request)
    {
        $about = AboutPage::first() ?? new AboutPage();

        $about->title = $request->title;
        $about->description = $request->description;

        if ($request->hasFile('image')) {
            $path = ImageHelper::upload($request->file('image'), 'about');
            $about->image = $path;
        }

        $about->save();

        return back()->with('success', 'About page updated');
    }
}
