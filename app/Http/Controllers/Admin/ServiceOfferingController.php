<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ServiceOffering;
use Illuminate\Http\Request;

class ServiceOfferingController extends Controller
{
    public function index()
    {
        $offerings = ServiceOffering::orderBy('category')->orderBy('order')->paginate(20);
        $categories = [
            'test_preparation' => 'Test Preparation',
            'job_abroad' => 'Jobs Abroad',
            'school_scholarship' => 'Schools & Scholarships',
            'benefits' => 'Benefits',
            'countries' => 'Countries',
            'vacancies' => 'Vacancies',
            'partners' => 'Partners',
        ];
        return view('admin.service-offerings.index', compact('offerings', 'categories'));
    }

    public function create()
    {
        $categories = [
            'test_preparation' => 'Test Preparation',
            'job_abroad' => 'Jobs Abroad',
            'school_scholarship' => 'Schools & Scholarships',
            'benefits' => 'Benefits',
            'countries' => 'Countries',
            'vacancies' => 'Vacancies',
            'partners' => 'Partners',
        ];
        return view('admin.service-offerings.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category' => 'required|string|in:test_preparation,job_abroad,school_scholarship,benefits,countries,vacancies,partners',
            'icon' => 'nullable|string|max:50',
            'order' => 'required|integer|min:0',
            'is_active' => 'boolean',
        ]);

        $validated['is_active'] = $request->has('is_active');

        ServiceOffering::create($validated);

        return redirect()->route('admin.service-offerings.index')->with('success', 'Service offering added successfully.');
    }

    public function edit(ServiceOffering $serviceOffering)
    {
        $categories = [
            'test_preparation' => 'Test Preparation',
            'job_abroad' => 'Jobs Abroad',
            'school_scholarship' => 'Schools & Scholarships',
            'benefits' => 'Benefits',
            'countries' => 'Countries',
            'vacancies' => 'Vacancies',
            'partners' => 'Partners',
        ];
        return view('admin.service-offerings.edit', compact('serviceOffering', 'categories'));
    }

    public function update(Request $request, ServiceOffering $serviceOffering)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category' => 'required|string|in:test_preparation,job_abroad,school_scholarship,benefits,countries,vacancies,partners',
            'icon' => 'nullable|string|max:50',
            'order' => 'required|integer|min:0',
            'is_active' => 'boolean',
        ]);

        $validated['is_active'] = $request->has('is_active');

        $serviceOffering->update($validated);

        return redirect()->route('admin.service-offerings.index')->with('success', 'Service offering updated successfully.');
    }

    public function destroy(ServiceOffering $serviceOffering)
    {
        $serviceOffering->delete();
        return redirect()->route('admin.service-offerings.index')->with('success', 'Service offering deleted successfully.');
    }
}
