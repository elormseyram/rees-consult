<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class MetaConversionsService
{
    protected ?string $pixelId;
    protected ?string $accessToken;
    protected ?string $testEventCode;

    public function __construct()
    {
        $this->pixelId = config('services.meta.pixel_id');
        $this->accessToken = config('services.meta.access_token');
        $this->testEventCode = config('services.meta.test_event_code');
    }

    /**
     * Determine if CAPI is active (has necessary credentials).
     */
    public function isActive(): bool
    {
        return !empty($this->pixelId) && !empty($this->accessToken);
    }

    /**
     * Send a standard or custom event to Meta Conversions API.
     *
     * @param string $eventName The name of the event (e.g. Lead, Contact, SubmitApplication, CompleteRegistration, etc.)
     * @param string $eventId A unique identifier used to deduplicate server and browser events
     * @param array $userData Raw user information (email, phone, first_name, last_name, etc.) to be normalized and hashed
     * @param array $customData Key-value pairs of custom event parameters (e.g. value, currency, etc.)
     * @param string $actionSource The source of the event (defaults to 'website')
     * @return bool True if successful, false otherwise
     */
    public function sendEvent(
        string $eventName,
        string $eventId,
        array $userData = [],
        array $customData = [],
        string $actionSource = 'website'
    ): bool {
        if (!$this->isActive()) {
            Log::debug("Meta Conversions API: Service is inactive. Missing configuration.", [
                'event_name' => $eventName,
                'event_id' => $eventId,
            ]);
            return false;
        }

        try {
            $payload = $this->buildPayload($eventName, $eventId, $userData, $customData, $actionSource);

            $url = "https://graph.facebook.com/v20.0/{$this->pixelId}/events?access_token={$this->accessToken}";

            Log::info("Meta Conversions API: Sending event '{$eventName}'", [
                'event_id' => $eventId,
                'payload' => $payload,
            ]);

            $response = Http::timeout(10)->post($url, $payload);

            if ($response->successful()) {
                Log::info("Meta Conversions API: Successfully sent '{$eventName}'", [
                    'event_id' => $eventId,
                    'response' => $response->json(),
                ]);
                return true;
            }

            Log::error("Meta Conversions API Error: Request failed", [
                'status' => $response->status(),
                'body' => $response->body(),
                'event_name' => $eventName,
                'event_id' => $eventId,
            ]);

            return false;
        } catch (\Throwable $e) {
            Log::error("Meta Conversions API Exception: " . $e->getMessage(), [
                'exception' => $e,
                'event_name' => $eventName,
                'event_id' => $eventId,
            ]);
            return false;
        }
    }

    /**
     * Construct the Conversions API event payload.
     */
    public function buildPayload(
        string $eventName,
        string $eventId,
        array $userData = [],
        array $customData = [],
        string $actionSource = 'website'
    ): array {
        $eventData = [
            'event_name' => $eventName,
            'event_time' => time(),
            'event_id' => $eventId,
            'event_source_url' => $userData['event_source_url'] ?? request()->fullUrl(),
            'action_source' => $actionSource,
            'user_data' => $this->formatUserData($userData),
        ];

        if (!empty($customData)) {
            $eventData['custom_data'] = $customData;
        }

        $payload = [
            'data' => [$eventData],
        ];

        // Attach test event code if provided (for debugging in Events Manager)
        if (!empty($this->testEventCode)) {
            $payload['test_event_code'] = $this->testEventCode;
        }

        return $payload;
    }

    /**
     * Format and securely hash user data fields.
     */
    public function formatUserData(array $raw): array
    {
        $userData = [];

        // Hashed Fields (Meta requires SHA-256 of lowercase/trimmed/normalized strings)
        if (!empty($raw['email'])) {
            $userData['em'] = [$this->hashString($raw['email'])];
        }
        if (!empty($raw['phone'])) {
            $userData['ph'] = [$this->hashPhone($raw['phone'])];
        }
        if (!empty($raw['first_name'])) {
            $userData['fn'] = [$this->hashString($raw['first_name'])];
        }
        if (!empty($raw['last_name'])) {
            $userData['ln'] = [$this->hashString($raw['last_name'])];
        }
        if (!empty($raw['gender'])) {
            $userData['ge'] = [$this->hashGender($raw['gender'])];
        }
        if (!empty($raw['city'])) {
            $userData['ct'] = [$this->hashString($raw['city'])];
        }
        if (!empty($raw['state'])) {
            $userData['st'] = [$this->hashString($raw['state'])];
        }
        if (!empty($raw['country'])) {
            $userData['country'] = [$this->hashCountry($raw['country'])];
        }

        // Unhashed Fields (Do not hash client IP, user agent, or cookie values)
        $userData['client_ip_address'] = $raw['client_ip_address'] ?? request()->ip();
        $userData['client_user_agent'] = $raw['client_user_agent'] ?? request()->userAgent();

        $fbp = $raw['fbp'] ?? request()->cookie('_fbp');
        if ($fbp) {
            $userData['fbp'] = $fbp;
        }

        $fbc = $raw['fbc'] ?? request()->cookie('_fbc');
        if ($fbc) {
            $userData['fbc'] = $fbc;
        }

        return $userData;
    }

    /**
     * Standard SHA-256 hashing for basic text inputs.
     */
    public function hashString(string $val): string
    {
        return hash('sha256', strtolower(trim($val)));
    }

    /**
     * Standardize and hash phone numbers.
     */
    public function hashPhone(string $phone): string
    {
        // Strip non-numeric characters (except maybe '+', but CAPI expects only digits including country code)
        $cleaned = preg_replace('/\D/', '', $phone);
        return hash('sha256', $cleaned);
    }

    /**
     * Standardize and hash gender (expects 'm' or 'f').
     */
    public function hashGender(string $gender): string
    {
        $cleaned = strtolower(trim($gender));
        if (str_starts_with($cleaned, 'm') || $cleaned === 'male') {
            $char = 'm';
        } elseif (str_starts_with($cleaned, 'f') || $cleaned === 'female') {
            $char = 'f';
        } else {
            $char = $cleaned;
        }
        return hash('sha256', $char);
    }

    /**
     * Standardize and hash country (expects 2-letter ISO country code).
     */
    public function hashCountry(string $country): string
    {
        $cleaned = strtolower(trim($country));
        
        // Basic mapping for full names if user supplied them
        $countryMap = [
            'ghana' => 'gh',
            'nigeria' => 'ng',
            'united states' => 'us',
            'united kingdom' => 'gb',
            'canada' => 'ca',
            'australia' => 'au',
        ];

        if (array_key_exists($cleaned, $countryMap)) {
            $cleaned = $countryMap[$cleaned];
        }

        return hash('sha256', strlen($cleaned) === 2 ? $cleaned : substr($cleaned, 0, 2));
    }
}
