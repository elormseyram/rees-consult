<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pricing;
use Illuminate\Http\Request;

class PricingController extends Controller
{
    public function index()
    {
        $pricings = Pricing::ordered()->paginate(20);
        return view('admin.pricings.index', compact('pricings'));
    }

    public function create()
    {
        return view('admin.pricings.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'test_name' => 'required|string|max:255',
            'group_in_person' => 'required|integer|min:0',
            'group_online' => 'required|integer|min:0',
            'one_on_one_in_person' => 'required|integer|min:0',
            'one_on_one_online' => 'required|integer|min:0',
            'order' => 'required|integer|min:0',
            'is_active' => 'boolean',
        ]);

        $validated['is_active'] = $request->has('is_active');

        Pricing::create($validated);

        return redirect()->route('admin.pricings.index')->with('success', 'Pricing added successfully.');
    }

    public function edit(Pricing $pricing)
    {
        return view('admin.pricings.edit', compact('pricing'));
    }

    public function update(Request $request, Pricing $pricing)
    {
        $validated = $request->validate([
            'test_name' => 'required|string|max:255',
            'group_in_person' => 'required|integer|min:0',
            'group_online' => 'required|integer|min:0',
            'one_on_one_in_person' => 'required|integer|min:0',
            'one_on_one_online' => 'required|integer|min:0',
            'order' => 'required|integer|min:0',
            'is_active' => 'boolean',
        ]);

        $validated['is_active'] = $request->has('is_active');

        $pricing->update($validated);

        return redirect()->route('admin.pricings.index')->with('success', 'Pricing updated successfully.');
    }

    public function destroy(Pricing $pricing)
    {
        $pricing->delete();
        return redirect()->route('admin.pricings.index')->with('success', 'Pricing deleted successfully.');
    }
}
