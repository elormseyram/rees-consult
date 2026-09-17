<?php

namespace Database\Seeders;

use App\Models\TeamMember;
use Illuminate\Database\Seeder;

class TeamMemberSeeder extends Seeder
{
    public function run(): void
    {
        $teamMembers = [
            [
                'name' => 'Rosemary Gyau',
                'role' => 'CEO / Instructor, Ghana',
                'bio' => 'Founder and CEO of Ree\'s Consult with over 10 years of experience in education consulting and IELTS preparation.',
                'social_links' => [
                    'facebook' => 'https://facebook.com',
                    'twitter' => 'https://twitter.com',
                    'linkedin' => 'https://linkedin.com',
                    'instagram' => 'https://instagram.com',
                ],
                'is_active' => true,
                'order' => 1,
            ],
            [
                'name' => 'Daniel Prince Mantey',
                'role' => 'Head of Marketing',
                'bio' => 'Marketing strategist with expertise in digital marketing and brand development.',
                'social_links' => [
                    'facebook' => 'https://facebook.com',
                    'twitter' => 'https://twitter.com',
                    'linkedin' => 'https://linkedin.com',
                    'instagram' => 'https://instagram.com',
                ],
                'is_active' => true,
                'order' => 2,
            ],
            [
                'name' => 'Justice Remy Anderson',
                'role' => 'Graphic Designer',
                'bio' => 'Creative designer specializing in branding and visual communication.',
                'social_links' => [
                    'facebook' => 'https://facebook.com',
                    'twitter' => 'https://twitter.com',
                    'linkedin' => 'https://linkedin.com',
                    'instagram' => 'https://instagram.com',
                ],
                'is_active' => true,
                'order' => 3,
            ],
            [
                'name' => 'Emmanuella Beke',
                'role' => 'Social Media Manager',
                'bio' => 'Social media expert managing online presence and community engagement.',
                'social_links' => [
                    'facebook' => 'https://facebook.com',
                    'twitter' => 'https://twitter.com',
                    'linkedin' => 'https://linkedin.com',
                    'instagram' => 'https://instagram.com',
                ],
                'is_active' => true,
                'order' => 4,
            ],
        ];

        foreach ($teamMembers as $member) {
            TeamMember::updateOrCreate(
                ['name' => $member['name']],
                $member
            );
        }
    }
}
