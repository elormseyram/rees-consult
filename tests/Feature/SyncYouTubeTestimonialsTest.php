<?php

namespace Tests\Feature;

use App\Models\Testimonial;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class SyncYouTubeTestimonialsTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Configure mock credentials
        Config::set('services.youtube.key', 'mock_api_key');
        Config::set('services.youtube.channel_id', 'mock_channel_id');
        Config::set('services.youtube.playlist_id', null); // force auto-resolve
    }

    public function test_youtube_sync_command_resolves_playlist_and_syncs_videos(): void
    {
        // Mock YouTube API responses
        Http::fake([
            'https://www.googleapis.com/youtube/v3/channels*' => Http::response([
                'items' => [
                    [
                        'contentDetails' => [
                            'relatedPlaylists' => [
                                'uploads' => 'mock_uploads_playlist_id'
                            ]
                        ]
                    ]
                ]
            ], 200),

            'https://www.googleapis.com/youtube/v3/playlistItems*' => Http::response([
                'items' => [
                    [
                        'snippet' => [
                            'title' => 'Jane Doe Testimonial',
                            'description' => 'Jane talks about her IELTS preparation experience.',
                            'resourceId' => [
                                'videoId' => 'xyz123'
                            ]
                        ]
                    ],
                    [
                        'snippet' => [
                            'title' => 'John Smith Testimonial',
                            'description' => 'John secured visa for study abroad.',
                            'resourceId' => [
                                'videoId' => 'abc456'
                            ]
                        ]
                    ]
                ]
            ], 200),
        ]);

        // Run Artisan command
        $this->artisan('youtube:sync-testimonials')
            ->expectsOutput('Resolving uploads playlist for Channel ID: mock_channel_id')
            ->expectsOutput('Resolved Uploads Playlist ID: mock_uploads_playlist_id')
            ->expectsOutput('Fetching videos from playlist: mock_uploads_playlist_id')
            ->expectsOutput('Sync complete. Synced: 2 new video(s), Skipped: 0 existing video(s).')
            ->assertExitCode(0);

        // Verify records in DB
        $this->assertDatabaseHas('testimonials', [
            'name' => 'Jane Doe Testimonial',
            'role' => 'YouTube Review',
            'message' => 'Jane talks about her IELTS preparation experience.',
            'youtube_video_url' => 'https://www.youtube.com/watch?v=xyz123',
            'rating' => 5,
            'is_active' => false,
        ]);

        $this->assertDatabaseHas('testimonials', [
            'name' => 'John Smith Testimonial',
            'role' => 'YouTube Review',
            'message' => 'John secured visa for study abroad.',
            'youtube_video_url' => 'https://www.youtube.com/watch?v=abc456',
            'rating' => 5,
            'is_active' => false,
        ]);
    }

    public function test_youtube_sync_command_skips_duplicates(): void
    {
        // Pre-create a testimonial that matches the video ID
        Testimonial::create([
            'name' => 'Existing Video',
            'role' => 'YouTube Review',
            'message' => 'Already saved',
            'youtube_video_url' => 'https://www.youtube.com/watch?v=xyz123',
            'rating' => 5,
            'is_active' => false,
        ]);

        // Mock response
        Http::fake([
            'https://www.googleapis.com/youtube/v3/channels*' => Http::response([
                'items' => [
                    [
                        'contentDetails' => [
                            'relatedPlaylists' => [
                                'uploads' => 'mock_uploads_playlist_id'
                            ]
                        ]
                    ]
                ]
            ], 200),

            'https://www.googleapis.com/youtube/v3/playlistItems*' => Http::response([
                'items' => [
                    [
                        'snippet' => [
                            'title' => 'Existing Video',
                            'description' => 'Already saved',
                            'resourceId' => [
                                'videoId' => 'xyz123'
                            ]
                        ]
                    ],
                    [
                        'snippet' => [
                            'title' => 'New Video',
                            'description' => 'Not saved yet',
                            'resourceId' => [
                                'videoId' => 'new789'
                            ]
                        ]
                    ]
                ]
            ], 200),
        ]);

        $this->artisan('youtube:sync-testimonials')
            ->expectsOutput('Sync complete. Synced: 1 new video(s), Skipped: 1 existing video(s).')
            ->assertExitCode(0);

        $this->assertDatabaseHas('testimonials', [
            'name' => 'New Video',
            'youtube_video_url' => 'https://www.youtube.com/watch?v=new789',
        ]);
    }
}
