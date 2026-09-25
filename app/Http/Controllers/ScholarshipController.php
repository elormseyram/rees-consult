<?php

namespace App\Http\Controllers;

use App\Models\Scholarship;
use Illuminate\Http\Request;

class ScholarshipController extends Controller
{
    public function index(Request $request)
    {
        $query = Scholarship::active();

        if ($request->filled('country')) {
            $query->where('country', $request->country);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('country', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        $scholarships = $query->ordered()->paginate(12);
        $countries = Scholarship::active()->distinct()->pluck('country')->sort();

        return view('scholarships.index', compact('scholarships', 'countries'));
    }

    public function show(Scholarship $scholarship)
    {
        abort_unless($scholarship->is_active, 404);

        return view('scholarships.show', compact('scholarship'));
    }
}
