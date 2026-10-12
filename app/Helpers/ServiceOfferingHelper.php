<?php

namespace App\Helpers;

use App\Models\ServiceOffering;

class ServiceOfferingHelper
{
    public static function getByCategory($category)
    {
        return ServiceOffering::active()
            ->byCategory($category)
            ->ordered()
            ->get()
            ->unique(fn ($item) => strtolower(trim($item->title)));
    }

    public static function getAllByCategory()
    {
        return ServiceOffering::active()
            ->ordered()
            ->get()
            ->groupBy('category')
            ->map(function ($items) {
                return $items->unique(fn ($item) => strtolower(trim($item->title)));
            });
    }

    public static function getCategoryLabel($category)
    {
        $labels = [
            'test_preparation' => 'Test Preparation',
            'job_abroad' => 'Jobs Abroad',
            'school_scholarship' => 'Schools & Scholarships',
            'benefits' => 'Benefits',
            'countries' => 'Countries',
            'vacancies' => 'Vacancies',
            'partners' => 'Partners',
        ];
        return $labels[$category] ?? $category;
    }
}
