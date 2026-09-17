<?php

namespace Database\Seeders;

use App\Models\HeroSlider;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class HeroSliderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $sliders = [
            [
                'title' => 'Travel Abroad',
                'subtitle' => 'With Ease',
                'image_url' => 'https://images.unsplash.com/photo-1523240795612-9a054b0db644?w=1600',
                'overlay_opacity' => 0.5,
                'order' => 0,
                'is_active' => true,
            ],
            [
                'title' => 'Expert Guidance',
                'subtitle' => 'For Your Success',
                'image_url' => 'https://images.unsplash.com/photo-1522202176988-66273c2fd55f?w=1600',
                'overlay_opacity' => 0.5,
                'order' => 1,
                'is_active' => true,
            ],
            [
                'title' => 'Global Opportunities',
                'subtitle' => 'Await You',
                'image_url' => 'https://images.unsplash.com/photo-1552664730-d307ca884978?w=1600',
                'overlay_opacity' => 0.5,
                'order' => 2,
                'is_active' => true,
            ],
        ];

        foreach ($sliders as $slider) {
            HeroSlider::create($slider);
        }
    }
}
