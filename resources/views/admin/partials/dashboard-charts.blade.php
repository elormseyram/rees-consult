{{--
    Dashboard analytics.

    Colour roles follow the validated data-viz palette:
      · ordered scales (lead rating, pipeline stage) use the one-hue blue
        ordinal ramp — light = low, dark = high;
      · nominal categories (goal) use categorical slots 1–3;
      · single-series bars use slot 1 for every bar (never a value-ramp on
        nominal categories);
      · "Other" is a residual bucket, so it takes neutral gray rather than
        growing the palette.
    Every chart ships a table view twin, and no value is reachable only
    through a tooltip.
--}}
@php
    $h        = $charts['headline'];
    $overTime = $charts['overTime'];
    $funnel   = $charts['funnel'];

    $hasLeads    = $h['total'] > 0;
    $funnelTotal = array_sum($funnel['stages']) + $funnel['lost'];
@endphp

<style>
    .viz {
        /* Chart surface is the card background. */
        --viz-surface:   #ffffff;
        --viz-ink:       #0b0b0b;
        --viz-ink-2:     #52514e;
        --viz-muted:     #898781;
        --viz-grid:      #e1e0d9;
        --viz-axis:      #c3c2b7;

        /* One-hue ordinal ramp (light → dark) */
        --viz-ord-1:     #86b6ef;
        --viz-ord-2:     #5598e7;
        --viz-ord-3:     #2a78d6;
        --viz-ord-4:     #184f95;
        --viz-ord-top:   #104281;

        /* Categorical slots 1–3 */
        --viz-cat-1:     #2a78d6;
        --viz-cat-2:     #eb6834;
        --viz-cat-3:     #1baf7a;
        --viz-neutral:   #898781;
    }
    .viz-card { background: var(--viz-surface); border: 0; border-radius: .5rem; }
    .viz-card .card-header { background: var(--viz-surface); border-bottom: 1px solid var(--viz-grid); }
    .viz-title { font-size: .95rem; font-weight: 700; color: var(--viz-ink); margin: 0; }
    .viz-sub   { font-size: .75rem; color: var(--viz-muted); margin: .15rem 0 0; }

    /* Container grows to include the x-axis band — never a nested scrollbar. */
    .viz-plot { position: relative; height: 300px; }
    .viz-plot-sm { position: relative; height: 240px; }

    .viz-legend { display: flex; flex-wrap: wrap; gap: .75rem; margin-top: .75rem; }
    .viz-legend-item { display: inline-flex; align-items: center; gap: .35rem; font-size: .75rem; color: var(--viz-ink-2); }
    .viz-swatch { width: 10px; height: 10px; border-radius: 2px; flex-shrink: 0; }

    .viz-table-toggle { font-size: .72rem; color: var(--viz-muted); cursor: pointer; list-style: none; padding-top: .6rem; }
    .viz-table-toggle::-webkit-details-marker { display: none; }
    .viz-table-toggle:hover { color: var(--viz-ink-2); }
    .viz-table { font-size: .75rem; margin-top: .5rem; margin-bottom: 0; }
    .viz-table td, .viz-table th { padding: .3rem .5rem; }
    .viz-table td.num, .viz-table th.num { text-align: right; font-variant-numeric: tabular-nums; }

    .viz-empty { display: flex; align-items: center; justify-content: center; height: 100%;
                 color: var(--viz-muted); font-size: .8rem; text-align: center; padding: 1rem; }

    /* KPI tiles — a number is the chart when there is only one value. */
    .kpi { background: var(--viz-surface); border-radius: .5rem; padding: 1rem 1.15rem; height: 100%; }
    .kpi-label { font-size: .68rem; text-transform: uppercase; letter-spacing: .5px; color: var(--viz-muted); font-weight: 600; }
    .kpi-value { font-size: 1.75rem; font-weight: 700; color: var(--viz-ink); line-height: 1.15; margin-top: .15rem; }
    .kpi-note  { font-size: .72rem; color: var(--viz-ink-2); margin-top: .1rem; }

    /* Meter — a single ratio against a limit, same-ramp track. */
    .meter { height: 6px; border-radius: 3px; background: var(--viz-grid); overflow: hidden; margin-top: .5rem; }
    .meter > span { display: block; height: 100%; border-radius: 3px; background: var(--viz-ord-3); }

    @media print { .viz-plot, .viz-plot-sm { display: none; } }
</style>

<div class="viz">

    {{-- One filter row, above everything it scopes --}}
    <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-3">
        <div>
            <h2 class="h5 fw-bold mb-0">Performance</h2>
            <p class="viz-sub">All charts below show <strong>{{ strtolower($charts['rangeLabel']) }}</strong>.</p>
        </div>
        <div class="btn-group btn-group-sm" role="group" aria-label="Date range">
            @foreach ($charts['ranges'] as $value => $label)
                <a href="{{ route('admin.dashboard', ['range' => $value]) }}"
                   class="btn {{ $charts['range'] === (string) $value ? 'btn-primary' : 'btn-outline-secondary' }}"
                   @if($charts['range'] === (string) $value) aria-current="true" @endif>
                    {{ str_replace(['Last ', ' days', ' months'], ['', 'd', 'm'], $label) }}
                </a>
            @endforeach
        </div>
    </div>

    {{-- KPI row --}}
    <div class="row g-3 mb-3">
        <div class="col-6 col-lg-3">
            <div class="kpi">
                <div class="kpi-label">Leads</div>
                <div class="kpi-value">{{ number_format($h['total']) }}</div>
                <div class="kpi-note">{{ $charts['rangeLabel'] }}</div>
            </div>
        </div>
        <div class="col-6 col-lg-3">
            <div class="kpi">
                <div class="kpi-label">Hot leads</div>
                <div class="kpi-value">{{ number_format($h['hot']) }}</div>
                <div class="kpi-note">{{ $h['hot_rate'] }}% of all leads</div>
                <div class="meter" role="img" aria-label="{{ $h['hot_rate'] }} percent of leads are hot">
                    <span style="width: {{ min(100, $h['hot_rate']) }}%"></span>
                </div>
            </div>
        </div>
        <div class="col-6 col-lg-3">
            <div class="kpi">
                <div class="kpi-label">Worked</div>
                <div class="kpi-value">{{ number_format($h['worked']) }}</div>
                <div class="kpi-note">{{ $h['worked_rate'] }}% contacted or beyond</div>
                <div class="meter" role="img" aria-label="{{ $h['worked_rate'] }} percent of leads have been worked">
                    <span style="width: {{ min(100, $h['worked_rate']) }}%"></span>
                </div>
            </div>
        </div>
        <div class="col-6 col-lg-3">
            <div class="kpi">
                <div class="kpi-label">Won</div>
                <div class="kpi-value">{{ number_format($h['won']) }}</div>
                <div class="kpi-note">
                    {{ $h['worked'] ? $h['win_rate'] . '% of worked leads' : 'No leads worked yet' }}
                </div>
            </div>
        </div>
    </div>

    @if ($hasLeads && $h['unworked'] === $h['total'])
        <div class="alert alert-warning d-flex align-items-start gap-2 py-2 px-3 mb-3" style="font-size:.82rem;">
            <i class="bi bi-exclamation-triangle-fill flex-shrink-0 mt-1"></i>
            <div>
                <strong>All {{ number_format($h['total']) }} leads are still marked “new”.</strong>
                The pipeline chart stays flat until staff update a lead’s status on its detail page,
                so win-rate and progress figures can’t be calculated yet.
            </div>
        </div>
    @endif

    <div class="row g-3">

        {{-- ── Leads over time: stacked bar, 3 ordered series ─────────────── --}}
        <div class="col-12 col-xl-8">
            <div class="card viz-card h-100">
                <div class="card-header py-3">
                    <h3 class="viz-title">Lead volume &amp; quality by week</h3>
                    <p class="viz-sub">
                        {{ number_format($overTime['total'] ?? 0) }} leads across
                        {{ $overTime['weeks'] ?? 0 }} weeks
                        @if (!empty($overTime['truncated'])) · showing the most recent 26 weeks only @endif
                    </p>
                </div>
                <div class="card-body">
                    @if ($hasLeads)
                        <div class="viz-plot"><canvas id="chartOverTime"></canvas></div>
                        <div class="viz-legend">
                            <span class="viz-legend-item"><span class="viz-swatch" style="background:var(--viz-ord-top)"></span>Hot</span>
                            <span class="viz-legend-item"><span class="viz-swatch" style="background:var(--viz-ord-3)"></span>Warm</span>
                            <span class="viz-legend-item"><span class="viz-swatch" style="background:var(--viz-ord-1)"></span>Cold</span>
                        </div>
                        <details>
                            <summary class="viz-table-toggle"><i class="bi bi-table"></i> Table view</summary>
                            <div class="table-responsive">
                                <table class="table table-sm viz-table">
                                    <thead>
                                        <tr>
                                            <th>Week of</th>
                                            <th class="num">Hot</th><th class="num">Warm</th>
                                            <th class="num">Cold</th><th class="num">Total</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($overTime['labels'] as $i => $label)
                                            @php
                                                $hot  = $overTime['series']['HOT'][$i] ?? 0;
                                                $warm = $overTime['series']['WARM'][$i] ?? 0;
                                                $cold = $overTime['series']['COLD'][$i] ?? 0;
                                            @endphp
                                            <tr>
                                                <td>{{ $label }}</td>
                                                <td class="num">{{ $hot }}</td>
                                                <td class="num">{{ $warm }}</td>
                                                <td class="num">{{ $cold }}</td>
                                                <td class="num fw-semibold">{{ $hot + $warm + $cold }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </details>
                    @else
                        <div class="viz-plot"><div class="viz-empty">No leads in this period.</div></div>
                    @endif
                </div>
            </div>
        </div>

        {{-- ── Rating split: doughnut, ordered ramp ───────────────────────── --}}
        <div class="col-12 col-md-6 col-xl-4">
            <div class="card viz-card h-100">
                <div class="card-header py-3">
                    <h3 class="viz-title">Lead quality</h3>
                    <p class="viz-sub">Share of leads by buying-readiness rating</p>
                </div>
                <div class="card-body">
                    @if ($hasLeads)
                        <div class="viz-plot-sm"><canvas id="chartRating"></canvas></div>
                        <div class="viz-legend">
                            @foreach (['HOT' => '--viz-ord-top', 'WARM' => '--viz-ord-3', 'COLD' => '--viz-ord-1'] as $key => $var)
                                <span class="viz-legend-item">
                                    <span class="viz-swatch" style="background:var({{ $var }})"></span>
                                    {{ ucfirst(strtolower($key)) }}
                                    <strong>{{ $charts['rating'][$key] ?? 0 }}</strong>
                                </span>
                            @endforeach
                        </div>
                        <details>
                            <summary class="viz-table-toggle"><i class="bi bi-table"></i> Table view</summary>
                            <table class="table table-sm viz-table">
                                <thead><tr><th>Rating</th><th class="num">Leads</th><th class="num">Share</th></tr></thead>
                                <tbody>
                                    @foreach (['HOT', 'WARM', 'COLD'] as $key)
                                        <tr>
                                            <td>{{ ucfirst(strtolower($key)) }}</td>
                                            <td class="num">{{ $charts['rating'][$key] ?? 0 }}</td>
                                            <td class="num">{{ $h['total'] ? round(($charts['rating'][$key] ?? 0) / $h['total'] * 100) : 0 }}%</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </details>
                    @else
                        <div class="viz-plot-sm"><div class="viz-empty">No leads in this period.</div></div>
                    @endif
                </div>
            </div>
        </div>

        {{-- ── Goal split: doughnut, nominal categories ───────────────────── --}}
        <div class="col-12 col-md-6 col-xl-4">
            <div class="card viz-card h-100">
                <div class="card-header py-3">
                    <h3 class="viz-title">What leads want</h3>
                    <p class="viz-sub">Share of leads by stated goal</p>
                </div>
                <div class="card-body">
                    @if ($hasLeads)
                        <div class="viz-plot-sm"><canvas id="chartGoal"></canvas></div>
                        <div class="viz-legend" id="goalLegend"></div>
                        <details>
                            <summary class="viz-table-toggle"><i class="bi bi-table"></i> Table view</summary>
                            <table class="table table-sm viz-table">
                                <thead><tr><th>Goal</th><th class="num">Leads</th><th class="num">Share</th></tr></thead>
                                <tbody>
                                    @foreach ($charts['goal'] as $label => $count)
                                        <tr>
                                            <td>{{ $label }}</td>
                                            <td class="num">{{ $count }}</td>
                                            <td class="num">{{ $h['total'] ? round($count / $h['total'] * 100) : 0 }}%</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </details>
                    @else
                        <div class="viz-plot-sm"><div class="viz-empty">No leads in this period.</div></div>
                    @endif
                </div>
            </div>
        </div>

        {{-- ── Pipeline funnel: ordered stages ────────────────────────────── --}}
        <div class="col-12 col-xl-4">
            <div class="card viz-card h-100">
                <div class="card-header py-3">
                    <h3 class="viz-title">Follow-up pipeline</h3>
                    <p class="viz-sub">
                        How far leads have progressed
                        @if ($funnel['lost'])
                            · {{ $funnel['lost'] }} lost
                        @endif
                    </p>
                </div>
                <div class="card-body">
                    @if ($funnelTotal)
                        <div class="viz-plot-sm"><canvas id="chartFunnel"></canvas></div>
                        <details>
                            <summary class="viz-table-toggle"><i class="bi bi-table"></i> Table view</summary>
                            <table class="table table-sm viz-table">
                                <thead><tr><th>Stage</th><th class="num">Leads</th></tr></thead>
                                <tbody>
                                    @foreach ($funnel['stages'] as $stage => $count)
                                        <tr><td>{{ $stage }}</td><td class="num">{{ $count }}</td></tr>
                                    @endforeach
                                    <tr><td>Lost</td><td class="num">{{ $funnel['lost'] }}</td></tr>
                                </tbody>
                            </table>
                        </details>
                    @else
                        <div class="viz-plot-sm"><div class="viz-empty">No leads in this period.</div></div>
                    @endif
                </div>
            </div>
        </div>

        {{-- ── Channel mix: single-series bar ─────────────────────────────── --}}
        <div class="col-12 col-md-6 col-xl-6">
            <div class="card viz-card h-100">
                <div class="card-header py-3">
                    <h3 class="viz-title">Where enquiries come from</h3>
                    <p class="viz-sub">Submissions per channel, {{ strtolower($charts['rangeLabel']) }}</p>
                </div>
                <div class="card-body">
                    @if (array_sum($charts['channels']))
                        <div class="viz-plot-sm"><canvas id="chartChannels"></canvas></div>
                        <details>
                            <summary class="viz-table-toggle"><i class="bi bi-table"></i> Table view</summary>
                            <table class="table table-sm viz-table">
                                <thead><tr><th>Channel</th><th class="num">Submissions</th></tr></thead>
                                <tbody>
                                    @foreach ($charts['channels'] as $label => $count)
                                        <tr><td>{{ $label }}</td><td class="num">{{ $count }}</td></tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </details>
                    @else
                        <div class="viz-plot-sm"><div class="viz-empty">No submissions in this period.</div></div>
                    @endif
                </div>
            </div>
        </div>

        {{-- ── Top countries: single-series bar ───────────────────────────── --}}
        <div class="col-12 col-md-6 col-xl-6">
            <div class="card viz-card h-100">
                <div class="card-header py-3">
                    <h3 class="viz-title">Top lead locations</h3>
                    <p class="viz-sub">As entered by the lead — smaller entries grouped as “Other”</p>
                </div>
                <div class="card-body">
                    @if (count($charts['countries']))
                        <div class="viz-plot-sm"><canvas id="chartCountries"></canvas></div>
                        <details>
                            <summary class="viz-table-toggle"><i class="bi bi-table"></i> Table view</summary>
                            <table class="table table-sm viz-table">
                                <thead><tr><th>Location</th><th class="num">Leads</th></tr></thead>
                                <tbody>
                                    @foreach ($charts['countries'] as $label => $count)
                                        <tr><td>{{ $label }}</td><td class="num">{{ $count }}</td></tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </details>
                    @else
                        <div class="viz-plot-sm"><div class="viz-empty">No location data in this period.</div></div>
                    @endif
                </div>
            </div>
        </div>

    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
<script>
(function () {
    if (typeof Chart === 'undefined') return;

    const css = getComputedStyle(document.querySelector('.viz'));
    const tok = (name) => css.getPropertyValue(name).trim();

    const SURFACE = tok('--viz-surface');
    const INK     = tok('--viz-ink');
    const INK2    = tok('--viz-ink-2');
    const MUTED   = tok('--viz-muted');
    const GRID    = tok('--viz-grid');
    const AXIS    = tok('--viz-axis');

    // Ordered (one-hue) ramp and nominal slots.
    const ORD  = [tok('--viz-ord-1'), tok('--viz-ord-2'), tok('--viz-ord-3'), tok('--viz-ord-4')];
    const TOP  = tok('--viz-ord-top');
    const CAT  = [tok('--viz-cat-1'), tok('--viz-cat-2'), tok('--viz-cat-3')];
    const NEUTRAL = tok('--viz-neutral');

    Chart.defaults.font.family = "'Inter', system-ui, -apple-system, 'Segoe UI', sans-serif";
    Chart.defaults.font.size = 11;
    Chart.defaults.color = MUTED;
    Chart.defaults.animation.duration = 300;

    // Shared tooltip styling — enhances, never the only way to read a value.
    const tooltip = {
        backgroundColor: '#0b0b0b',
        titleColor: '#ffffff',
        bodyColor: '#e8e8e6',
        padding: 10,
        cornerRadius: 6,
        displayColors: true,
        boxWidth: 8,
        boxHeight: 8,
        boxPadding: 4,
    };

    const gridX = { grid: { display: false }, border: { color: AXIS }, ticks: { color: MUTED } };
    const gridY = {
        beginAtZero: true,
        grid: { color: GRID, drawTicks: false },      // solid hairline, never dashed
        border: { display: false },
        ticks: { color: MUTED, precision: 0, padding: 6 },
    };

    // ── Leads over time — stacked bar, ordered series ────────────────────
    const overTimeEl = document.getElementById('chartOverTime');
    if (overTimeEl) {
        const labels = @json($overTime['labels'] ?? []);
        const series = @json($overTime['series'] ?? []);

        new Chart(overTimeEl, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [
                    // Stacked hot → cold so the darkest (most valuable) sits at the base.
                    { label: 'Hot',  data: series.HOT  || [], backgroundColor: TOP },
                    { label: 'Warm', data: series.WARM || [], backgroundColor: ORD[2] },
                    { label: 'Cold', data: series.COLD || [], backgroundColor: ORD[0] },
                ].map(function (d) {
                    return Object.assign(d, {
                        borderColor: SURFACE,   // 2px surface gap between segments
                        borderWidth: 2,
                        borderRadius: 4,
                        borderSkipped: false,
                        maxBarThickness: 34,
                    });
                }),
            },
            options: {
                responsive: true, maintainAspectRatio: false,
                interaction: { mode: 'index', intersect: false },
                scales: {
                    x: Object.assign({ stacked: true }, gridX),
                    y: Object.assign({ stacked: true }, gridY),
                },
                plugins: {
                    legend: { display: false },   // rendered as HTML above
                    tooltip: Object.assign({}, tooltip, {
                        callbacks: {
                            footer: function (items) {
                                const total = items.reduce((sum, i) => sum + (i.parsed.y || 0), 0);
                                return 'Total  ' + total;
                            },
                        },
                    }),
                },
            },
        });
    }

    // ── Doughnut factory — part-to-whole at a glance, ≤ 6 segments ───────
    function doughnut(el, labels, values, colors) {
        return new Chart(el, {
            type: 'doughnut',
            plugins: [centreTotal],   // scoped to this chart, never registered globally
            data: {
                labels: labels,
                datasets: [{
                    data: values,
                    backgroundColor: colors,
                    borderColor: SURFACE,   // 2px surface gap between slices
                    borderWidth: 2,
                    hoverOffset: 6,
                }],
            },
            options: {
                responsive: true, maintainAspectRatio: false,
                cutout: '62%',
                plugins: {
                    legend: { display: false },
                    tooltip: Object.assign({}, tooltip, {
                        callbacks: {
                            label: function (ctx) {
                                const total = ctx.dataset.data.reduce((a, b) => a + b, 0);
                                const pct = total ? Math.round(ctx.parsed / total * 100) : 0;
                                return ' ' + ctx.label + '  ' + ctx.parsed + '  (' + pct + '%)';
                            },
                        },
                    }),
                },
            },
        });
    }

    // Centre total — the headline the doughnut is really about. Declared
    // before doughnut() runs and attached per-chart (see above): registering
    // it globally would paint the total onto the bar charts too.
    const centreTotal = {
        id: 'centreTotal',
        afterDatasetDraw: function (chart) {
            const { ctx, chartArea } = chart;
            const total = chart.data.datasets[0].data.reduce((a, b) => a + b, 0);
            const x = (chartArea.left + chartArea.right) / 2;
            const y = (chartArea.top + chartArea.bottom) / 2;
            ctx.save();
            ctx.textAlign = 'center'; ctx.textBaseline = 'middle';
            ctx.fillStyle = INK;
            ctx.font = '700 22px ' + Chart.defaults.font.family;
            ctx.fillText(total, x, y - 6);
            ctx.fillStyle = MUTED;
            ctx.font = '500 10px ' + Chart.defaults.font.family;
            ctx.fillText('LEADS', x, y + 13);
            ctx.restore();
        },
    };

    const ratingEl = document.getElementById('chartRating');
    if (ratingEl) {
        const r = @json($charts['rating'] ?? []);
        // Ordered scale: hot = darkest step, cold = lightest.
        doughnut(ratingEl, ['Hot', 'Warm', 'Cold'],
            [r.HOT || 0, r.WARM || 0, r.COLD || 0], [TOP, ORD[2], ORD[0]]);
    }

    const goalEl = document.getElementById('chartGoal');
    if (goalEl) {
        const g = @json($charts['goal'] ?? []);
        const labels = Object.keys(g), values = Object.values(g);
        // Nominal categories take categorical slots; the residual "Other"
        // bucket takes neutral gray rather than a 4th hue.
        const colors = labels.map((l, i) => l === 'Other' ? NEUTRAL : CAT[i % CAT.length]);
        doughnut(goalEl, labels, values, colors);

        const legend = document.getElementById('goalLegend');
        if (legend) {
            legend.innerHTML = labels.map(function (l, i) {
                return '<span class="viz-legend-item"><span class="viz-swatch" style="background:'
                    + colors[i] + '"></span>' + l + ' <strong>' + values[i] + '</strong></span>';
            }).join('');
        }
    }

    // ── Horizontal bar factory ──────────────────────────────────────────
    function hbar(el, labels, values, colors) {
        return new Chart(el, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [{
                    data: values,
                    backgroundColor: colors,
                    borderRadius: 4,          // rounded data-end
                    borderSkipped: false,
                    maxBarThickness: 22,
                }],
            },
            options: {
                indexAxis: 'y',
                responsive: true, maintainAspectRatio: false,
                scales: {
                    x: {
                        beginAtZero: true,
                        grid: { color: GRID, drawTicks: false },
                        border: { display: false },
                        ticks: { color: MUTED, precision: 0 },
                    },
                    y: {
                        grid: { display: false },
                        border: { color: AXIS },
                        ticks: { color: INK2, font: { size: 11 } },
                    },
                },
                plugins: {
                    legend: { display: false },   // single series — no legend needed
                    tooltip: Object.assign({}, tooltip, {
                        callbacks: { label: (ctx) => '  ' + ctx.parsed.x },
                    }),
                },
            },
        });
    }

    const funnelEl = document.getElementById('chartFunnel');
    if (funnelEl) {
        const f = @json($funnel['stages'] ?? []);
        const lost = @json($funnel['lost'] ?? 0);
        const labels = Object.keys(f), values = Object.values(f);
        // Ordered stages take the ordinal ramp; "Lost" is an exit, not a
        // stage further along, so it takes neutral gray.
        const colors = values.map((_, i) => ORD[Math.min(i, ORD.length - 1)]);
        if (lost > 0) { labels.push('Lost'); values.push(lost); colors.push(NEUTRAL); }
        hbar(funnelEl, labels, values, colors);
    }

    const channelsEl = document.getElementById('chartChannels');
    if (channelsEl) {
        const c = @json($charts['channels'] ?? []);
        // Nominal categories, one series → slot 1 for every bar.
        hbar(channelsEl, Object.keys(c), Object.values(c), ORD[2]);
    }

    const countriesEl = document.getElementById('chartCountries');
    if (countriesEl) {
        const c = @json($charts['countries'] ?? []);
        const labels = Object.keys(c);
        const colors = labels.map((l) => l === 'Other' ? NEUTRAL : ORD[2]);
        hbar(countriesEl, labels, Object.values(c), colors);
    }
})();
</script>
@endpush
