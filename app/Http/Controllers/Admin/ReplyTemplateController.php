<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ReplyTemplate;
use Illuminate\Http\Request;

class ReplyTemplateController extends Controller
{
    public function index()
    {
        $templates = ReplyTemplate::latest()->paginate(10);
        return view('admin.reply_templates.index', compact('templates'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'subject' => 'required|string|max:255',
            'body' => 'required|string',
        ]);

        ReplyTemplate::create($validated);

        return redirect()->route('admin.reply_templates.index')->with('success', 'Template created successfully.');
    }

    public function update(Request $request, ReplyTemplate $replyTemplate)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'subject' => 'required|string|max:255',
            'body' => 'required|string',
        ]);

        $replyTemplate->update($validated);

        return redirect()->route('admin.reply_templates.index')->with('success', 'Template updated successfully.');
    }

    public function destroy(ReplyTemplate $replyTemplate)
    {
        $replyTemplate->delete();
        return redirect()->route('admin.reply_templates.index')->with('success', 'Template deleted successfully.');
    }
}
