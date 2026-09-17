<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ServiceSignup;
use Illuminate\Http\Request;

class ServiceSignupController extends Controller
{
    public function index(Request $request)
    {
        $query = ServiceSignup::with('service')->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $signups = $query->paginate(15);
        return view('admin.service-signups.index', compact('signups'));
    }

    public function show(ServiceSignup $serviceSignup)
    {
        $serviceSignup->load('service');
        return view('admin.service-signups.show', compact('serviceSignup'));
    }

    public function updateStatus(Request $request, ServiceSignup $serviceSignup)
    {
        $request->validate([
            'status' => 'required|in:pending,contacted,active,completed',
        ]);

        $serviceSignup->update([
            'status' => $request->status,
            'notes' => $request->notes ?? $serviceSignup->notes,
        ]);

        return back()->with('success', 'Signup status updated to ' . ucfirst($request->status) . '.');
    }

    public function destroy(ServiceSignup $serviceSignup)
    {
        $serviceSignup->delete();
        return redirect()->route('admin.service-signups.index')->with('success', 'Signup record deleted.');
    }
}
