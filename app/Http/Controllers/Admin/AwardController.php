<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Award;
use App\Models\Testimonial;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AwardController extends Controller
{
    public function index(Request $request)
    {
        $query = Award::ordered();

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('awarded_by', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $status = $request->input('status');
            $query->where('is_active', $status === 'active');
        }

        $awards = $query->paginate(15)->withQueryString();

        // Award-category video testimonials — shown in a separate tab on this page
        $awardVideosQuery = Testimonial::where('video_category', 'award')->ordered();

        if ($request->filled('video_search')) {
            $vs = $request->input('video_search');
            $awardVideosQuery->where(function ($q) use ($vs) {
                $q->where('name', 'like', "%{$vs}%")
                  ->orWhere('role', 'like', "%{$vs}%");
            });
        }

        if ($request->filled('video_status')) {
            $awardVideosQuery->where('is_active', $request->input('video_status') === 'active');
        }

        $awardVideos = $awardVideosQuery->paginate(15, ['*'], 'vpage')->withQueryString();

        return view('admin.awards.index', compact('awards', 'awardVideos'));
    }

    public function create()
    {
        return view('admin.awards.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title'      => 'required|string|max:255',
            'description'=> 'nullable|string',
            'photo'      => 'nullable|image|max:4096',
            'awarded_by' => 'nullable|string|max:255',
            'award_date' => 'nullable|date',
            'is_active'  => 'boolean',
        ]);

        if ($request->hasFile('photo')) {
            $validated['photo'] = $request->file('photo')->store('awards', 'public');
        }

        $validated['is_active'] = $request->has('is_active');
        Award::query()->increment('order');
        $validated['order'] = 0;

        Award::create($validated);

        return redirect()->route('admin.awards.index')
                         ->with('success', 'Award created successfully.');
    }

    public function edit(Award $award)
    {
        return view('admin.awards.edit', compact('award'));
    }

    public function update(Request $request, Award $award)
    {
        $validated = $request->validate([
            'title'      => 'required|string|max:255',
            'description'=> 'nullable|string',
            'photo'      => 'nullable|image|max:4096',
            'awarded_by' => 'nullable|string|max:255',
            'award_date' => 'nullable|date',
            'is_active'  => 'boolean',
            'order'      => 'nullable|integer|min:0',
        ]);

        if ($request->hasFile('photo')) {
            if ($award->photo) {
                Storage::disk('public')->delete($award->photo);
            }
            $validated['photo'] = $request->file('photo')->store('awards', 'public');
        }

        $validated['is_active'] = $request->has('is_active');
        $validated['order']     = $request->input('order') !== null ? intval($request->input('order')) : $award->order;

        $award->update($validated);

        return redirect()->route('admin.awards.index')
                         ->with('success', 'Award updated successfully.');
    }

    public function destroy(Award $award)
    {
        if ($award->photo) {
            Storage::disk('public')->delete($award->photo);
        }
        $award->delete();
        return redirect()->route('admin.awards.index')
                         ->with('success', 'Award deleted successfully.');
    }

    public function toggleStatus(Award $award)
    {
        $award->update(['is_active' => !$award->is_active]);
        return redirect()->route('admin.awards.index')
                         ->with('success', 'Award status updated.');
    }

    public function reorder(Request $request)
    {
        $request->validate(['items' => 'required|array']);
        foreach ($request->items as $item) {
            Award::where('id', $item['id'])->update(['order' => $item['order']]);
        }
        return response()->json(['success' => true]);
    }

    public function bulkUpload(Request $request)
    {
        $request->validate([
            'files' => 'required|array',
            'files.*' => 'required|image|max:4096',
        ]);

        $uploadedCount = 0;
        $files = $request->file('files');
        Award::query()->increment('order', count($files));
        $newOrder = 0;

        foreach ($files as $file) {
            $originalName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
            $title = ucwords(str_replace(['-', '_'], ' ', $originalName));
            $photoPath = $file->store('awards', 'public');

            Award::create([
                'title' => $title,
                'photo' => $photoPath,
                'is_active' => true,
                'order' => $newOrder++,
            ]);

            $uploadedCount++;
        }

        return redirect()->route('admin.awards.index')
                         ->with('success', "Successfully bulk uploaded {$uploadedCount} award image(s).");
    }
}
