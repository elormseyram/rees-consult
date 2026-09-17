<?php

namespace App\Helpers;

use App\Models\Pricing;

class PricingHelper
{
    public static function getAllPricings()
    {
        return Pricing::active()->ordered()->get();
    }

    public static function getPricingsByTest($testName)
    {
        return Pricing::active()
            ->where('test_name', $testName)
            ->first();
    }
}
