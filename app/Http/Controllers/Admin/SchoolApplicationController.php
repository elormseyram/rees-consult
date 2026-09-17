<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SchoolApplication;
use Illuminate\Http\Request;

class SchoolApplicationController extends Controller
{
    public function index(Request $request)
    {
        $query = SchoolApplication::latest();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $applications = $query->paginate(15);
        return view('admin.school-applications.index', compact('applications'));
    }

    public function show(SchoolApplication $schoolApplication)
    {
        return view('admin.school-applications.show', compact('schoolApplication'));
    }

    public function updateStatus(Request $request, SchoolApplication $schoolApplication)
    {
        $request->validate([
            'status' => 'required|in:pending,reviewing,accepted,closed',
        ]);

        $schoolApplication->update([
            'status' => $request->status,
            'notes' => $request->notes ?? $schoolApplication->notes,
        ]);

        return back()->with('success', 'Application status updated to ' . ucfirst($request->status) . '.');
    }

    public function destroy(SchoolApplication $schoolApplication)
    {
        $schoolApplication->delete();
        return redirect()->route('admin.school-applications.index')->with('success', 'Application record deleted.');
    }
}
