<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Subscriber;
use Illuminate\Http\Request;

class NewsletterController extends Controller
{
    public function index()
    {
        $subscribers = Subscriber::latest()->paginate(20);
        return view('admin.newsletter.index', compact('subscribers'));
    }

    public function toggleStatus(Subscriber $subscriber)
    {
        $subscriber->update([
            'is_active' => !$subscriber->is_active
        ]);

        return back()->with('success', 'Subscriber status updated successfully.');
    }

    public function destroy(Subscriber $subscriber)
    {
        $subscriber->delete();
        return back()->with('success', 'Subscriber deleted successfully.');
    }
}
