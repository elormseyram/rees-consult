<?php

namespace App\Services;

use App\Models\Consultation;
use App\Models\JobApplication;
use App\Models\Lead;
use App\Models\SchoolApplication;
use App\Models\ServiceSignup;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;

/**
 * Aggregates the numbers behind the dashboard charts.
 *
 * Date bucketing is done in PHP rather than with driver-specific SQL
 * (DATE_FORMAT is MySQL-only) so the same code runs under the SQLite test
 * database. Only the columns needed for grouping are selected.
 */
class DashboardMetrics
{
    /** Selectable ranges for the dashboard filter row: label => days (null = all time). */
    public const RANGES = [
        '30'  => 'Last 30 days',
        '90'  => 'Last 90 days',
        '365' => 'Last 12 months',
        'all' => 'All time',
    ];

    /** Lead pipeline stages in progression order; `lost` is an exit, not a stage. */
    public const FUNNEL_STAGES = ['new', 'contacted', 'qualified', 'won'];

    /** Ratings ordered cold -> hot so they map onto the ordinal colour ramp. */
    public const RATINGS = ['COLD', 'WARM', 'HOT'];

    public function __construct(private string $range = '90')
    {
        if (! array_key_exists($this->range, self::RANGES)) {
            $this->range = '90';
        }
    }

    /** Start of the selected window, or null for all time. */
    public function since(): ?Carbon
    {
        return $this->range === 'all' ? null : now()->subDays((int) $this->range)->startOfDay();
    }

    public function range(): string
    {
        return $this->range;
    }

    public function rangeLabel(): string
    {
        return self::RANGES[$this->range];
    }

    /** Apply the window to any query with a created_at column. */
    private function scoped($query)
    {
        $since = $this->since();

        return $since ? $query->where('created_at', '>=', $since) : $query;
    }

    /** Headline figures for the KPI row. */
    public function headline(): array
    {
        $total = $this->scoped(Lead::query())->count();
        $hot   = $this->scoped(Lead::query())->where('rating', 'HOT')->count();
        $worked = $this->scoped(Lead::query())->where('status', '!=', 'new')->count();
        $won   = $this->scoped(Lead::query())->where('status', 'won')->count();

        // Rates are whole-number percentages — cast so they serialise as ints.
        return [
            'total'       => $total,
            'hot'         => $hot,
            'hot_rate'    => $total ? (int) round($hot / $total * 100) : 0,
            'worked'      => $worked,
            'unworked'    => $total - $worked,
            'worked_rate' => $total ? (int) round($worked / $total * 100) : 0,
            'won'         => $won,
            'win_rate'    => $worked ? (int) round($won / $worked * 100) : 0,
        ];
    }

    /**
     * Lead volume split by rating, bucketed by week.
     *
     * Buckets are whole weeks (so columns are comparable) but only leads
     * inside the selected window are counted — the chart therefore totals
     * exactly the same as the KPI row. The first bucket may be a part-week.
     *
     * Returns labels, per-rating series, and a `truncated` flag when the
     * bucket cap hid older weeks (never drop data silently).
     */
    public function leadsOverTime(): array
    {
        $earliest = Lead::min('created_at');
        $since    = $this->since() ?? ($earliest ? Carbon::parse($earliest) : null);

        if (! $since) {
            return ['labels' => [], 'iso' => [], 'series' => [], 'truncated' => false, 'total' => 0];
        }

        $since = Carbon::parse($since);
        $firstBucket = (clone $since)->startOfWeek();
        $lastBucket  = now()->startOfWeek();

        $weeksSpan = (int) $firstBucket->diffInWeeks($lastBucket) + 1;

        // Cap the column count so "all time" can't render hundreds of bars.
        $cap = 26;
        $truncated = $weeksSpan > $cap;
        $weeks = min($cap, max(1, $weeksSpan));

        $start = (clone $lastBucket)->subWeeks($weeks - 1);

        // When truncating, the window start moves forward with the buckets so
        // the counts still match what is drawn.
        $countFrom = $truncated ? $start : $since;

        $buckets = [];
        for ($i = 0; $i < $weeks; $i++) {
            $buckets[(clone $start)->addWeeks($i)->format('Y-m-d')] = array_fill_keys(self::RATINGS, 0);
        }

        $total = 0;
        Lead::query()
            ->where('created_at', '>=', $countFrom)
            ->get(['created_at', 'rating'])
            ->each(function ($lead) use (&$buckets, &$total) {
                $key = $lead->created_at->copy()->startOfWeek()->format('Y-m-d');
                $rating = in_array($lead->rating, self::RATINGS, true) ? $lead->rating : 'COLD';

                if (isset($buckets[$key])) {
                    $buckets[$key][$rating]++;
                    $total++;
                }
            });

        $series = array_fill_keys(self::RATINGS, []);
        foreach ($buckets as $counts) {
            foreach (self::RATINGS as $rating) {
                $series[$rating][] = $counts[$rating];
            }
        }

        return [
            'labels'    => array_map(fn ($d) => Carbon::parse($d)->format('M j'), array_keys($buckets)),
            'iso'       => array_keys($buckets),
            'series'    => $series,
            'truncated' => $truncated,
            'weeks'     => $weeks,
            'total'     => $total,
        ];
    }

    /** Lead quality split — drives the rating doughnut. */
    public function ratingSplit(): array
    {
        $counts = $this->scoped(Lead::query())
            ->get(['rating'])
            ->countBy(fn ($lead) => in_array($lead->rating, self::RATINGS, true) ? $lead->rating : 'COLD');

        return collect(self::RATINGS)
            ->mapWithKeys(fn ($rating) => [$rating => (int) $counts->get($rating, 0)])
            ->all();
    }

    /** What people are asking for — drives the goal doughnut. */
    public function goalSplit(): array
    {
        $labels = [
            'study_abroad' => 'Study Abroad',
            'work_abroad'  => 'Work Abroad',
            'test_prep'    => 'Test Prep',
        ];

        $counts = $this->scoped(Lead::query())->get(['goal'])->countBy('goal');

        $out = [];
        foreach ($labels as $key => $label) {
            $out[$label] = (int) $counts->get($key, 0);
        }

        // Anything unexpected in the column still gets represented.
        $other = $counts->reject(fn ($_, $key) => array_key_exists($key, $labels))->sum();
        if ($other > 0) {
            $out['Other'] = $other;
        }

        return $out;
    }

    /** Follow-up funnel: how far leads have progressed. */
    public function funnel(): array
    {
        $counts = $this->scoped(Lead::query())->get(['status'])->countBy('status');

        $stages = [];
        foreach (self::FUNNEL_STAGES as $stage) {
            $stages[ucfirst($stage)] = (int) $counts->get($stage, 0);
        }

        return ['stages' => $stages, 'lost' => (int) $counts->get('lost', 0)];
    }

    /** Top lead countries, tail folded into "Other" rather than growing the palette. */
    public function topCountries(int $limit = 6): array
    {
        $counts = $this->scoped(Lead::query())
            ->get(['country'])
            ->map(fn ($lead) => $this->normaliseCountry($lead->country))
            ->filter()
            ->countBy()
            ->sortDesc();

        $top = $counts->take($limit);
        $other = $counts->slice($limit)->sum();

        $out = $top->all();
        if ($other > 0) {
            $out['Other'] = $other;
        }

        return $out;
    }

    /** Where enquiries arrive from, across every public form. */
    public function channelMix(): array
    {
        return [
            'Apply Now leads'    => $this->scoped(Lead::query())->count(),
            'Consultations'      => $this->scoped(Consultation::query())->count(),
            'Course signups'     => $this->scoped(ServiceSignup::query())->count(),
            'Study abroad apps'  => $this->scoped(SchoolApplication::query())->count(),
            'Work abroad apps'   => $this->scoped(JobApplication::query())->count(),
        ];
    }

    /**
     * Tidy up free-text country entries so "GH" and "ghana " don't render as
     * separate slices. Deliberately conservative — only obvious fixes.
     */
    private function normaliseCountry(?string $raw): ?string
    {
        $value = trim((string) $raw);

        // Strip emoji/flags and collapse whitespace.
        $value = preg_replace('/[\x{1F000}-\x{1FAFF}\x{2600}-\x{27BF}\x{FE0F}]/u', '', $value);
        $value = trim(preg_replace('/\s+/', ' ', $value));

        if ($value === '') {
            return null;
        }

        $codes = [
            'GH' => 'Ghana', 'NG' => 'Nigeria', 'ZA' => 'South Africa', 'KE' => 'Kenya',
            'CM' => 'Cameroon', 'UK' => 'United Kingdom', 'GB' => 'United Kingdom',
            'US' => 'United States', 'USA' => 'United States', 'CA' => 'Canada',
        ];

        $upper = strtoupper($value);
        if (isset($codes[$upper])) {
            return $codes[$upper];
        }

        return ucwords(strtolower($value));
    }
}
