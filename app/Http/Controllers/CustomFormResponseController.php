<?php

namespace App\Http\Controllers;

use App\Models\CustomForm;
use App\Models\CustomFormSubmission;
use Illuminate\Http\Request;

class CustomFormResponseController extends Controller
{
    public function show($slug)
    {
        $customForm = CustomForm::where('slug', $slug)
            ->where('is_active', true)
            ->with(['questions' => function ($q) {
                $q->orderBy('order_index');
            }])
            ->firstOrFail();

        return view('custom_forms.show', compact('customForm'));
    }

    public function store(Request $request, $slug)
    {
        $customForm = CustomForm::where('slug', $slug)
            ->where('is_active', true)
            ->with('questions')
            ->firstOrFail();

        // Build dynamic validation rules based on required questions
        $rules = [];
        $messages = [];

        foreach ($customForm->questions as $question) {
            if ($question->type === 'section_break') {
                continue;
            }

            $fieldName = 'q_' . $question->id;
            $fieldRules = [];

            if ($question->is_required) {
                $fieldRules[] = 'required';
                $messages["{$fieldName}.required"] = "This question is required.";
            }

            if ($question->type == 'checkbox') {
                $fieldRules[] = 'array';
                // If checkbox is required, it must have at least one selection
                if ($question->is_required) {
                    $fieldRules[] = 'min:1';
                }
            } else if ($question->type == 'radio' || $question->type == 'select') {
               // Must be a string (or integer) but roughly scalar
               if ($question->is_required) {
                   $fieldRules[] = 'string';
               }
            } else {
                if ($question->is_required) {
                    $fieldRules[] = 'string';
                }
            }

            if(!empty($fieldRules)){
                 $rules[$fieldName] = implode('|', $fieldRules);
            }
        }

        if(!empty($rules)){
            $request->validate($rules, $messages);
        }

        // Store Submission
        $submission = new CustomFormSubmission();
        $submission->custom_form_id = $customForm->id;
        $submission->ip_address = $request->ip();
        $submission->user_agent = $request->userAgent();
        $submission->save();

        // Store Answers
        foreach ($customForm->questions as $question) {
            if ($question->type === 'section_break') {
                continue;
            }

            $fieldName = 'q_' . $question->id;
            
            if ($request->has($fieldName) && $request->input($fieldName) !== null && $request->input($fieldName) !== '') {
                $rawAnswer = $request->input($fieldName);
                
                // If array (checkboxes), encode to JSON
                if (is_array($rawAnswer)) {
                    $finalAnswer = json_encode($rawAnswer);
                } else {
                    $finalAnswer = $rawAnswer;
                }

                $submission->answers()->create([
                    'custom_form_question_id' => $question->id,
                    'answer' => $finalAnswer,
                ]);
            }
        }

        // Meta Pixel & CAPI Tracking
        try {
            $userData = [];
            foreach ($customForm->questions as $question) {
                $fieldName = 'q_' . $question->id;
                if ($request->has($fieldName) && $request->input($fieldName) !== null) {
                    $ans = trim(is_array($request->input($fieldName)) ? implode(', ', $request->input($fieldName)) : $request->input($fieldName));
                    if (empty($ans)) continue;

                    $labelText = strtolower($question->question_text);

                    if (str_contains($labelText, 'email')) {
                        $userData['email'] = $ans;
                    } elseif (str_contains($labelText, 'phone') || str_contains($labelText, 'mobile') || str_contains($labelText, 'telephone') || str_contains($labelText, 'tel')) {
                        $userData['phone'] = $ans;
                    } elseif (str_contains($labelText, 'first name')) {
                        $userData['first_name'] = $ans;
                    } elseif (str_contains($labelText, 'last name')) {
                        $userData['last_name'] = $ans;
                    } elseif (str_contains($labelText, 'name') && !isset($userData['first_name'])) {
                        $parts = explode(' ', $ans, 2);
                        $userData['first_name'] = $parts[0];
                        $userData['last_name'] = $parts[1] ?? '';
                    } elseif (str_contains($labelText, 'gender') || str_contains($labelText, 'sex')) {
                        $userData['gender'] = $ans;
                    } elseif (str_contains($labelText, 'city')) {
                        $userData['city'] = $ans;
                    } elseif (str_contains($labelText, 'country')) {
                        $userData['country'] = $ans;
                    }
                }
            }

            $eventId = (string) \Illuminate\Support\Str::uuid();
            $customData = [
                'content_name' => $customForm->title,
                'content_category' => 'Custom Form',
            ];

            \App\Jobs\SendMetaConversionsEvent::dispatch('Lead', $eventId, $userData, $customData);

            session()->push('fb_events', [
                'name' => 'Lead',
                'id' => $eventId,
                'data' => $customData,
            ]);
        } catch (\Throwable $e) {
            \Log::error('Meta Pixel/CAPI error in CustomFormResponseController: ' . $e->getMessage());
        }

        return back()->with('form_success', 'Your form has been successfully submitted! Thank you.');
    }
}
