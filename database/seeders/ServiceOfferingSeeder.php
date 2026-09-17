<?php

namespace Database\Seeders;

use App\Models\ServiceOffering;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ServiceOfferingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $offerings = [
            // Test Preparation
            ['title' => 'IELTS', 'category' => 'test_preparation', 'icon' => 'bi-book', 'order' => 0, 'is_active' => true],
            ['title' => 'TOEFL', 'category' => 'test_preparation', 'icon' => 'bi-book', 'order' => 1, 'is_active' => true],
            ['title' => 'GMAT', 'category' => 'test_preparation', 'icon' => 'bi-book', 'order' => 2, 'is_active' => true],
            ['title' => 'TESOL', 'category' => 'test_preparation', 'icon' => 'bi-book', 'order' => 3, 'is_active' => true],
            ['title' => 'NCLEX', 'category' => 'test_preparation', 'icon' => 'bi-book', 'order' => 4, 'is_active' => true],
            ['title' => 'OET', 'category' => 'test_preparation', 'icon' => 'bi-book', 'order' => 5, 'is_active' => true],
            ['title' => 'GRE', 'category' => 'test_preparation', 'icon' => 'bi-book', 'order' => 6, 'is_active' => true],
            ['title' => 'SAT', 'category' => 'test_preparation', 'icon' => 'bi-book', 'order' => 7, 'is_active' => true],
            
            // Jobs Abroad
            ['title' => 'Jobs Abroad', 'description' => 'Professional job placement services internationally', 'category' => 'job_abroad', 'icon' => 'bi-briefcase', 'order' => 0, 'is_active' => true],
            
            // Schools & Scholarships
            ['title' => 'Schools and Scholarship Applications Abroad', 'description' => 'Complete guidance for international school and scholarship applications', 'category' => 'school_scholarship', 'icon' => 'bi-mortarboard', 'order' => 0, 'is_active' => true],
            
            // Benefits
            ['title' => 'Visa', 'category' => 'benefits', 'icon' => 'bi-passport', 'order' => 0, 'is_active' => true],
            ['title' => 'Work Permit', 'category' => 'benefits', 'icon' => 'bi-file-earmark-check', 'order' => 1, 'is_active' => true],
            ['title' => 'Residence Permit', 'category' => 'benefits', 'icon' => 'bi-house-check', 'order' => 2, 'is_active' => true],
            ['title' => 'Accommodation', 'category' => 'benefits', 'icon' => 'bi-building', 'order' => 3, 'is_active' => true],
            
            // Countries
            ['title' => 'Netherlands', 'category' => 'countries', 'icon' => 'bi-globe', 'order' => 0, 'is_active' => true],
            ['title' => 'Ireland', 'category' => 'countries', 'icon' => 'bi-globe', 'order' => 1, 'is_active' => true],
            ['title' => 'Italy', 'category' => 'countries', 'icon' => 'bi-globe', 'order' => 2, 'is_active' => true],
            ['title' => 'Germany', 'category' => 'countries', 'icon' => 'bi-globe', 'order' => 3, 'is_active' => true],
            ['title' => 'Luxembourg', 'category' => 'countries', 'icon' => 'bi-globe', 'order' => 4, 'is_active' => true],
            ['title' => 'Norway', 'category' => 'countries', 'icon' => 'bi-globe', 'order' => 5, 'is_active' => true],
            ['title' => 'Finland', 'category' => 'countries', 'icon' => 'bi-globe', 'order' => 6, 'is_active' => true],
            
            // Vacancies
            ['title' => 'Nurses', 'category' => 'vacancies', 'icon' => 'bi-heart-pulse', 'order' => 0, 'is_active' => true],
            ['title' => 'Midwives', 'category' => 'vacancies', 'icon' => 'bi-heart-pulse', 'order' => 1, 'is_active' => true],
            ['title' => 'Warehouse Workers', 'category' => 'vacancies', 'icon' => 'bi-box2', 'order' => 2, 'is_active' => true],
            ['title' => 'Admin Assistants', 'category' => 'vacancies', 'icon' => 'bi-person-check', 'order' => 3, 'is_active' => true],
            ['title' => 'Accountants', 'category' => 'vacancies', 'icon' => 'bi-calculator', 'order' => 4, 'is_active' => true],
            
            // Partners
            ['title' => 'The British Council', 'category' => 'partners', 'icon' => 'bi-building', 'order' => 0, 'is_active' => true],
            ['title' => 'International Development Program (IDP)', 'category' => 'partners', 'icon' => 'bi-building', 'order' => 1, 'is_active' => true],
            ['title' => 'African Institute for Mathematical Sciences (AIMS, GHANA)', 'category' => 'partners', 'icon' => 'bi-building', 'order' => 2, 'is_active' => true],
            ['title' => 'NAFSA: Association of International Educators', 'category' => 'partners', 'icon' => 'bi-building', 'order' => 3, 'is_active' => true],
        ];

        foreach ($offerings as $offering) {
            ServiceOffering::create($offering);
        }
    }
}
