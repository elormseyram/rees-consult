<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('testimonials', function (Blueprint $table) {
            $table->enum('video_category', ['testimonial', 'award', 'other'])
                  ->nullable()
                  ->default(null)
                  ->after('youtube_video_url')
                  ->comment('Categorises YouTube videos: testimonial shows on /testimonials, award shows on /awards');
        });
    }

    public function down(): void
    {
        Schema::table('testimonials', function (Blueprint $table) {
            $table->dropColumn('video_category');
        });
    }
};
