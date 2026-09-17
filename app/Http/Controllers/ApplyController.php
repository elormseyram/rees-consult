<?php

namespace App\Http\Controllers;

use App\Mail\NewApplicationLead;
use App\Models\Lead;
use App\Services\AutomatedMailer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class ApplyController extends Controller
{
    /**
     * Dedicated "/apply" ads landing page. Reuses the same Apply Now modal
     * and qualification process as the home page.
     */
    public function index()
    {
        $services = \App\Models\Service::active()->ordered()->take(6)->get();
        $testimonials = \App\Models\Testimonial::active()->whereNull('youtube_video_url')->ordered()->take(3)->get();

        return view('apply', compact('services', 'testimonials'));
    }

    /**
     * Handle a multi-step "Apply Now" qualification submission.
     *
     * Captures the lead, scores it BANT-style (Budget, Authority/commitment,
     * then emails a full transcript + summary to the team.
     */
    public function store(Request $request)
    {
        $reason = null;
        if ($this->looksLikeSpam($request, $reason)) {
            Log::info('Apply spam blocked', [
                'ip'     => $request->ip(),
                'email'  => $request->input('email'),
                'reason' => $reason,
                'fbclid' => $request->input('fbclid'),
            ]);

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Thank you! Your application has been received. Our team will reach out shortly.',
                ]);
            }

            return back()->with('success', 'Thank you! Your application has been received.');
        }

        $validated = $request->validate([
            // Step 1: goal
            'goal'                => 'required|string|in:test_prep,study_abroad,work_abroad',

            // Step 4: demographics (required)
            'sex'                 => 'required|string|in:male,female,prefer_not',
            'age_range'           => 'required|string|in:under_18,18_24,25_34,35_44,45_plus',
            'country'             => 'required|string|max:120',
            'city'                => 'required|string|max:120',
            'nationality'         => 'required|string|max:120',

            // Step 2: tailored details (all optional/contextual)
            'test'                => 'nullable|string|max:255',
            'target_score'        => 'nullable|string|max:255',
            'exam_deadline'       => 'nullable|string|max:255',
            'education_level'     => 'nullable|string|max:255',
            'course_of_interest'  => 'nullable|string|max:255',
            'target_countries'    => 'nullable|string|max:255',
            'highest_qualification' => 'nullable|string|max:255',
            'has_passport'        => 'nullable|string|max:50',
            'experience_years'    => 'nullable|string|max:50',
            'current_occupation'  => 'nullable|string|max:255',
            'target_sector'       => 'nullable|string|max:255',

            // Step 3: BANT qualification
            'timeline'            => 'required|string|in:within_1_month,1_3_months,3_6_months,6_12_months,exploring',
            'budget_status'       => 'required|string|in:ready_now,within_weeks,saving,not_yet',
            'funding_source'      => 'required|string|in:self,sponsor,loan,scholarship_hope,not_sure',
            'commitment'          => 'required|string|in:pay_now,few_weeks,researching,curious',

            // Step 4: contact
            'first_name'          => 'required|string|max:255',
            'last_name'           => 'required|string|max:255',
            'email'               => 'required|email|max:255',
            'phone'               => 'required|string|max:30',
            'contact_method'      => 'nullable|string|max:50',
            'notes'               => 'nullable|string|max:2000',
        ]);

        $assessment = $this->assess($validated);
        $transcript = $this->buildTranscript($validated);

        // 1) Persist the lead first — this is the source of truth and must not
        //    be lost even if the notification email fails.
        try {
            Lead::create(array_merge($validated, [
                'rating'        => $assessment['rating'],
                'score'         => $assessment['total'],
                'willing'       => $assessment['willing'],
                'able'          => $assessment['able'],
                'willing_score' => $assessment['willing_score'],
                'able_score'    => $assessment['able_score'],
                'verdict'       => $assessment['verdict'],
                'transcript'    => $transcript,
                'status'        => 'new',
            ]));
        } catch (\Throwable $e) {
            Log::error('Apply lead save failed: ' . $e->getMessage());

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'We could not submit your application right now. Please try again or contact us directly.',
                ], 500);
            }

            return back()->with('error', 'We could not submit your application right now. Please try again.');
        }

        // 2) Notify the team. Email failure is logged but does not fail the
        //    request, since the lead is already safely stored.
        try {
            Mail::to('info@reesconsult.com')
                ->cc('reesconsult1@gmail.com')
                ->send(new NewApplicationLead($validated, $assessment, $transcript));
        } catch (\Throwable $e) {
            Log::error('Apply lead email failed: ' . $e->getMessage());
        }

        // 3) Welcome the lead and set the expectation that we will call.
        //    AutomatedMailer swallows its own failures.
        app(AutomatedMailer::class)->send('lead.welcome', $validated['email'], [
            'first_name' => $validated['first_name'],
            'last_name'  => $validated['last_name'],
            'full_name'  => trim($validated['first_name'] . ' ' . $validated['last_name']),
            'email'      => $validated['email'],
            'phone'      => $validated['phone'],
            'goal'       => [
                'test_prep'    => 'Test Prep',
                'study_abroad' => 'Study Abroad',
                'work_abroad'  => 'Work Abroad',
            ][$validated['goal']] ?? $validated['goal'],
        ]);

        // Meta Pixel & CAPI Tracking
        $fbEvents = [];
        try {
            $eventIdSubmit = (string) \Illuminate\Support\Str::uuid();
            $eventIdLead = $request->input('event_id') ?: (string) \Illuminate\Support\Str::uuid();

            $userData = [
                'email' => $validated['email'],
                'phone' => $validated['phone'],
                'first_name' => $validated['first_name'],
                'last_name' => $validated['last_name'],
                'gender' => $validated['sex'] ?? null,
                'city' => $validated['city'] ?? null,
                'country' => $validated['country'] ?? null,
            ];

            $customData = [
                'content_category' => 'Application',
                'content_name' => $validated['goal'] ?? null,
            ];

            \App\Jobs\SendMetaConversionsEvent::dispatch('SubmitApplication', $eventIdSubmit, $userData, $customData);
            \App\Jobs\SendMetaConversionsEvent::dispatch('Lead', $eventIdLead, $userData, $customData);

            $fbEvents = [
                [
                    'name' => 'SubmitApplication',
                    'id' => $eventIdSubmit,
                    'data' => $customData,
                ],
                [
                    'name' => 'Lead',
                    'id' => $eventIdLead,
                    'data' => $customData,
                ],
            ];

            if (!$request->expectsJson()) {
                foreach ($fbEvents as $event) {
                    session()->push('fb_events', $event);
                }
            }
        } catch (\Throwable $e) {
            Log::error('Meta Pixel/CAPI error in ApplyController: ' . $e->getMessage());
        }

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Thank you! Your application has been received. Our team will reach out shortly.',
                'fb_events' => $fbEvents,
            ]);
        }

        return back()->with('success', 'Thank you! Your application has been received. Our team will reach out shortly.');
    }

    /**
     * Multi-layer spam check for the Apply Now form. Returns true if the
     * submission looks automated. Layers are deliberately independent so a bot
     * has to defeat all of them: a hidden honeypot, a JS-set proof token, a
     * time trap, and gibberish heuristics on the free-text fields.
     */
    private function looksLikeSpam(Request $request, &$reason = null): bool
    {
        // 1) Honeypot — a hidden field humans never see or fill.
        if (filled($request->input('website'))) {
            $reason = 'Honeypot field filled';
            return true;
        }

        // If they come from Facebook Ads (has fbclid), we bypass JS and time checks
        // to guarantee zero false positives for real ad traffic.
        $isAdTraffic = $request->filled('fbclid');

        if (!$isAdTraffic) {
            // 2) JS proof — our script sets this from the live CSRF token. Bots that
            //    POST directly without running JavaScript cannot reproduce it.
            $expected = substr(base64_encode(csrf_token()), 0, 16);
            if (! hash_equals($expected, (string) $request->input('js_token'))) {
                $reason = 'JS token mismatch or missing';
                return true;
            }

            // 3) Time trap — the 5-step form can't be completed by a human in a few
            //    seconds. Missing timing means JS never ran (also a bot).
            $elapsed = (int) $request->input('elapsed_ms');
            if ($elapsed <= 0 || $elapsed < 3000) {
                $reason = "Time trap triggered ({$elapsed}ms)";
                return true;
            }
        }

        // 4) Gibberish heuristics on the human-entered text fields.
        foreach (['first_name', 'last_name', 'city', 'country', 'nationality', 'notes'] as $field) {
            if ($this->isGibberish((string) $request->input($field))) {
                $reason = "Gibberish in field '{$field}'";
                return true;
            }
        }

        return false;
    }

    /**
     * Heuristic detector for machine-generated random strings (e.g. "xqhsixpveu").
     * Conservative on purpose — only flags clear gibberish so real, unusual names
     * are not blocked. A token trips it on a long consonant run or near-zero vowels.
     */
    private function isGibberish(string $value): bool
    {
        $value = trim($value);
        if ($value === '') {
            return false;
        }

        foreach (preg_split('/\s+/', $value) as $token) {
            $token = strtolower(preg_replace('/[^a-z]/i', '', $token));
            $len = strlen($token);
            if ($len < 7) {
                continue; // too short to judge reliably
            }

            // Run of 5+ consonants in a row — vanishingly rare in real words/names.
            if (preg_match('/[bcdfghjklmnpqrstvwxyz]{5,}/', $token)) {
                return true;
            }

            // Very low vowel density over a long token.
            $ratio = preg_match_all('/[aeiou]/', $token) / $len;
            if ($len >= 10 && $ratio < 0.25) {
                return true;
            }

            // A 4+ consonant run combined with low vowel density — two weak
            // signals together, conservative enough to leave real names alone.
            if ($len >= 9 && $ratio < 0.30 && preg_match('/[bcdfghjklmnpqrstvwxyz]{4,}/', $token)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Score the lead and decide whether they appear willing & able to buy.
     */
    private function assess(array $data): array
    {
        $timelineScores   = ['within_1_month' => 3, '1_3_months' => 2, '3_6_months' => 1, '6_12_months' => 0, 'exploring' => 0];
        $budgetScores     = ['ready_now' => 3, 'within_weeks' => 2, 'saving' => 1, 'not_yet' => 0];
        $fundingScores    = ['self' => 3, 'sponsor' => 2, 'loan' => 1, 'scholarship_hope' => 0, 'not_sure' => 0];
        $commitmentScores = ['pay_now' => 3, 'few_weeks' => 2, 'researching' => 1, 'curious' => 0];

        $timeline   = $timelineScores[$data['timeline']] ?? 0;
        $budget     = $budgetScores[$data['budget_status']] ?? 0;
        $funding    = $fundingScores[$data['funding_source']] ?? 0;
        $commitment = $commitmentScores[$data['commitment']] ?? 0;

        // Willing = intent (commitment + timeline). Able = capacity (budget + funding).
        $willingScore = $commitment + $timeline; // 0-6
        $ableScore    = $budget + $funding;       // 0-6
        $total        = $willingScore + $ableScore; // 0-12

        $willing = $willingScore >= 4;
        $able    = $ableScore >= 4;

        if ($total >= 9) {
            $rating = 'HOT';
        } elseif ($total >= 5) {
            $rating = 'WARM';
        } else {
            $rating = 'COLD';
        }

        if ($willing && $able) {
            $verdict = 'This lead appears WILLING and ABLE to buy. Prioritise a fast, personal follow-up.';
        } elseif ($able && ! $willing) {
            $verdict = 'This lead appears ABLE to pay but not yet committed (low urgency). Nurture and build urgency.';
        } elseif ($willing && ! $able) {
            $verdict = 'This lead appears WILLING/eager but funding is unclear. Qualify the budget before investing heavy time.';
        } else {
            $verdict = 'Low buying signals (likely just exploring). Add to nurture list; do not over-invest yet.';
        }

        return [
            'rating'        => $rating,
            'total'         => $total,
            'willing'       => $willing,
            'able'          => $able,
            'willing_score' => $willingScore,
            'able_score'    => $ableScore,
            'verdict'       => $verdict,
        ];
    }

    /**
     * Build a human-readable Q&A transcript from the submission.
     */
    private function buildTranscript(array $data): array
    {
        $goalLabels = [
            'test_prep'    => 'Test Preparation (IELTS / GRE / SAT / TOEFL, etc.)',
            'study_abroad' => 'Study Abroad (School Application)',
            'work_abroad'  => 'Work Abroad (Job Placement)',
        ];
        $timelineLabels = [
            'within_1_month' => 'Within 1 month',
            '1_3_months'     => '1 – 3 months',
            '3_6_months'     => '3 – 6 months',
            '6_12_months'    => '6 – 12 months',
            'exploring'      => 'Just exploring / no set date',
        ];
        $budgetLabels = [
            'ready_now'    => 'Yes — funds are ready now',
            'within_weeks' => 'Yes — funds ready within a few weeks',
            'saving'       => 'Still saving toward it',
            'not_yet'      => 'No budget set aside yet',
        ];
        $fundingLabels = [
            'self'             => 'Self / family savings',
            'sponsor'          => 'Sponsor (employer / relative)',
            'loan'             => 'Loan',
            'scholarship_hope' => 'Hoping for a scholarship',
            'not_sure'         => 'Not sure yet',
        ];
        $commitmentLabels = [
            'pay_now'     => 'Ready to enrol & pay now',
            'few_weeks'   => 'Ready within a few weeks',
            'researching' => 'Researching options',
            'curious'     => 'Just curious for now',
        ];
        $sexLabels = [
            'male'       => 'Male',
            'female'     => 'Female',
            'prefer_not' => 'Prefer not to say',
        ];
        $ageLabels = [
            'under_18' => 'Under 18',
            '18_24'    => '18 – 24',
            '25_34'    => '25 – 34',
            '35_44'    => '35 – 44',
            '45_plus'  => '45+',
        ];

        $rows = [];
        $rows['Goal'] = $goalLabels[$data['goal']] ?? $data['goal'];

        // Tailored details
        if ($data['goal'] === 'test_prep') {
            $rows['Test of interest'] = $data['test'] ?? '—';
            $rows['Target score']     = $data['target_score'] ?? '—';
            $rows['Exam date / deadline'] = $data['exam_deadline'] ?? '—';
        } elseif ($data['goal'] === 'study_abroad') {
            $rows['Level of study']        = $data['education_level'] ?? '—';
            $rows['Course of interest']    = $data['course_of_interest'] ?? '—';
            $rows['Target countries']      = $data['target_countries'] ?? '—';
            $rows['Highest qualification'] = $data['highest_qualification'] ?? '—';
            $rows['Has passport']          = $data['has_passport'] ?? '—';
        } elseif ($data['goal'] === 'work_abroad') {
            $rows['Target country / sector'] = trim(($data['target_countries'] ?? '') . ' ' . ($data['target_sector'] ?? '')) ?: '—';
            $rows['Years of experience']     = $data['experience_years'] ?? '—';
            $rows['Current occupation']      = $data['current_occupation'] ?? '—';
            $rows['Highest qualification']   = $data['highest_qualification'] ?? '—';
            $rows['Has passport']            = $data['has_passport'] ?? '—';
        }

        // BANT
        $rows['Timeline to start']  = $timelineLabels[$data['timeline']] ?? $data['timeline'];
        $rows['Budget readiness']   = $budgetLabels[$data['budget_status']] ?? $data['budget_status'];
        $rows['Funding source']     = $fundingLabels[$data['funding_source']] ?? $data['funding_source'];
        $rows['Commitment level']   = $commitmentLabels[$data['commitment']] ?? $data['commitment'];

        // Contact
        $rows['Name']            = trim($data['first_name'] . ' ' . $data['last_name']);
        $rows['Email']           = $data['email'];
        $rows['Phone']           = $data['phone'];
        $rows['Preferred contact'] = $data['contact_method'] ?? '—';

        // Demographics
        $rows['Sex']         = $sexLabels[$data['sex'] ?? ''] ?? ($data['sex'] ?? '—');
        $rows['Age range']   = $ageLabels[$data['age_range'] ?? ''] ?? ($data['age_range'] ?? '—');
        $rows['Country']     = $data['country'] ?? '—';
        $rows['City']        = $data['city'] ?? '—';
        $rows['Nationality'] = $data['nationality'] ?? '—';

        if (! empty($data['notes'])) {
            $rows['Notes'] = $data['notes'];
        }

        return $rows;
    }
}
