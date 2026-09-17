<?php

namespace App\Helpers;

use App\Models\HeroImage;

class HeroImageHelper
{
    /**
     * Get hero image for a specific page
     */
    public static function getHeroImage($pageSlug)
    {
        return HeroImage::where('page_slug', $pageSlug)
            ->where('is_active', true)
            ->first();
    }

    /**
     * Get hero image URL (either uploaded or external)
     */
    public static function getHeroImageUrl($pageSlug)
    {
        $heroImage = self::getHeroImage($pageSlug);
        
        if (!$heroImage) {
            return null;
        }

        if ($heroImage->image_path || $heroImage->image_url) {
            return $heroImage->formatted_image_url;
        }

        return $heroImage->image_url;
    }

    /**
     * Get hero image with all details
     */
    public static function getHeroImageData($pageSlug)
    {
        return self::getHeroImage($pageSlug);
    }
}
