<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HeroSlider;
use Illuminate\Http\Request;

class HeroSliderController extends Controller
{
    public function index()
    {
        $sliders = HeroSlider::orderBy('order', 'asc')->paginate(10);
        return view('admin.hero-sliders.index', compact('sliders'));
    }

    public function create()
    {
        return view('admin.hero-sliders.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'nullable|string|max:255',
            'subtitle' => 'nullable|string|max:255',
            'image_url' => 'nullable|url',
            'image_path' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
            'overlay_opacity' => 'required|numeric|min:0|max:1',
            'order' => 'required|integer|min:0',
            'is_active' => 'boolean',
        ]);

        if ($request->hasFile('image_path')) {
            $path = $request->file('image_path')->store('hero-sliders', 'public');
            $validated['image_path'] = $path;
        }

        $validated['is_active'] = $request->has('is_active');

        HeroSlider::create($validated);

        return redirect()->route('admin.hero-sliders.index')->with('success', 'Hero slider image added successfully.');
    }

    public function edit(HeroSlider $heroSlider)
    {
        return view('admin.hero-sliders.edit', compact('heroSlider'));
    }

    public function update(Request $request, HeroSlider $heroSlider)
    {
        $validated = $request->validate([
            'title' => 'nullable|string|max:255',
            'subtitle' => 'nullable|string|max:255',
            'image_url' => 'nullable|url',
            'image_path' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
            'overlay_opacity' => 'required|numeric|min:0|max:1',
            'order' => 'required|integer|min:0',
            'is_active' => 'boolean',
        ]);

        if ($request->hasFile('image_path')) {
            if ($heroSlider->image_path) {
                \Storage::disk('public')->delete($heroSlider->image_path);
            }
            $path = $request->file('image_path')->store('hero-sliders', 'public');
            $validated['image_path'] = $path;
        }

        $validated['is_active'] = $request->has('is_active');

        $heroSlider->update($validated);

        return redirect()->route('admin.hero-sliders.index')->with('success', 'Hero slider image updated successfully.');
    }

    public function destroy(HeroSlider $heroSlider)
    {
        if ($heroSlider->image_path) {
            \Storage::disk('public')->delete($heroSlider->image_path);
        }
        $heroSlider->delete();
        return redirect()->route('admin.hero-sliders.index')->with('success', 'Hero slider image deleted successfully.');
    }
}
