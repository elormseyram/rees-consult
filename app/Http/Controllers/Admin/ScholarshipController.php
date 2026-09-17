<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Scholarship;
use Illuminate\Http\Request;

class ScholarshipController extends Controller
{
    public function index()
    {
        $scholarships = Scholarship::paginate(15);
        return view('admin.scholarships.index', compact('scholarships'));
    }

    public function create()
    {
        return view('admin.scholarships.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'country' => 'required|string|max:255',
            'deadline' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'requirements' => 'nullable|array',
            'is_active' => 'boolean',
            'order' => 'integer',
        ]);

        $validated['is_active'] = $request->has('is_active');

        Scholarship::create($validated);

        return redirect()->route('admin.scholarships.index')->with('success', 'Scholarship added successfully.');
    }

    public function edit($scholarship)
    {
        $scholarship = Scholarship::findOrFail($scholarship);
        return view('admin.scholarships.edit', compact('scholarship'));
    }

    public function update(Request $request, $scholarship)
    {
        $scholarship = Scholarship::findOrFail($scholarship);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'country' => 'required|string|max:255',
            'deadline' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'requirements' => 'nullable|array',
            'is_active' => 'boolean',
            'order' => 'integer',
        ]);

        $validated['is_active'] = $request->has('is_active');

        $scholarship->update($validated);

        return redirect()->route('admin.scholarships.index')->with('success', 'Scholarship updated successfully.');
    }

    public function destroy($scholarship)
    {
        $scholarship = Scholarship::findOrFail($scholarship);
        $scholarship->delete();
        return redirect()->route('admin.scholarships.index')->with('success', 'Scholarship deleted successfully.');
    }
}
