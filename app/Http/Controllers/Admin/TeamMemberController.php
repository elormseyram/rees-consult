<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TeamMember;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class TeamMemberController extends Controller
{
    public function index()
    {
        $teamMembers = TeamMember::ordered()->paginate(10);
        return view('admin.team.index', compact('teamMembers'));
    }

    public function create()
    {
        return view('admin.team.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'role' => 'required|string|max:255',
            'bio' => 'nullable|string',
            'image' => 'nullable|image|max:2048',
            'facebook' => 'nullable|url',
            'twitter' => 'nullable|url',
            'linkedin' => 'nullable|url',
            'instagram' => 'nullable|url',
            'is_active' => 'boolean',
            'order' => 'integer',
        ]);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('team', 'public');
        }

        $validated['social_links'] = [
            'facebook' => $request->facebook,
            'twitter' => $request->twitter,
            'linkedin' => $request->linkedin,
            'instagram' => $request->instagram,
        ];

        $validated['is_active'] = $request->has('is_active');

        TeamMember::create($validated);

        return redirect()->route('admin.team.index')->with('success', 'Team member added successfully.');
    }

    public function edit($team)
    {
        $teamMember = TeamMember::findOrFail($team);
        return view('admin.team.edit', compact('teamMember'));
    }

    public function update(Request $request, $team)
    {
        $teamMember = TeamMember::findOrFail($team);
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'role' => 'required|string|max:255',
            'bio' => 'nullable|string',
            'image' => 'nullable|image|max:2048',
            'facebook' => 'nullable|url',
            'twitter' => 'nullable|url',
            'linkedin' => 'nullable|url',
            'instagram' => 'nullable|url',
            'is_active' => 'boolean',
            'order' => 'integer',
        ]);

        if ($request->hasFile('image')) {
            if ($teamMember->image) {
                Storage::disk('public')->delete($teamMember->image);
            }
            $validated['image'] = $request->file('image')->store('team', 'public');
        }

        $validated['social_links'] = [
            'facebook' => $request->facebook,
            'twitter' => $request->twitter,
            'linkedin' => $request->linkedin,
            'instagram' => $request->instagram,
        ];

        $validated['is_active'] = $request->has('is_active');

        $teamMember->update($validated);

        return redirect()->route('admin.team.index')->with('success', 'Team member updated successfully.');
    }

    public function destroy($team)
    {
        $teamMember = TeamMember::findOrFail($team);
        
        if ($teamMember->image) {
            Storage::disk('public')->delete($teamMember->image);
        }
        $teamMember->delete();
        return redirect()->route('admin.team.index')->with('success', 'Team member deleted successfully.');
    }
}
