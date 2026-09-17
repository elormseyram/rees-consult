{{--
    Animated lead-temperature badge.

    Usage:  @include('admin.leads.partials.rating-badge', ['rating' => $lead->rating])
            optional: 'size' => 'lg'   (larger badge for the detail page header)

    HOT flickers like a flame, COLD shimmers like frost, WARM sits still — the
    motion is the signal, so only the two extremes move and the middle stays
    quiet. The rating word is always rendered, so nothing depends on colour or
    animation alone, and all motion is disabled under prefers-reduced-motion.

    The colours here are deliberately fire/ice semantics (a status marker with
    icon + label), not the ordered blue ramp the dashboard charts use for the
    same field — a chart encodes a quantitative scale, a badge names a state.
--}}
@php
    $rating = strtoupper($rating ?? '');
    $large  = ($size ?? '') === 'lg';
@endphp

@once
    @push('styles')
    <style>
        .rating-badge {
            --rb-glow: transparent;
            display: inline-flex;
            align-items: center;
            gap: .3rem;
            padding: .28rem .55rem;
            border-radius: 999px;
            font-size: .7rem;
            font-weight: 700;
            letter-spacing: .4px;
            line-height: 1;
            white-space: nowrap;
            position: relative;
        }
        .rating-badge.rb-lg { font-size: .85rem; padding: .4rem .8rem; gap: .4rem; }

        /* ── HOT — flickering flame ─────────────────────────────────────── */
        .rating-badge.rb-hot {
            color: #fff;
            background: linear-gradient(135deg, #e34948 0%, #eb6834 55%, #f59d20 100%);
            box-shadow: 0 0 0 0 rgba(227, 73, 72, .45);
            animation: rb-pulse 2.4s ease-out infinite;
        }
        .rb-flame {
            position: relative;
            display: inline-block;
            width: 1em;
            height: 1em;
            flex-shrink: 0;
        }
        .rb-flame i {
            position: absolute;
            inset: 0;
            display: block;
            transform-origin: 50% 85%;   /* flames pivot at their base */
        }
        .rb-flame .rb-flame-back {
            color: #ffd08a;
            filter: blur(.6px);
            animation: rb-flicker-back 1.15s ease-in-out infinite;
        }
        .rb-flame .rb-flame-front {
            color: #fff6e0;
            animation: rb-flicker-front .78s ease-in-out infinite;
        }

        @keyframes rb-flicker-back {
            0%, 100% { transform: scale(1)      translateY(0)      rotate(-1deg); opacity: .85; }
            30%      { transform: scaleY(1.16) scaleX(.94) translateY(-.5px) rotate(1.5deg); opacity: 1; }
            55%      { transform: scaleY(.92)  scaleX(1.06) translateY(.5px)  rotate(-2deg); opacity: .75; }
            80%      { transform: scaleY(1.1)  scaleX(.97)  translateY(-.3px) rotate(1deg);  opacity: .95; }
        }
        @keyframes rb-flicker-front {
            0%, 100% { transform: scale(.82) translateY(.5px)  rotate(1deg);  opacity: .9; }
            35%      { transform: scale(.9)  translateY(-.4px) rotate(-2deg); opacity: 1; }
            70%      { transform: scale(.76) translateY(.6px)  rotate(2deg);  opacity: .8; }
        }
        @keyframes rb-pulse {
            0%   { box-shadow: 0 0 0 0    rgba(227, 73, 72, .45); }
            70%  { box-shadow: 0 0 0 7px  rgba(227, 73, 72, 0); }
            100% { box-shadow: 0 0 0 0    rgba(227, 73, 72, 0); }
        }

        /* ── COLD — drifting frost ──────────────────────────────────────── */
        .rating-badge.rb-cold {
            color: #0d366b;
            background: linear-gradient(135deg, #dbeafe 0%, #bfdcf8 55%, #e8f4ff 100%);
            overflow: hidden;
        }
        .rating-badge.rb-cold .rb-ice {
            display: inline-block;
            color: #2a78d6;
            animation: rb-spin 14s linear infinite;
            flex-shrink: 0;
        }
        /* Shimmer sweep — a slow highlight crossing the frosted badge. */
        .rating-badge.rb-cold::after {
            content: "";
            position: absolute;
            top: 0; bottom: 0;
            left: -60%;
            width: 45%;
            background: linear-gradient(100deg, transparent, rgba(255, 255, 255, .85), transparent);
            transform: skewX(-18deg);
            animation: rb-shimmer 4.5s ease-in-out infinite;
            pointer-events: none;
        }
        @keyframes rb-spin   { to { transform: rotate(360deg); } }
        @keyframes rb-shimmer {
            0%        { left: -60%; }
            55%, 100% { left: 130%; }
        }

        /* ── WARM — deliberately still ──────────────────────────────────── */
        .rating-badge.rb-warm {
            color: #6b4300;
            background: linear-gradient(135deg, #fde9b8 0%, #f8d488 100%);
        }
        .rating-badge.rb-none { color: #52514e; background: #eceef1; }

        /* Row accent so hot leads are findable while scanning the table. */
        tr.lead-hot > td:first-child  { box-shadow: inset 3px 0 0 0 #eb6834; }
        tr.lead-cold > td:first-child { box-shadow: inset 3px 0 0 0 #bfdcf8; }

        /* Motion is an enhancement — never a requirement. */
        @media (prefers-reduced-motion: reduce) {
            .rating-badge,
            .rating-badge .rb-ice,
            .rating-badge .rb-flame i,
            .rating-badge.rb-cold::after {
                animation: none !important;
            }
            .rating-badge.rb-cold::after { display: none; }
            .rb-flame .rb-flame-front { transform: scale(.82); }
        }
    </style>
    @endpush
@endonce

@if ($rating === 'HOT')
    <span class="rating-badge rb-hot {{ $large ? 'rb-lg' : '' }}"
          title="Hot lead — willing and able to buy. Call these first.">
        <span class="rb-flame" aria-hidden="true">
            <i class="bi bi-fire rb-flame-back"></i>
            <i class="bi bi-fire rb-flame-front"></i>
        </span>
        HOT
    </span>
@elseif ($rating === 'COLD')
    <span class="rating-badge rb-cold {{ $large ? 'rb-lg' : '' }}"
          title="Cold lead — low intent or no budget yet. Nurture, don’t chase.">
        <i class="bi bi-snow rb-ice" aria-hidden="true"></i>
        COLD
    </span>
@elseif ($rating === 'WARM')
    <span class="rating-badge rb-warm {{ $large ? 'rb-lg' : '' }}"
          title="Warm lead — interested, but not ready yet. Follow up.">
        <i class="bi bi-thermometer-half" aria-hidden="true"></i>
        WARM
    </span>
@else
    <span class="rating-badge rb-none {{ $large ? 'rb-lg' : '' }}">
        {{ $rating !== '' ? $rating : 'UNRATED' }}
    </span>
@endif
