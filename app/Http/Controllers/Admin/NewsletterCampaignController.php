<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Newsletter;
use App\Models\Subscriber;
use App\Mail\NewsletterCampaign;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class NewsletterCampaignController extends Controller
{
    public function index()
    {
        $campaigns = Newsletter::latest()->paginate(10);
        return view('admin.newsletter.campaigns.index', compact('campaigns'));
    }

    public function create()
    {
        return view('admin.newsletter.campaigns.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'subject' => 'required|string|max:255',
            'content' => 'required|string',
        ]);

        $newsletter = Newsletter::create([
            'subject' => $request->subject,
            'content' => $request->content,
        ]);

        if ($request->has('send_now')) {
            $this->sendNewsletter($newsletter);
            return redirect()->route('admin.newsletter.campaigns.index')->with('success', 'Campaign created and sent successfully.');
        }

        return redirect()->route('admin.newsletter.campaigns.index')->with('success', 'Campaign saved as draft.');
    }

    public function send(Newsletter $newsletter)
    {
        if ($newsletter->sent_at) {
             return back()->with('error', 'This campaign has already been sent.');
        }
        
        $this->sendNewsletter($newsletter);
        return back()->with('success', 'Campaign sent successfully.');
    }

    public function destroy(Newsletter $newsletter)
    {
        $newsletter->delete();
        return back()->with('success', 'Campaign deleted successfully.');
    }

    private function sendNewsletter(Newsletter $newsletter)
    {
        $subscribers = Subscriber::where('is_active', true)->get();

        foreach ($subscribers as $subscriber) {
             try {
                Mail::to($subscriber->email)->queue(new NewsletterCampaign($newsletter, $subscriber));
             } catch (\Exception $e) {
                 \Log::error("Failed to queue newsletter for {$subscriber->email}: " . $e->getMessage());
             }
        }

        $newsletter->update(['sent_at' => now()]);
    }
}
