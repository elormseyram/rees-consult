<?php

namespace App\Console\Commands;

use App\Models\Testimonial;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class SyncYouTubeTestimonials extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'youtube:sync-testimonials';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fetch video testimonials from a YouTube channel/playlist and store them as draft testimonials. Detects Shorts (≤60s) and prevents duplicates.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $apiKey    = config('services.youtube.key');
        $channelId = config('services.youtube.channel_id');
        $playlistId= config('services.youtube.playlist_id');

        if (!$apiKey) {
            $this->error('YOUTUBE_API_KEY is not configured in services/env.');
            return Command::FAILURE;
        }

        // 1. Resolve playlist ID from channel ID if not directly specified
        if (!$playlistId) {
            if (!$channelId) {
                $this->error('Neither YOUTUBE_PLAYLIST_ID nor YOUTUBE_CHANNEL_ID is configured.');
                return Command::FAILURE;
            }

            $this->info("Resolving uploads playlist for Channel ID: {$channelId}");

            $channelResponse = Http::get('https://www.googleapis.com/youtube/v3/channels', [
                'part' => 'contentDetails',
                'id'   => $channelId,
                'key'  => $apiKey,
            ]);

            if ($channelResponse->failed()) {
                $this->error('Failed to communicate with YouTube API to retrieve channel info.');
                return Command::FAILURE;
            }

            $channelData = $channelResponse->json();
            if (empty($channelData['items'])) {
                $this->error('No channel found for the given YouTube Channel ID.');
                return Command::FAILURE;
            }

            $playlistId = $channelData['items'][0]['contentDetails']['relatedPlaylists']['uploads'] ?? null;
            if (!$playlistId) {
                $this->error('Failed to resolve the uploads playlist from channel details.');
                return Command::FAILURE;
            }

            $this->info("Resolved Uploads Playlist ID: {$playlistId}");
        }

        // 2. Fetch items from the playlist (up to 50)
        $this->info("Fetching videos from playlist: {$playlistId}");

        $playlistResponse = Http::get('https://www.googleapis.com/youtube/v3/playlistItems', [
            'part'       => 'snippet',
            'playlistId' => $playlistId,
            'key'        => $apiKey,
            'maxResults' => 50,
        ]);

        if ($playlistResponse->failed()) {
            $this->error('Failed to fetch videos from the playlist.');
            return Command::FAILURE;
        }

        $playlistData = $playlistResponse->json();
        $items        = $playlistData['items'] ?? [];

        if (empty($items)) {
            $this->warn('No videos found in the specified YouTube playlist.');
            return Command::SUCCESS;
        }

        // 3. Collect snippet data keyed by videoId
        $videoSnippets = [];
        foreach ($items as $item) {
            $snippet = $item['snippet'] ?? [];
            $videoId = $snippet['resourceId']['videoId'] ?? null;
            if ($videoId) {
                $videoSnippets[$videoId] = [
                    'title'       => $snippet['title'] ?? '',
                    'description' => $snippet['description'] ?? '',
                ];
            }
        }

        if (empty($videoSnippets)) {
            $this->warn('No valid video IDs found in playlist items.');
            return Command::SUCCESS;
        }

        // 4. Batch fetch video durations to detect Shorts (≤ 60 seconds)
        $this->info('Fetching video durations to detect YouTube Shorts...');
        $videoIds     = array_keys($videoSnippets);
        $durationMap  = $this->fetchDurations($videoIds, $apiKey);

        // 5. Process each video
        $syncedCount   = 0;
        $skippedCount  = 0;
        $updatedCount  = 0;
        $shortsCount   = 0;

        foreach ($videoSnippets as $videoId => $snippet) {
            $title       = $snippet['title'];
            $description = $snippet['description'];
            $videoUrl    = 'https://www.youtube.com/watch?v=' . $videoId;
            $isShort     = ($durationMap[$videoId] ?? 9999) <= 60;

            if ($isShort) {
                $shortsCount++;
            }

            // ── Deduplication check using the dedicated youtube_video_id column ──
            $existing = Testimonial::where('youtube_video_id', $videoId)->first();

            // Fallback: legacy records might not have youtube_video_id populated yet
            if (!$existing) {
                $existing = Testimonial::where('youtube_video_url', 'like', '%' . $videoId . '%')->first();
            }

            if ($existing) {
                $dirty = false;

                // Back-fill youtube_video_id if missing (legacy record)
                if (!$existing->youtube_video_id) {
                    $existing->youtube_video_id = $videoId;
                    $dirty = true;
                }

                // Back-fill / correct is_short flag
                if ($existing->is_short !== $isShort) {
                    $existing->is_short = $isShort;
                    $dirty = true;
                }

                // Update title only if the record has never been manually edited
                if ($existing->updated_at->eq($existing->created_at) && $existing->name !== $title) {
                    $existing->name    = $title;
                    $existing->timestamps = false; // preserve updated_at == created_at sentinel
                    $dirty = true;
                }

                if ($dirty) {
                    $existing->save();
                    $updatedCount++;
                    $this->line("  Updated existing: [{$videoId}] " . ($isShort ? '📱 Short' : '🎬 Video'));
                }

                $skippedCount++;
                continue;
            }

            // ── Create new draft testimonial ──
            Testimonial::create([
                'name'             => $title,
                'role'             => 'YouTube Review',
                'message'          => $description,
                'youtube_video_url'=> $videoUrl,
                'youtube_video_id' => $videoId,
                'is_short'         => $isShort,
                'rating'           => 5,
                'is_active'        => false,
                'order'            => 0,
            ]);

            $syncedCount++;
            $this->line("  Synced: [{$videoId}] " . ($isShort ? '📱 Short' : '🎬 Video') . " – {$title}");
        }

        $this->newLine();
        $this->info("✅ Sync complete.");
        $this->table(
            ['Metric', 'Count'],
            [
                ['New videos synced', $syncedCount],
                ['  of which Shorts', $shortsCount],
                ['Existing (skipped)', $skippedCount],
                ['Back-filled/updated', $updatedCount],
            ]
        );

        return Command::SUCCESS;
    }

    /**
     * Fetch ISO 8601 durations for a batch of video IDs and convert to seconds.
     *
     * @param  string[] $videoIds
     * @param  string   $apiKey
     * @return array<string, int>  Map of videoId => duration_in_seconds
     */
    private function fetchDurations(array $videoIds, string $apiKey): array
    {
        $map      = [];
        $chunks   = array_chunk($videoIds, 50); // API allows up to 50 IDs per request

        foreach ($chunks as $chunk) {
            $response = Http::get('https://www.googleapis.com/youtube/v3/videos', [
                'part' => 'contentDetails',
                'id'   => implode(',', $chunk),
                'key'  => $apiKey,
            ]);

            if ($response->failed()) {
                $this->warn('Could not fetch durations for a batch; Shorts detection may be inaccurate.');
                continue;
            }

            foreach ($response->json('items', []) as $item) {
                $id       = $item['id'] ?? null;
                $duration = $item['contentDetails']['duration'] ?? 'PT0S';
                if ($id) {
                    $map[$id] = $this->iso8601ToSeconds($duration);
                }
            }
        }

        return $map;
    }

    /**
     * Convert an ISO 8601 duration string (e.g. "PT1M3S", "PT58S") to total seconds.
     */
    private function iso8601ToSeconds(string $duration): int
    {
        preg_match('/PT(?:(\d+)H)?(?:(\d+)M)?(?:(\d+)S)?/', $duration, $m);
        return (int)($m[1] ?? 0) * 3600
             + (int)($m[2] ?? 0) * 60
             + (int)($m[3] ?? 0);
    }
}
