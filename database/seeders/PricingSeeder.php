<?php

namespace Database\Seeders;

use App\Models\Pricing;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PricingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Truncate existing pricings to allow safe re-seeding
        \Illuminate\Support\Facades\DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        Pricing::truncate();
        \Illuminate\Support\Facades\DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $pricings = [
            ['test_name' => 'IELTS', 'group_in_person' => 2000, 'group_online' => 1700, 'one_on_one_in_person' => 3500, 'one_on_one_online' => 3000, 'order' => 0],
            ['test_name' => 'TOEFL', 'group_in_person' => 2000, 'group_online' => 1700, 'one_on_one_in_person' => 3500, 'one_on_one_online' => 3000, 'order' => 1],
            ['test_name' => 'GRE', 'group_in_person' => 3000, 'group_online' => 2700, 'one_on_one_in_person' => 3500, 'one_on_one_online' => 3000, 'order' => 2],
            ['test_name' => 'GMAT', 'group_in_person' => 3000, 'group_online' => 2700, 'one_on_one_in_person' => 3500, 'one_on_one_online' => 3000, 'order' => 3],
            ['test_name' => 'G-MAT', 'group_in_person' => 3000, 'group_online' => 2700, 'one_on_one_in_person' => 3500, 'one_on_one_online' => 3000, 'order' => 4],
            ['test_name' => 'SAT', 'group_in_person' => 3000, 'group_online' => 2700, 'one_on_one_in_person' => 3500, 'one_on_one_online' => 3000, 'order' => 5],
            ['test_name' => 'ACT', 'group_in_person' => 3000, 'group_online' => 2700, 'one_on_one_in_person' => 3500, 'one_on_one_online' => 3000, 'order' => 6],
            ['test_name' => 'TESOL', 'group_in_person' => 2000, 'group_online' => 1700, 'one_on_one_in_person' => 3500, 'one_on_one_online' => 3000, 'order' => 7],
            ['test_name' => 'NCLEX', 'group_in_person' => 2000, 'group_online' => 1700, 'one_on_one_in_person' => 3500, 'one_on_one_online' => 3000, 'order' => 8],
            ['test_name' => 'OET', 'group_in_person' => 2000, 'group_online' => 1700, 'one_on_one_in_person' => 3500, 'one_on_one_online' => 3000, 'order' => 9],
        ];

        foreach ($pricings as $pricing) {
            Pricing::create($pricing);
        }
    }
}
