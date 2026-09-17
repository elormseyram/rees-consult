<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HeroImage;
use Illuminate\Http\Request;

class HeroImageController extends Controller
{
    public function index()
    {
        $heroImages = HeroImage::paginate(10);
        return view('admin.hero-images.index', compact('heroImages'));
    }

    public function create()
    {
        $pages = [
            'home' => 'Home',
            'about' => 'About Us',
            'services' => 'Services',
            'scholarships' => 'Scholarships',
            'events' => 'Events',
            'blog' => 'Blog',
            'contact' => 'Contact',
        ];
        return view('admin.hero-images.create', compact('pages'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'page_slug' => 'required|string|unique:hero_images,page_slug',
            'page_title' => 'required|string|max:255',
            'image_url' => 'nullable|url',
            'image_path' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
            'overlay_opacity' => 'required|numeric|min:0|max:1',
            'is_active' => 'boolean',
        ]);

        if ($request->hasFile('image_path')) {
            $path = $request->file('image_path')->store('hero-images', 'public');
            $validated['image_path'] = $path;
        }

        $validated['is_active'] = $request->has('is_active');

        HeroImage::create($validated);

        return redirect()->route('admin.hero-images.index')->with('success', 'Hero image added successfully.');
    }

    public function edit(HeroImage $heroImage)
    {
        $pages = [
            'home' => 'Home',
            'about' => 'About Us',
            'services' => 'Services',
            'scholarships' => 'Scholarships',
            'events' => 'Events',
            'blog' => 'Blog',
            'contact' => 'Contact',
        ];
        return view('admin.hero-images.edit', compact('heroImage', 'pages'));
    }

    public function update(Request $request, HeroImage $heroImage)
    {
        $validated = $request->validate([
            'page_slug' => 'required|string|unique:hero_images,page_slug,' . $heroImage->id,
            'page_title' => 'required|string|max:255',
            'image_url' => 'nullable|url',
            'image_path' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
            'overlay_opacity' => 'required|numeric|min:0|max:1',
            'is_active' => 'boolean',
        ]);

        if ($request->hasFile('image_path')) {
            if ($heroImage->image_path) {
                \Storage::disk('public')->delete($heroImage->image_path);
            }
            $path = $request->file('image_path')->store('hero-images', 'public');
            $validated['image_path'] = $path;
        }

        $validated['is_active'] = $request->has('is_active');

        $heroImage->update($validated);

        return redirect()->route('admin.hero-images.index')->with('success', 'Hero image updated successfully.');
    }

    public function destroy(HeroImage $heroImage)
    {
        if ($heroImage->image_path) {
            \Storage::disk('public')->delete($heroImage->image_path);
        }
        $heroImage->delete();
        return redirect()->route('admin.hero-images.index')->with('success', 'Hero image deleted successfully.');
    }
}
