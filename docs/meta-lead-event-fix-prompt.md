# Task: Fix Premature Meta Pixel "Lead" Event Firing on Apply Form Modal

## Context

This is a Laravel application for **Rees Consult**, a global mobility/education consulting firm. The site has a multistep "Apply" form that opens in a **modal**. The modal can be triggered two ways:

1. Directly via URL: `https://reesconsult.com/?apply=true` or `https://reesconsult.com/#apply` (used by Meta ad campaigns — this URL is the destination link on active Facebook/Instagram lead-gen ads)
2. Via an "Apply Now" button click elsewhere on the site

We are running Meta (Facebook) ad campaigns that optimize for the **Lead** conversion event, tracked via both the **Meta Pixel** (browser-side, using `fbq()`) and the **Meta Conversions API / CAPI** (server-side).

## The Problem

Meta Ads Manager is reporting leads (e.g., 52 leads at $0.65/lead), but the actual number of completed form submissions / email notifications received is far lower than 52. This is a real business problem: it's throwing off cost-per-lead reporting and could cause Meta's ad delivery algorithm to optimize toward the wrong audience (people who never actually convert).

### Diagnostic evidence already gathered

Using Meta Events Manager's **Test Events** tool, we observed this sequence in the live event log:

```
7:55:26 AM  PageView       (Browser)
7:55:26 AM  Lead           (Browser, Processed)
7:55:31 AM  Lead           (Browser, Processed)
7:55:31 AM  PageView       (Browser, Deduplicated)
8:00:27 AM  Lead           (Browser, Deduplicated)
```

Key observations:
- **Lead fired within 0–5 seconds of PageView.** No human can complete a multistep form (name, contact info, program interest, etc.) in that time. This strongly indicates the Lead event is bound to **modal open**, not **form submission**.
- **Only "Browser" events are visible — no "Server" events appear in the log.** This suggests Conversions API (CAPI) server-side events either aren't implemented, aren't firing, or aren't reaching Meta at all, meaning we are likely relying on browser pixel only (which is also more fragile — ad blockers, iOS ATT, Safari ITP all suppress it).

### Working hypothesis

Because the modal auto-opens whenever the URL contains `?apply=true` or `#apply`, the `fbq('track', 'Lead', ...)` call is very likely placed inside the **shared modal-open function/handler** (used both for the URL-triggered auto-open and the button-click open) rather than inside the **final step's successful submission handler**. This means simply landing on the ad's destination URL — even if the visitor closes the modal immediately without entering anything — fires a Lead event.

## Your Task

### Phase 1 — Investigate and confirm the root cause

1. Search the codebase for all Meta Pixel tracking calls:
   ```
   fbq(
   ```
   Check Blade views (`resources/views/**/*.blade.php`), JS files (`resources/js/**/*.js`), and any shared layout files (`app.blade.php`, `layout.blade.php`, etc.) where the base pixel snippet or an inline `fbq('track', 'Lead', ...)` might live.

2. Locate the function(s) responsible for:
   - Auto-opening the apply modal when the URL contains `?apply=true` or the `#apply` hash (likely in a JS file that runs on page load / `DOMContentLoaded`, checking `window.location.search` or `window.location.hash`)
   - Opening the modal via the "Apply Now" button click handler
   - Handling the final step of the multistep form's submission (likely an AJAX/fetch/axios POST to a Laravel route, e.g. `/apply/submit` or similar — find the actual route name in `routes/web.php`)

3. Confirm whether `fbq('track', 'Lead', ...)` is called in the modal-open path (the bug) versus only in the successful-submission path (correct behavior). Report back exactly which file(s) and line(s) contain the current call(s), and what triggers each one.

4. Search for any existing Conversions API (server-side) implementation — check for Laravel HTTP client calls to `graph.facebook.com`, any `FacebookAds` or CAPI-related package in `composer.json`, config files (`config/services.php`), or `.env` entries referencing `FB_PIXEL_ID`, `FB_ACCESS_TOKEN`, `META_CAPI_TOKEN`, etc. Report whether CAPI is implemented at all, and if so, where and what triggers it.

5. Do not make any code changes yet — first report your findings: which files contain what, and confirm or refute the "fires on modal open" hypothesis with evidence (exact code snippets and line numbers).

### Phase 2 — Implement the fix

Once the root cause is confirmed, implement the following:

#### 2a. Remove the premature Lead event

Remove (or move) any `fbq('track', 'Lead', ...)` call that fires when the modal simply **opens** — whether via URL parameter, hash, or button click. Opening the modal should fire **no Lead event**. If you want visibility into how many people open the modal without completing it, that's a separate, differently-named custom event (e.g., `fbq('trackCustom', 'ApplyModalOpened')`) — do NOT use the standard `Lead` event name for this, since it would still pollute Meta's optimization signal. Only implement this custom event if it's trivial to add; it is optional and lower priority than the core fix.

#### 2b. Fire the Lead event only on confirmed successful submission

In the JS handler for the **final step's form submission**, only call `fbq('track', 'Lead', ...)` **inside the success callback**, after the server has confirmed the submission was actually saved (i.e., after receiving a success response from your Laravel backend — not optimistically before the request completes, and not if the request fails or returns a validation error).

Generate a unique `event_id` for each submission (e.g., via `crypto.randomUUID()` or a UUID library already in use in the project) and pass it into the pixel call:

```js
const eventId = crypto.randomUUID();

fetch('/apply/submit', {
    method: 'POST',
    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken },
    body: JSON.stringify({ ...formData, event_id: eventId })
})
.then(res => res.json())
.then(data => {
    if (data.success) {
        fbq('track', 'Lead', {
            content_name: 'Apply Form Submission'
        }, { eventID: eventId });
        // proceed to thank-you state / redirect
    } else {
        // show validation errors, do NOT fire Lead
    }
})
.catch(err => {
    // handle network/server error, do NOT fire Lead
});
```

Send this same `event_id` along in the request body so it can be reused for the matching server-side CAPI event (required for deduplication — see 2c).

#### 2c. Implement (or fix) the server-side Conversions API call

In the Laravel controller method that handles the apply form's final submission (find or create the appropriate route/controller — check `routes/web.php` for existing routes like `/apply/submit`):

- Only send the CAPI `Lead` event **after** the lead record has been successfully persisted to the database (and, ideally, after any confirmation email has been queued/sent — though don't block the response waiting on email delivery; dispatch it as a queued job if not already).
- Use the **same `event_id`** received from the frontend request, so Meta deduplicates the Browser and Server events into a single Lead rather than double-counting.
- Hash PII fields (email, phone) using SHA-256 in lowercase, trimmed, before sending — per Meta's CAPI requirements. Do not send raw PII.
- Include `client_ip_address` and `client_user_agent` from the incoming request, and `fbc`/`fbp` cookie values if available (read from `$request->cookie('_fbc')` and `$request->cookie('_fbp')`) — these significantly improve Event Match Quality.
- Read the Pixel ID and access token from config/`.env` (do not hardcode them). If these don't already exist as env vars, add `FB_PIXEL_ID` and `FB_CAPI_ACCESS_TOKEN` to `.env.example` and `config/services.php`, and flag clearly in your final report that these need to be populated with real values before this goes live.
- Wrap the CAPI call in a try/catch (or dispatch as a queued job) so that if Meta's API is slow or down, it does not block or break the user's form submission response.

Example structure (adapt to match this project's existing conventions — check if there's already an `FacebookConversionsApiService` or similar; if not, create one under `app/Services/`):

```php
// app/Services/MetaConversionsApiService.php
class MetaConversionsApiService
{
    public function sendLeadEvent(Request $request, string $eventId, string $email, ?string $phone): void
    {
        $payload = [
            'data' => [[
                'event_name' => 'Lead',
                'event_time' => now()->timestamp,
                'event_id' => $eventId,
                'action_source' => 'website',
                'event_source_url' => $request->headers->get('referer'),
                'user_data' => array_filter([
                    'em' => [hash('sha256', strtolower(trim($email)))],
                    'ph' => $phone ? [hash('sha256', preg_replace('/\D/', '', $phone))] : null,
                    'client_ip_address' => $request->ip(),
                    'client_user_agent' => $request->userAgent(),
                    'fbc' => $request->cookie('_fbc'),
                    'fbp' => $request->cookie('_fbp'),
                ]),
            ]],
            'access_token' => config('services.meta.capi_token'),
        ];

        try {
            Http::post(
                'https://graph.facebook.com/v19.0/' . config('services.meta.pixel_id') . '/events',
                $payload
            );
        } catch (\Throwable $e) {
            Log::warning('Meta CAPI Lead event failed to send', ['error' => $e->getMessage()]);
        }
    }
}
```

Call this service from the controller only after the lead is confirmed saved.

#### 2d. Do not remove or alter PageView tracking

The `PageView` event firing on modal-open/page-load pages is expected and correct — leave that as-is. Only the `Lead` event trigger point is the problem.

### Phase 3 — Verify

1. After implementing, walk through the funnel yourself in a local/staging environment with browser devtools open (Network tab, filter for `graph.facebook.com` or check the `fbq` calls via a Pixel Helper–style console log), and confirm:
   - Opening the modal (via `?apply=true`, `#apply`, or button click) fires **no** Lead event.
   - Filling in the form and clicking a "Next" button on intermediate steps fires **no** Lead event.
   - Only completing all steps and receiving a successful server response fires **one** Lead event from the browser.
   - The Laravel backend fires **one** matching server-side CAPI Lead event with the same `event_id` immediately after persisting the lead.
   - Submitting with invalid data (e.g., failed validation) does **not** fire any Lead event on either side.

2. Report back a summary of every file changed, the reasoning, and any `.env` variables that need real values filled in on staging/production before this can be considered complete (e.g., `FB_CAPI_ACCESS_TOKEN`).

3. Explicitly flag anything you were unable to verify (e.g., "I could not confirm this fires correctly against the live Meta Pixel because CAPI requires a valid access token which isn't set locally — recommend testing this in Meta Events Manager's Test Events tool after deploying to staging").

## Constraints & Notes

- Do not change the modal's visual design, the multistep form's UX, or any unrelated tracking (PageView, Subscribe, etc.).
- Follow this project's existing code style and conventions — check how other services/HTTP calls are structured in `app/Services/` or `app/Http/Controllers/` before introducing a new pattern.
- Do not hardcode any Pixel ID, access token, or other secret directly in code — use `.env` / `config/services.php` as described above.
- If you find the codebase already has partial or full CAPI implementation somewhere non-obvious, report it rather than duplicating it.
