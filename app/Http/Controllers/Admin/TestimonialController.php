<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Testimonial;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class TestimonialController extends Controller
{
    public function index(Request $request)
    {
        // Award-category videos are managed from Admin → Awards, not here.
        // Exclude them from the default query unless the admin explicitly filters for them.
        $query = Testimonial::ordered()
            ->where(function ($q) {
                $q->whereNull('video_category')
                  ->orWhere('video_category', '!=', 'award');
            });

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('role', 'like', "%{$search}%")
                  ->orWhere('message', 'like', "%{$search}%");
            });
        }

        if ($request->filled('category')) {
            $category = $request->input('category');
            if ($category === 'written') {
                $query->whereNull('youtube_video_url');
            } elseif ($category === 'short') {
                $query->where('is_short', true)->whereNotNull('youtube_video_url');
            } else {
                // Explicitly selected category — override the default exclusion of 'award'
                $query->where('video_category', $category);
            }
        }

        if ($request->filled('status')) {
            $status = $request->input('status');
            $query->where('is_active', $status === 'active');
        }

        $testimonials = $query->paginate(15)->withQueryString();

        return view('admin.testimonials.index', compact('testimonials'));
    }

    public function create()
    {
        return view('admin.testimonials.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'             => 'required|string|max:255',
            'role'             => 'required|string|max:255',
            'message'          => 'nullable|string',
            'image'            => 'nullable|image|max:2048',
            'youtube_video_url'=> 'nullable|url|max:500',
            'video_category'   => 'nullable|in:testimonial,award,other',
            'rating'           => 'required|integer|min:1|max:5',
            'is_active'        => 'boolean',
            'is_showcase'      => 'boolean',
            'is_short'         => 'boolean',
            'order'            => 'nullable|integer|min:0',
        ]);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('testimonials', 'public');
        }

        $validated['is_active']  = $request->has('is_active');
        $validated['is_showcase']= $request->has('is_showcase');
        $validated['is_short']   = $request->has('is_short');
        $validated['order'] = $request->input('order') !== null ? intval($request->input('order')) : 0;

        if ($validated['is_showcase'] && !empty($validated['youtube_video_url'])) {
            $category = $request->input('video_category') ?: 'testimonial';
            if ($category === 'testimonial') {
                Testimonial::where(function ($q) {
                    $q->whereNull('video_category')
                      ->orWhere('video_category', 'testimonial');
                })->update(['is_showcase' => false]);
            } else {
                Testimonial::where('video_category', $category)->update(['is_showcase' => false]);
            }
        } else {
            $validated['is_showcase'] = false;
        }

        Testimonial::create($validated);

        return redirect()->route('admin.testimonials.index')->with('success', 'Testimonial created successfully.');
    }

    public function edit(Testimonial $testimonial)
    {
        return view('admin.testimonials.edit', compact('testimonial'));
    }

    public function update(Request $request, Testimonial $testimonial)
    {
        $validated = $request->validate([
            'name'             => 'required|string|max:255',
            'role'             => 'required|string|max:255',
            'message'          => 'nullable|string',
            'image'            => 'nullable|image|max:2048',
            'youtube_video_url'=> 'nullable|url|max:500',
            'video_category'   => 'nullable|in:testimonial,award,other',
            'rating'           => 'required|integer|min:1|max:5',
            'is_active'        => 'boolean',
            'is_showcase'      => 'boolean',
            'is_short'         => 'boolean',
            'order'            => 'nullable|integer|min:0',
        ]);

        if ($request->hasFile('image')) {
            if ($testimonial->image) {
                Storage::disk('public')->delete($testimonial->image);
            }
            $validated['image'] = $request->file('image')->store('testimonials', 'public');
        }

        $validated['is_active']  = $request->has('is_active');
        $validated['is_showcase']= $request->has('is_showcase');
        $validated['is_short']   = $request->has('is_short');
        $validated['order'] = $request->input('order') !== null ? intval($request->input('order')) : 0;

        if ($validated['is_showcase'] && !empty($validated['youtube_video_url'])) {
            $category = $request->input('video_category') ?: 'testimonial';
            if ($category === 'testimonial') {
                Testimonial::where(function ($q) {
                    $q->whereNull('video_category')
                      ->orWhere('video_category', 'testimonial');
                })->where('id', '!=', $testimonial->id)->update(['is_showcase' => false]);
            } else {
                Testimonial::where('video_category', $category)->where('id', '!=', $testimonial->id)->update(['is_showcase' => false]);
            }
        } else {
            $validated['is_showcase'] = false;
        }

        $testimonial->update($validated);

        return redirect()->route('admin.testimonials.index')->with('success', 'Testimonial updated successfully.');
    }

    public function destroy(Testimonial $testimonial)
    {
        if ($testimonial->image) {
            Storage::disk('public')->delete($testimonial->image);
        }
        $testimonial->delete();
        return redirect()->route('admin.testimonials.index')->with('success', 'Testimonial deleted successfully.');
    }

    public function toggleStatus(Testimonial $testimonial)
    {
        $testimonial->update([
            'is_active' => !$testimonial->is_active
        ]);

        return redirect()->route('admin.testimonials.index')->with('success', 'Testimonial status updated successfully.');
    }

    /**
     * Persist a new display order submitted by the drag-and-drop UI.
     * Expects JSON body: [{ "id": 3, "order": 0 }, { "id": 1, "order": 1 }, …]
     */
    public function reorder(Request $request)
    {
        $request->validate([
            'order'          => 'required|array',
            'order.*.id'     => 'required|integer|exists:testimonials,id',
            'order.*.order'  => 'required|integer|min:0',
        ]);

        foreach ($request->input('order') as $item) {
            Testimonial::where('id', $item['id'])->update(['order' => $item['order']]);
        }

        return response()->json(['success' => true]);
    }
}

