<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pricing;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ServiceController extends Controller
{
    public function index()
    {
        $services = Service::ordered()->paginate(10);
        return view('admin.services.index', compact('services'));
    }

    public function create()
    {
        return view('admin.services.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title'                  => 'required|string|max:255',
            'subtitle'               => 'nullable|string|max:255',
            'category'               => 'required|string|in:standardized_test,school_application,job_abroad',
            'description'            => 'required|string',
            // price & processing_fee are entered in GHS by the admin
            'price'                  => 'required|numeric|min:0',
            'processing_fee'         => 'nullable|numeric|min:0',
            'country'                => 'nullable|string|max:255',
            'icon'                   => 'nullable|string|max:255',
            'is_active'              => 'boolean',
            'order'                  => 'integer',
            'color'                  => 'nullable|string|max:20',
            'duration'               => 'nullable|string|max:100',
            'class_size'             => 'nullable|string|max:100',
            'features'               => 'nullable|array',
            'features.*'             => 'nullable|string|max:500',
            'why_choose_program'     => 'nullable|array',
            'why_choose_program.*'   => 'nullable|string|max:500',
            // Standardized test tuition tiers (in GHS)
            'tuition_group_online'        => 'nullable|integer|min:0',
            'tuition_group_in_person'     => 'nullable|integer|min:0',
            'tuition_one_on_one_online'   => 'nullable|integer|min:0',
            'tuition_one_on_one_in_person'=> 'nullable|integer|min:0',
        ]);

        // Convert GHS inputs to USD for storage (1 USD = 12 GHS)
        $validated['price']          = round(($validated['price'] ?? 0) / 12, 2);
        $validated['processing_fee'] = isset($validated['processing_fee'])
            ? round($validated['processing_fee'] / 12, 2)
            : null;

        $validated['slug']      = Str::slug($validated['title']);
        $validated['is_active'] = $request->has('is_active');

        if (isset($validated['features'])) {
            $validated['features'] = array_values(array_filter($validated['features'], fn($item) => !is_null($item) && trim($item) !== ''));
        }
        if (isset($validated['why_choose_program'])) {
            $validated['why_choose_program'] = array_values(array_filter($validated['why_choose_program'], fn($item) => !is_null($item) && trim($item) !== ''));
        }

        // Remove tuition keys before creating the Service record
        $tuitionData = [
            'group_online'        => $request->input('tuition_group_online'),
            'group_in_person'     => $request->input('tuition_group_in_person'),
            'one_on_one_online'   => $request->input('tuition_one_on_one_online'),
            'one_on_one_in_person'=> $request->input('tuition_one_on_one_in_person'),
        ];
        unset(
            $validated['tuition_group_online'],
            $validated['tuition_group_in_person'],
            $validated['tuition_one_on_one_online'],
            $validated['tuition_one_on_one_in_person']
        );

        $service = Service::create($validated);

        // If standardized test, upsert the Pricing record
        if ($service->category === 'standardized_test' && array_filter($tuitionData, fn($v) => !is_null($v))) {
            $keyword = strtoupper(explode(' ', trim($service->title))[0]);
            Pricing::updateOrCreate(
                ['test_name' => $keyword],
                array_filter($tuitionData, fn($v) => !is_null($v))
            );
        }

        return redirect()->route('admin.services.index')->with('success', 'Service created successfully.');
    }

    public function edit(Service $service)
    {
        // Load the matching Pricing row for standardized tests
        $pricing = null;
        if ($service->category === 'standardized_test') {
            $keyword = strtoupper(explode(' ', trim($service->title))[0]);
            $pricing = Pricing::where('test_name', $keyword)->first();
        }

        return view('admin.services.edit', compact('service', 'pricing'));
    }

    public function update(Request $request, Service $service)
    {
        $validated = $request->validate([
            'title'                  => 'required|string|max:255',
            'subtitle'               => 'nullable|string|max:255',
            'category'               => 'required|string|in:standardized_test,school_application,job_abroad',
            'description'            => 'required|string',
            // price & processing_fee are entered in GHS by the admin
            'price'                  => 'required|numeric|min:0',
            'processing_fee'         => 'nullable|numeric|min:0',
            'country'                => 'nullable|string|max:255',
            'icon'                   => 'nullable|string|max:255',
            'is_active'              => 'boolean',
            'order'                  => 'integer',
            'color'                  => 'nullable|string|max:20',
            'duration'               => 'nullable|string|max:100',
            'class_size'             => 'nullable|string|max:100',
            'features'               => 'nullable|array',
            'features.*'             => 'nullable|string|max:500',
            'why_choose_program'     => 'nullable|array',
            'why_choose_program.*'   => 'nullable|string|max:500',
            // Standardized test tuition tiers (in GHS)
            'tuition_group_online'        => 'nullable|integer|min:0',
            'tuition_group_in_person'     => 'nullable|integer|min:0',
            'tuition_one_on_one_online'   => 'nullable|integer|min:0',
            'tuition_one_on_one_in_person'=> 'nullable|integer|min:0',
        ]);

        // Convert GHS inputs to USD for storage (1 USD = 12 GHS)
        $validated['price']          = round(($validated['price'] ?? 0) / 12, 2);
        $validated['processing_fee'] = isset($validated['processing_fee'])
            ? round($validated['processing_fee'] / 12, 2)
            : null;

        $validated['slug']      = Str::slug($validated['title']);
        $validated['is_active'] = $request->has('is_active');

        if (isset($validated['features'])) {
            $validated['features'] = array_values(array_filter($validated['features'], fn($item) => !is_null($item) && trim($item) !== ''));
        }
        if (isset($validated['why_choose_program'])) {
            $validated['why_choose_program'] = array_values(array_filter($validated['why_choose_program'], fn($item) => !is_null($item) && trim($item) !== ''));
        }

        // Capture tuition data before removing from validated array
        $tuitionData = [
            'group_online'        => $request->input('tuition_group_online'),
            'group_in_person'     => $request->input('tuition_group_in_person'),
            'one_on_one_online'   => $request->input('tuition_one_on_one_online'),
            'one_on_one_in_person'=> $request->input('tuition_one_on_one_in_person'),
        ];
        unset(
            $validated['tuition_group_online'],
            $validated['tuition_group_in_person'],
            $validated['tuition_one_on_one_online'],
            $validated['tuition_one_on_one_in_person']
        );

        $service->update($validated);

        // If standardized test, upsert the Pricing record
        if ($service->category === 'standardized_test' && array_filter($tuitionData, fn($v) => !is_null($v))) {
            $keyword = strtoupper(explode(' ', trim($service->title))[0]);
            Pricing::updateOrCreate(
                ['test_name' => $keyword],
                array_filter($tuitionData, fn($v) => !is_null($v))
            );
        }

        return redirect()->route('admin.services.index')->with('success', 'Service updated successfully.');
    }

    public function destroy(Service $service)
    {
        $service->delete();
        return redirect()->route('admin.services.index')->with('success', 'Service deleted successfully.');
    }
}
