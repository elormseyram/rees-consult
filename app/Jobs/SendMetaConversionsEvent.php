<?php

namespace App\Jobs;

use App\Services\MetaConversionsService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendMetaConversionsEvent implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected string $eventName;
    protected string $eventId;
    protected array $userData;
    protected array $customData;
    protected string $actionSource;

    /**
     * Create a new job instance.
     */
    public function __construct(
        string $eventName,
        string $eventId,
        array $userData = [],
        array $customData = [],
        string $actionSource = 'website'
    ) {
        $this->eventName = $eventName;
        $this->eventId = $eventId;
        $this->customData = $customData;
        $this->actionSource = $actionSource;

        // Capture request context during construction (which happens on the main HTTP thread)
        // so that it is serialized and available when run in the queue worker.
        $this->userData = array_merge([
            'client_ip_address' => request()->ip(),
            'client_user_agent' => request()->userAgent(),
            'fbp'               => request()->cookie('_fbp'),
            'fbc'               => request()->cookie('_fbc'),
            'event_source_url'  => request()->fullUrl(),
        ], $userData);
    }

    /**
     * Execute the job.
     */
    public function handle(MetaConversionsService $service): void
    {
        $service->sendEvent(
            $this->eventName,
            $this->eventId,
            $this->userData,
            $this->customData,
            $this->actionSource
        );
    }
}
