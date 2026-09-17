<?php

namespace Database\Seeders;

use App\Models\HeroImage;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class HeroImageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $heroImages = [
            [
                'page_slug' => 'home',
                'page_title' => 'Home',
                'image_url' => 'https://images.unsplash.com/photo-1522202176988-66273c2fd55f?w=1600',
                'overlay_opacity' => 0.8,
                'is_active' => true,
            ],
            [
                'page_slug' => 'about',
                'page_title' => 'About Us',
                'image_url' => 'https://images.unsplash.com/photo-1523240795612-9a054b0db644?w=1600',
                'overlay_opacity' => 0.8,
                'is_active' => true,
            ],
            [
                'page_slug' => 'services',
                'page_title' => 'Services',
                'image_url' => 'https://images.unsplash.com/photo-1522202176988-66273c2fd55f?w=1600',
                'overlay_opacity' => 0.8,
                'is_active' => true,
            ],
            [
                'page_slug' => 'scholarships',
                'page_title' => 'Scholarships',
                'image_url' => 'https://images.unsplash.com/photo-1523240795612-9a054b0db644?w=1600',
                'overlay_opacity' => 0.8,
                'is_active' => true,
            ],
            [
                'page_slug' => 'events',
                'page_title' => 'Events',
                'image_url' => 'https://images.unsplash.com/photo-1544531586-fde5298cdd40?w=1600',
                'overlay_opacity' => 0.8,
                'is_active' => true,
            ],
            [
                'page_slug' => 'blog',
                'page_title' => 'Blog',
                'image_url' => 'https://images.unsplash.com/photo-1499750310159-5b5f226932b7?w=1600',
                'overlay_opacity' => 0.8,
                'is_active' => true,
            ],
            [
                'page_slug' => 'contact',
                'page_title' => 'Contact',
                'image_url' => 'https://images.unsplash.com/photo-1423666639041-f142fcb93370?w=1600',
                'overlay_opacity' => 0.8,
                'is_active' => true,
            ],
            [
                'page_slug' => 'testimonials',
                'page_title' => 'Testimonials',
                'image_url' => 'https://images.unsplash.com/photo-1522202176988-66273c2fd55f?w=1600',
                'overlay_opacity' => 0.8,
                'is_active' => true,
            ],
        ];

        foreach ($heroImages as $heroImage) {
            HeroImage::updateOrCreate(
                ['page_slug' => $heroImage['page_slug']],
                $heroImage
            );
        }
    }
}
