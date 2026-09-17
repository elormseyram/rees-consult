<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // ── Add columns only if they don't already exist ────────────────────
        // The first migration attempt may have created these columns before
        // failing on the back-fill SQL, so we guard against duplicate-column errors.
        Schema::table('testimonials', function (Blueprint $table) {
            if (!Schema::hasColumn('testimonials', 'youtube_video_id')) {
                $table->string('youtube_video_id', 20)
                      ->nullable()
                      ->unique()
                      ->after('youtube_video_url')
                      ->comment('11-character YouTube video ID extracted from the URL. UNIQUE to prevent duplicates.');
            }

            if (!Schema::hasColumn('testimonials', 'is_short')) {
                $table->boolean('is_short')
                      ->default(false)
                      ->after('youtube_video_id')
                      ->comment('True when this video is a YouTube Short (≤ 60 seconds).');
            }
        });

        // ── Back-fill youtube_video_id for existing rows ─────────────────────
        // Uses PHP regex — no REGEXP_SUBSTR, compatible with MySQL 5.7+ / MariaDB.
        $rows = DB::table('testimonials')
                  ->whereNotNull('youtube_video_url')
                  ->whereNull('youtube_video_id')
                  ->select('id', 'youtube_video_url')
                  ->get();

        foreach ($rows as $row) {
            $videoId = null;

            // Same pattern used in Testimonial::getYoutubeEmbedUrlAttribute()
            if (preg_match(
                '%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/\s]{11})%i',
                $row->youtube_video_url,
                $match
            )) {
                $videoId = $match[1];
            }

            if ($videoId) {
                DB::table('testimonials')
                  ->where('id', $row->id)
                  ->update(['youtube_video_id' => $videoId]);
            }
        }
    }

    public function down(): void
    {
        Schema::table('testimonials', function (Blueprint $table) {
            if (Schema::hasColumn('testimonials', 'youtube_video_id')) {
                $table->dropUnique(['youtube_video_id']);
                $table->dropColumn('youtube_video_id');
            }

            if (Schema::hasColumn('testimonials', 'is_short')) {
                $table->dropColumn('is_short');
            }
        });
    }
};
