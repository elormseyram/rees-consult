<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContactMessage;
use App\Models\ReplyTemplate;
use App\Mail\ContactReply;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class ContactController extends Controller
{
    public function index()
    {
        $messages = ContactMessage::latest()->paginate(10);
        return view('admin.contact.index', compact('messages'));
    }

    public function show(ContactMessage $contactMessage)
    {
        $templates = ReplyTemplate::all();
        return view('admin.contact.show', compact('contactMessage', 'templates'));
    }

    public function reply(Request $request, ContactMessage $contactMessage)
    {
        $request->validate([
            'reply_body' => 'required|string',
        ]);

        // Send email to visitor
        try {
            Mail::to($contactMessage->email)->send(new ContactReply($contactMessage, $request->reply_body));
            
            $contactMessage->update(['is_replied' => true]);
            
            return redirect()->route('admin.contact.show', $contactMessage)->with('success', 'Reply sent successfully.');
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to send reply: ' . $e->getMessage());
        }
    }

    public function destroy(ContactMessage $contactMessage)
    {
        $contactMessage->delete();
        return redirect()->route('admin.contact.index')->with('success', 'Message deleted successfully.');
    }
}
