<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CustomForm;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

class CustomFormController extends Controller
{
    public function index()
    {
        $forms = CustomForm::withCount(['submissions', 'questions'])->latest()->paginate(12);
        return view('admin.custom_forms.index', compact('forms'));
    }

    public function create()
    {
        return view('admin.custom_forms.form');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'bg_image' => 'nullable|image|max:5120',
            'is_active' => 'boolean',
            'questions_json' => 'required|string',
        ]);

        $customForm = new CustomForm();
        $customForm->title = $request->title;
        // Generate a unique slug
        $slug = Str::slug($request->title);
        $count = CustomForm::where('slug', 'LIKE', "{$slug}%")->count();
        if ($count > 0) {
            $slug .= '-' . ($count + 1);
        }
        $customForm->slug = $slug;
        $customForm->description = $request->description;
        $customForm->is_active = $request->has('is_active');

        if ($request->hasFile('bg_image')) {
            $customForm->bg_image = $request->file('bg_image')->store('custom_forms', 'public');
        }

        $customForm->save();

        $this->syncQuestions($customForm, json_decode($request->questions_json, true));

        return redirect()->route('admin.custom_forms.index')->with('success', 'Form created successfully. People can access it via the public link.');
    }

    public function edit(CustomForm $customForm)
    {
        $customForm->load('questions');
        return view('admin.custom_forms.form', compact('customForm'));
    }

    public function update(Request $request, CustomForm $customForm)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'bg_image' => 'nullable|image|max:5120',
            'is_active' => 'boolean',
            'questions_json' => 'required|string',
        ]);

        $customForm->title = $request->title;
        $customForm->description = $request->description;
        $customForm->is_active = $request->has('is_active');

        if ($request->hasFile('bg_image')) {
            if ($customForm->bg_image) {
                Storage::disk('public')->delete($customForm->bg_image);
            }
            $customForm->bg_image = $request->file('bg_image')->store('custom_forms', 'public');
        }

        $customForm->save();

        $this->syncQuestions($customForm, json_decode($request->questions_json, true));

        return redirect()->route('admin.custom_forms.index')->with('success', 'Form updated successfully.');
    }

    public function destroy(CustomForm $customForm)
    {
        if ($customForm->bg_image) {
            Storage::disk('public')->delete($customForm->bg_image);
        }
        $customForm->delete();

        return redirect()->route('admin.custom_forms.index')->with('success', 'Form deleted successfully.');
    }

    public function submissions(CustomForm $customForm)
    {
        $customForm->load(['questions' => function ($q) {
            $q->orderBy('order_index');
        }]);
        $submissions = $customForm->submissions()->with('answers')->paginate(20);

        return view('admin.custom_forms.submissions', compact('customForm', 'submissions'));
    }

    public function export(CustomForm $customForm)
    {
        $customForm->load(['questions' => function ($q) {
            $q->orderBy('order_index');
        }]);
        
        $submissions = $customForm->submissions()->with('answers')->get();

        $filename = "submissions-" . Str::slug($customForm->title) . "-" . date('Y-md-His') . ".csv";

        $headers = array(
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=$filename",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        );

        $columns = ['ID', 'Date', 'IP Address'];
        foreach ($customForm->questions as $question) {
            $columns[] = $question->question_text;
        }

        $callback = function() use($submissions, $columns, $customForm) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);

            foreach ($submissions as $submission) {
                $row = [
                    $submission->id,
                    $submission->created_at->format('Y-m-d H:i:s'),
                    $submission->ip_address,
                ];

                foreach ($customForm->questions as $question) {
                    $answer = $submission->answers->where('custom_form_question_id', $question->id)->first();
                    $answerText = '';
                    if ($answer) {
                        // If it's a JSON array (e.g. checkboxes), decode and implode
                        $decoded = json_decode($answer->answer, true);
                        if (is_array($decoded)) {
                            $answerText = implode(', ', $decoded);
                        } else {
                            $answerText = $answer->answer;
                        }
                    }
                    $row[] = $answerText;
                }

                fputcsv($file, $row);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    private function syncQuestions(CustomForm $form, $questionsData)
    {
        if (!is_array($questionsData)) {
            $questionsData = [];
        }

        $existingIds = [];

        foreach ($questionsData as $index => $qData) {
            $options = null;
            if (in_array($qData['type'], ['radio', 'checkbox', 'select']) && isset($qData['options'])) {
                // Ensure options is an array (split by newline if coming as string from a textarea maybe)
                if (is_string($qData['options'])) {
                    $options = array_map('trim', explode("\n", $qData['options']));
                    $options = array_filter($options);
                } else if (is_array($qData['options'])) {
                    $options = $qData['options'];
                }
            }

            $question = $form->questions()->updateOrCreate(
                ['id' => $qData['id'] ?? null],
                [
                    'type' => $qData['type'],
                    'question_text' => $qData['question_text'],
                    'is_required' => $qData['is_required'] ?? false,
                    'options' => $options,
                    'order_index' => $index,
                ]
            );

            $existingIds[] = $question->id;
        }

        // Delete questions that were removed
        $form->questions()->whereNotIn('id', $existingIds)->delete();
    }
}
