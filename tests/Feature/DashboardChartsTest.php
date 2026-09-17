<?php

namespace Tests\Feature;

use App\Models\Consultation;
use App\Models\Lead;
use App\Models\User;
use App\Services\DashboardMetrics;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Dashboard analytics: aggregation correctness and chart rendering.
 */
class DashboardChartsTest extends TestCase
{
    use RefreshDatabase;

    private function admin(): User
    {
        return User::firstOrCreate(
            ['email' => 'admin@example.com'],
            ['name' => 'Admin User', 'password' => 'secret123', 'role' => 'admin', 'is_active' => true]
        );
    }

    private function lead(array $attrs = []): Lead
    {
        static $n = 0;
        $n++;

        return Lead::create(array_merge([
            'goal' => 'study_abroad', 'first_name' => "Lead{$n}", 'last_name' => 'Test',
            'email' => "lead{$n}@example.com", 'phone' => '+2348000000000',
            'rating' => 'WARM', 'status' => 'new', 'country' => 'Ghana',
        ], $attrs));
    }

    // ------------------------------------------------------------ headline

    public function test_headline_counts_and_rates(): void
    {
        $this->lead(['rating' => 'HOT', 'status' => 'won']);
        $this->lead(['rating' => 'HOT', 'status' => 'contacted']);
        $this->lead(['rating' => 'COLD', 'status' => 'new']);
        $this->lead(['rating' => 'WARM', 'status' => 'new']);

        $h = (new DashboardMetrics('all'))->headline();

        $this->assertSame(4, $h['total']);
        $this->assertSame(2, $h['hot']);
        $this->assertSame(50, $h['hot_rate']);
        $this->assertSame(2, $h['worked']);       // won + contacted
        $this->assertSame(2, $h['unworked']);
        $this->assertSame(1, $h['won']);
        $this->assertSame(50, $h['win_rate']);    // 1 won of 2 worked
    }

    public function test_headline_rates_do_not_divide_by_zero(): void
    {
        $h = (new DashboardMetrics('all'))->headline();

        $this->assertSame(0, $h['total']);
        $this->assertSame(0, $h['hot_rate']);
        $this->assertSame(0, $h['win_rate']);
    }

    // --------------------------------------------------------------- range

    public function test_range_filter_excludes_older_leads(): void
    {
        $this->lead();                                              // today
        $this->lead()->forceFill(['created_at' => now()->subDays(45)])->save();

        $this->assertSame(1, (new DashboardMetrics('30'))->headline()['total']);
        $this->assertSame(2, (new DashboardMetrics('90'))->headline()['total']);
        $this->assertSame(2, (new DashboardMetrics('all'))->headline()['total']);
    }

    public function test_invalid_range_falls_back_to_default(): void
    {
        $metrics = new DashboardMetrics('not-a-range');

        $this->assertSame('90', $metrics->range());
    }

    /**
     * The chart and the KPI row must agree — week buckets must not pull in
     * leads from outside the selected window.
     */
    public function test_time_chart_total_matches_the_headline_total(): void
    {
        // Seed across several weeks, including just outside the 30-day window.
        foreach ([0, 3, 9, 16, 23, 29, 34, 60] as $daysAgo) {
            $this->lead()->forceFill(['created_at' => now()->subDays($daysAgo)])->save();
        }

        foreach (['30', '90', 'all'] as $range) {
            $metrics = new DashboardMetrics($range);
            $headline = $metrics->headline()['total'];

            $chartTotal = 0;
            foreach ($metrics->leadsOverTime()['series'] as $counts) {
                $chartTotal += array_sum($counts);
            }

            $this->assertSame(
                $headline,
                $chartTotal,
                "Chart total must equal the KPI total for range={$range}"
            );
        }
    }

    public function test_time_chart_is_empty_without_leads(): void
    {
        $result = (new DashboardMetrics('30'))->leadsOverTime();

        $this->assertSame(0, $result['total']);
        $this->assertFalse($result['truncated']);
    }

    // -------------------------------------------------------------- splits

    public function test_rating_split_covers_every_rating(): void
    {
        $this->lead(['rating' => 'HOT']);
        $this->lead(['rating' => 'HOT']);
        $this->lead(['rating' => 'COLD']);

        $split = (new DashboardMetrics('all'))->ratingSplit();

        $this->assertSame(['COLD' => 1, 'WARM' => 0, 'HOT' => 2], $split);
    }

    public function test_unknown_rating_is_bucketed_rather_than_dropped(): void
    {
        $this->lead(['rating' => 'NEW']);   // the column's default

        $split = (new DashboardMetrics('all'))->ratingSplit();

        $this->assertSame(1, array_sum($split), 'every lead must appear somewhere');
    }

    public function test_goal_split_labels_and_counts(): void
    {
        $this->lead(['goal' => 'study_abroad']);
        $this->lead(['goal' => 'work_abroad']);
        $this->lead(['goal' => 'work_abroad']);

        $split = (new DashboardMetrics('all'))->goalSplit();

        $this->assertSame(1, $split['Study Abroad']);
        $this->assertSame(2, $split['Work Abroad']);
        $this->assertSame(0, $split['Test Prep']);
    }

    public function test_funnel_reports_every_stage_and_lost_separately(): void
    {
        $this->lead(['status' => 'new']);
        $this->lead(['status' => 'contacted']);
        $this->lead(['status' => 'lost']);

        $funnel = (new DashboardMetrics('all'))->funnel();

        $this->assertSame(['New' => 1, 'Contacted' => 1, 'Qualified' => 0, 'Won' => 0], $funnel['stages']);
        $this->assertSame(1, $funnel['lost']);
    }

    // ----------------------------------------------------------- countries

    public function test_country_codes_and_casing_are_normalised(): void
    {
        $this->lead(['country' => 'Ghana']);
        $this->lead(['country' => 'GH']);
        $this->lead(['country' => ' ghana ']);
        $this->lead(['country' => 'Ghana 🇬🇭']);

        $countries = (new DashboardMetrics('all'))->topCountries();

        $this->assertSame(['Ghana' => 4], $countries, 'variants must collapse into one entry');
    }

    public function test_blank_countries_are_skipped(): void
    {
        $this->lead(['country' => null]);
        $this->lead(['country' => '   ']);
        $this->lead(['country' => 'Nigeria']);

        $this->assertSame(['Nigeria' => 1], (new DashboardMetrics('all'))->topCountries());
    }

    public function test_country_tail_folds_into_other_rather_than_growing_the_palette(): void
    {
        foreach (['Ghana', 'Nigeria', 'Kenya', 'Cameroon', 'Togo', 'Benin', 'Mali', 'Chad'] as $i => $country) {
            // Give the first six descending weight so the tail is deterministic.
            $times = max(1, 10 - $i);
            for ($n = 0; $n < $times; $n++) {
                $this->lead(['country' => $country]);
            }
        }

        $countries = (new DashboardMetrics('all'))->topCountries(6);

        // Weights are 10,9,8,7,6,5 for the named six; the tail is Mali 4 + Chad 3.
        $this->assertCount(7, $countries, '6 named + Other');
        $this->assertArrayHasKey('Other', $countries);
        $this->assertSame(4 + 3, $countries['Other'], 'Mali (4) + Chad (3)');
    }

    // ------------------------------------------------------------ channels

    public function test_channel_mix_counts_each_source(): void
    {
        $this->lead();
        $this->lead();
        Consultation::create([
            'first_name' => 'Ada', 'last_name' => 'Okoro', 'email' => 'ada@example.com',
            'phone' => '+2348001112222', 'service' => 'Study Abroad', 'status' => 'pending',
        ]);

        $channels = (new DashboardMetrics('all'))->channelMix();

        $this->assertSame(2, $channels['Apply Now leads']);
        $this->assertSame(1, $channels['Consultations']);
        $this->assertSame(0, $channels['Work abroad apps']);
    }

    // -------------------------------------------------------------- render

    public function test_dashboard_renders_the_charts(): void
    {
        $this->lead(['rating' => 'HOT']);

        $response = $this->actingAs($this->admin())->get(route('admin.dashboard'));

        $response->assertOk()
            ->assertSee('chartOverTime')
            ->assertSee('chartRating')
            ->assertSee('chartGoal')
            ->assertSee('chartFunnel')
            ->assertSee('chartChannels')
            ->assertSee('chartCountries')
            ->assertSee('Table view');
    }

    public function test_dashboard_renders_with_no_data_at_all(): void
    {
        $this->actingAs($this->admin())
            ->get(route('admin.dashboard'))
            ->assertOk()
            ->assertSee('No leads in this period.');
    }

    public function test_range_parameter_is_reflected_in_the_page(): void
    {
        $this->lead();

        $this->actingAs($this->admin())
            ->get(route('admin.dashboard', ['range' => '30']))
            ->assertOk()
            ->assertSee('last 30 days');
    }

    public function test_unworked_warning_shows_only_when_nothing_is_worked(): void
    {
        $this->lead(['status' => 'new']);

        $this->actingAs($this->admin())
            ->get(route('admin.dashboard'))
            ->assertOk()
            ->assertSee('still marked', false);

        Lead::query()->update(['status' => 'contacted']);

        $this->actingAs($this->admin())
            ->get(route('admin.dashboard'))
            ->assertOk()
            ->assertDontSee('still marked', false);
    }

    public function test_employees_can_view_the_dashboard(): void
    {
        $employee = User::create([
            'name' => 'Sales Rep', 'email' => 'rep@example.com',
            'password' => 'secret123', 'role' => 'employee', 'is_active' => true,
        ]);

        $this->actingAs($employee)->get(route('admin.dashboard'))->assertOk();
    }
}
