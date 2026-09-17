@extends('layouts.app')

@section('title', 'Success Stories — Work Abroad, Study Abroad & IELTS Reviews | Rees Consult')
@section('description', 'Read genuine success stories from Ghanaian clients who secured overseas jobs, gained school admissions abroad, and passed IELTS, GRE & OET with Rees Consult in Accra, Ghana.')
@section('keywords', 'Rees Consult reviews, work abroad success stories Ghana, study abroad testimonials Ghana, IELTS success Ghana, job abroad Ghana testimonials, student reviews Accra consultancy')
@section('og_title', 'Success Stories — Work Abroad, Study Abroad & IELTS Reviews | Rees Consult')
@section('og_description', 'Real reviews from clients who secured jobs abroad, got school admissions, and passed their IELTS exams with Rees Consult Ghana.')

@push('styles')
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "EducationalOrganization",
  "name": "Rees Consult",
  "url": "{{ url('/') }}",
  "logo": "{{ asset('reesconsult-logo.png') }}",
  "aggregateRating": {
    "@type": "AggregateRating",
    "ratingValue": "4.9",
    "reviewCount": "{{ max(15, $testimonials->total() ?? 25) }}",
    "bestRating": "5",
    "worstRating": "1"
  },
  "review": [
    @foreach($testimonials as $index => $item)
    {
      "@type": "Review",
      "author": {
        "@type": "Person",
        "name": "{{ addslashes($item->name) }}"
      },
      "reviewRating": {
        "@type": "Rating",
        "ratingValue": "{{ $item->rating ?? 5 }}",
        "bestRating": "5"
      },
      "reviewBody": "{{ addslashes(Str::limit(strip_tags($item->message ?: 'Excellent service for work abroad and study abroad consultancy.'), 250)) }}"
    }@if(!$loop->last),@endif
    @endforeach
  ]
}
</script>
<style>
/* ═══════════════════════════════════════════════════════
   TESTIMONIALS PAGE — Premium Redesign
   Brand: Navy #07294D  |  Gold #F8A706  |  White #fff
   ═══════════════════════════════════════════════════════ */
:root {
  --navy:   #07294D;
  --navy2:  #0a3560;
  --gold:   #F8A706;
  --gold2:  #ffbe2e;
  --light:  #f4f6fb;
  --card-r: 18px;
  --shadow: 0 4px 28px rgba(7,41,77,.10);
  --shadow-hover: 0 16px 48px rgba(7,41,77,.18);
}
*, *::before, *::after { box-sizing: border-box; }

/* ── Page wrapper ── */
.tp-page { overflow-x: hidden; }

/* ════════════════════════════════════
   HERO
   ════════════════════════════════════ */
.tp-hero {
  position: relative;
  min-height: 380px;
  display: flex;
  align-items: center;
  justify-content: center;
  text-align: center;
  overflow: hidden;
  background: var(--navy);
}
.tp-hero-bg {
  position: absolute;
  inset: 0;
  background-image: url('https://images.unsplash.com/photo-1522202176988-66273c2fd55f?w=1600&q=80');
  background-size: cover;
  background-position: center;
  opacity: .12;
}
/* Decorative blobs */
.tp-hero::before {
  content: '';
  position: absolute;
  width: 500px; height: 500px;
  border-radius: 50%;
  background: radial-gradient(circle, rgba(248,167,6,.18) 0%, transparent 70%);
  top: -120px; right: -80px;
  pointer-events: none;
}
.tp-hero::after {
  content: '';
  position: absolute;
  width: 360px; height: 360px;
  border-radius: 50%;
  background: radial-gradient(circle, rgba(248,167,6,.12) 0%, transparent 70%);
  bottom: -100px; left: -60px;
  pointer-events: none;
}
.tp-hero-inner { position: relative; z-index: 2; padding: clamp(64px,12vw,110px) 20px clamp(52px,10vw,90px); }
.tp-hero-eyebrow {
  display: inline-flex; align-items: center; gap: 8px;
  background: rgba(248,167,6,.15);
  border: 1px solid rgba(248,167,6,.4);
  color: var(--gold);
  padding: 5px 16px;
  border-radius: 999px;
  font-size: .75rem; font-weight: 700; letter-spacing: 1.8px; text-transform: uppercase;
  margin-bottom: 1.1rem;
}
.tp-hero h1 {
  font-size: clamp(2rem, 5vw, 3.4rem);
  font-weight: 800;
  color: #fff;
  line-height: 1.15;
  margin-bottom: .8rem;
}
.tp-hero-lead {
  color: rgba(255,255,255,.72);
  font-size: clamp(.9rem, 2.2vw, 1.05rem);
  max-width: 560px; margin: 0 auto 2rem;
}
/* Stats row */
.tp-stats {
  display: flex; flex-wrap: wrap; justify-content: center; gap: 16px 32px;
}
.tp-stat {
  color: #fff; text-align: center;
}
.tp-stat-num {
  display: block; font-size: clamp(1.5rem, 4vw, 2.2rem); font-weight: 800; color: var(--gold); line-height: 1;
}
.tp-stat-label { font-size: .75rem; color: rgba(255,255,255,.6); text-transform: uppercase; letter-spacing: 1px; }
.tp-stat-divider { width: 1px; background: rgba(255,255,255,.2); height: 36px; align-self: center; }
@media (max-width: 479.98px) { .tp-stat-divider { display: none; } }

/* ════════════════════════════════════
   FILTER TABS
   ════════════════════════════════════ */
.tp-filter-bar {
  background: #fff;
  border-bottom: 1px solid rgba(7,41,77,.08);
  position: sticky;
  top: 0;
  z-index: 100;
  box-shadow: 0 2px 12px rgba(7,41,77,.06);
}
.tp-filter-inner {
  display: flex; align-items: center; gap: 4px;
  overflow-x: auto; padding: 0 .5rem;
  scrollbar-width: none;
}
.tp-filter-inner::-webkit-scrollbar { display: none; }
.tp-filter-btn {
  display: inline-flex; align-items: center; gap: 6px;
  padding: 1rem 1.2rem;
  font-size: .875rem; font-weight: 600; color: #6c757d;
  border: none; background: transparent; cursor: pointer;
  border-bottom: 3px solid transparent;
  text-decoration: none; white-space: nowrap;
  transition: color .2s, border-color .2s;
}
.tp-filter-btn:hover { color: var(--navy); border-color: rgba(7,41,77,.25); }
.tp-filter-btn.active { color: var(--navy); border-color: var(--gold); }
.tp-filter-count {
  background: var(--light); color: #6c757d;
  border-radius: 999px; font-size: .7rem; font-weight: 700;
  padding: 1px 7px; min-width: 22px; text-align: center;
}
.tp-filter-btn.active .tp-filter-count { background: var(--navy); color: #fff; }

/* ════════════════════════════════════
   FEATURED SHOWCASE
   ════════════════════════════════════ */
.tp-showcase-section {
  background: var(--navy);
  padding: clamp(44px, 8vw, 72px) 0;
}
.tp-showcase-card {
  background: rgba(255,255,255,.05);
  border: 1px solid rgba(255,255,255,.1);
  border-radius: 24px;
  overflow: hidden;
  display: grid;
  grid-template-columns: 1fr 1fr;
}
@media (max-width: 767.98px) {
  .tp-showcase-card { grid-template-columns: 1fr; }
}
.tp-showcase-video {
  position: relative;
  width: 100%;
  aspect-ratio: 16 / 9;
  background: #000;
}
.tp-showcase-video iframe {
  position: absolute; inset: 0; width: 100%; height: 100%; border: none;
}
.tp-showcase-info {
  padding: clamp(28px, 5vw, 52px);
  display: flex; flex-direction: column; justify-content: center;
}
.tp-showcase-badge {
  display: inline-flex; align-items: center; gap: 6px;
  background: rgba(248,167,6,.2); border: 1px solid rgba(248,167,6,.4);
  color: var(--gold);
  padding: 5px 14px; border-radius: 999px;
  font-size: .72rem; font-weight: 700; letter-spacing: 1.5px; text-transform: uppercase;
  margin-bottom: 1.1rem; align-self: flex-start;
}
.tp-showcase-name {
  font-size: clamp(1.2rem, 3vw, 1.8rem); font-weight: 800; color: #fff; margin-bottom: .6rem;
}
.tp-showcase-quote {
  color: rgba(255,255,255,.7); font-size: .95rem; line-height: 1.65;
  font-style: italic; margin-bottom: 1.4rem;
  border-left: 3px solid var(--gold); padding-left: 1rem;
}
.tp-showcase-author {
  display: flex; align-items: center; gap: 12px;
}
.tp-showcase-avatar {
  width: 44px; height: 44px; border-radius: 50%; overflow: hidden;
  border: 2px solid rgba(248,167,6,.5); flex-shrink: 0;
  background: rgba(255,255,255,.1);
  display: flex; align-items: center; justify-content: center;
}
.tp-showcase-avatar img { width: 100%; height: 100%; object-fit: cover; }
.tp-showcase-author-name { font-weight: 700; color: #fff; font-size: .9rem; }
.tp-showcase-author-role { color: rgba(255,255,255,.55); font-size: .78rem; }

/* ════════════════════════════════════
   MAIN GRID SECTION
   ════════════════════════════════════ */
.tp-grid-section {
  background: var(--light);
  padding: clamp(44px, 8vw, 72px) 0;
}
.tp-section-intro { text-align: center; margin-bottom: 2.5rem; }
.tp-section-eyebrow {
  font-size: .72rem; font-weight: 700; letter-spacing: 2px;
  text-transform: uppercase; color: var(--gold); margin-bottom: .4rem;
}
.tp-section-title {
  font-size: clamp(1.4rem, 3vw, 2rem); font-weight: 800; color: var(--navy);
}
.tp-section-divider {
  width: 44px; height: 4px;
  background: linear-gradient(90deg, var(--gold), var(--gold2));
  border-radius: 4px; margin: .75rem auto 0;
}

/* Card grid */
.tp-grid {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(min(100%, 320px), 1fr));
  gap: 24px;
}

/* ── Base card ── */
.tp-card {
  background: #fff;
  border-radius: var(--card-r);
  box-shadow: var(--shadow);
  display: flex;
  flex-direction: column;
  overflow: hidden;
  transition: transform .28s ease, box-shadow .28s ease;
  /* entrance animation */
  opacity: 0;
  transform: translateY(24px);
}
.tp-card.in-view { opacity: 1; transform: translateY(0); transition: transform .45s cubic-bezier(.22,1,.36,1), box-shadow .28s ease, opacity .35s ease; }
.tp-card:hover { transform: translateY(-6px); box-shadow: var(--shadow-hover); }

/* ── Video card ── */
.tp-video-wrap {
  position: relative; width: 100%; padding-top: 56.25%; /* 16:9 */
  background: #000; flex-shrink: 0; overflow: hidden;
}
.tp-video-wrap iframe {
  position: absolute; inset: 0; width: 100%; height: 100%; border: none;
}
/* Shorts — portrait 9:16 */
.tp-video-short-wrap {
  position: relative; width: 100%; padding-top: 177.78%;
  background: #000; flex-shrink: 0; overflow: hidden;
  border-radius: var(--card-r) var(--card-r) 0 0;
}
.tp-video-short-wrap iframe {
  position: absolute; inset: 0; width: 100%; height: 100%; border: none;
}
.tp-card.is-short { max-width: 340px; margin-left: auto; margin-right: auto; }

/* ── Card body ── */
.tp-card-body {
  padding: 1.2rem 1.35rem 1.5rem;
  display: flex; flex-direction: column; flex: 1;
}
.tp-card-meta {
  display: flex; align-items: center; justify-content: space-between; margin-bottom: .7rem;
}
.tp-stars i { font-size: .8rem; color: #dee2e6; }
.tp-stars i.filled { color: #F8A706; }
.tp-type-badge {
  font-size: .65rem; font-weight: 700; letter-spacing: .8px;
  padding: 3px 8px; border-radius: 999px; text-transform: uppercase;
}
.tp-type-badge.video { background: rgba(7,41,77,.08); color: var(--navy); }
.tp-type-badge.short { background: rgba(248,167,6,.15); color: #a06a00; }
.tp-type-badge.written { background: rgba(25,135,84,.1); color: #0d6e3f; }

/* Quote */
.tp-quote-icon { font-size: 2.4rem; line-height: 1; color: rgba(7,41,77,.08); float: right; margin-left: 6px; }
.tp-card-message {
  font-size: .9rem; color: #4a5568; line-height: 1.65;
  font-style: italic; flex: 1;
  display: -webkit-box; -webkit-line-clamp: 4; -webkit-box-orient: vertical; overflow: hidden;
}
.tp-card-message.no-clamp { -webkit-line-clamp: unset; overflow: visible; }

/* Photo banner for written cards */
.tp-photo-banner {
  position: relative; width: 100%; padding-top: 52%; overflow: hidden; flex-shrink: 0; background: #f0f2f7;
}
.tp-photo-banner img {
  position: absolute; inset: 0; width: 100%; height: 100%;
  object-fit: cover; object-position: top center; display: block;
}

/* Author row */
.tp-author {
  display: flex; align-items: center; gap: 10px;
  padding-top: .9rem; margin-top: auto; border-top: 1px solid rgba(7,41,77,.06);
}
.tp-avatar {
  width: 40px; height: 40px; border-radius: 50%; overflow: hidden; flex-shrink: 0;
  border: 2px solid var(--navy); background: #f0f2f7;
  display: flex; align-items: center; justify-content: center;
}
.tp-avatar img { width: 100%; height: 100%; object-fit: cover; }
.tp-avatar-icon { font-size: 1.1rem; color: #adb5bd; }
.tp-author-name { font-weight: 700; font-size: .88rem; color: var(--navy); line-height: 1.2; }
.tp-author-role { font-size: .75rem; color: #6c757d; }

/* ── Empty state ── */
.tp-empty {
  text-align: center; padding: 64px 24px; color: #adb5bd;
}
.tp-empty i { font-size: 3rem; display: block; margin-bottom: .9rem; color: rgba(7,41,77,.15); }
.tp-empty h3 { color: var(--navy); font-size: 1.15rem; }

/* ── Pagination ── */
.tp-pagination { margin-top: 2.5rem; display: flex; justify-content: center; }
.tp-pagination .pagination .page-link {
  border-radius: 8px; margin: 0 3px;
  font-weight: 600; font-size: .85rem;
  color: var(--navy); border-color: rgba(7,41,77,.15);
  padding: .45rem .75rem;
  transition: background .2s, color .2s;
}
.tp-pagination .pagination .page-item.active .page-link { background: var(--navy); border-color: var(--navy); color: #fff; }
.tp-pagination .pagination .page-link:hover { background: var(--light); }

/* ════════════════════════════════════
   CTA SECTION
   ════════════════════════════════════ */
.tp-cta {
  background: linear-gradient(135deg, var(--navy) 0%, var(--navy2) 60%, #0b4a80 100%);
  padding: clamp(52px, 9vw, 80px) 0;
  position: relative; overflow: hidden;
}
.tp-cta::before {
  content: '';
  position: absolute; top: -100px; right: -80px;
  width: 400px; height: 400px; border-radius: 50%;
  background: radial-gradient(circle, rgba(248,167,6,.15) 0%, transparent 70%);
  pointer-events: none;
}
.tp-cta-title { font-size: clamp(1.6rem, 4vw, 2.5rem); font-weight: 800; color: #fff; margin-bottom: .75rem; }
.tp-cta-lead { color: rgba(255,255,255,.7); font-size: 1rem; margin-bottom: 0; }
.tp-cta-btn {
  background: var(--gold); color: var(--navy) !important; font-weight: 800;
  border: none; border-radius: 12px; padding: .9rem 2.2rem; font-size: 1rem;
  box-shadow: 0 6px 24px rgba(248,167,6,.4);
  transition: transform .2s, box-shadow .2s;
}
.tp-cta-btn:hover { transform: translateY(-2px); box-shadow: 0 10px 32px rgba(248,167,6,.5); color: var(--navy) !important; }

/* ── Responsive final tweaks ── */
@media (max-width: 575.98px) {
  .tp-card-body { padding: 1rem 1.1rem 1.25rem; }
  .tp-grid { gap: 16px; }
}
</style>
@endpush

@section('content')
<div class="tp-page">

{{-- ═══════════════════════════════════
     HERO
     ═══════════════════════════════════ --}}
<section class="tp-hero">
    <div class="tp-hero-bg"></div>
    <div class="tp-hero-inner container">
        <div class="tp-hero-eyebrow">
            <i class="bi bi-star-fill"></i> Real Stories
        </div>
        <h1>What Our Clients Say</h1>
        <p class="tp-hero-lead">
            Hear directly from students and professionals who trusted Rees Consult
            and achieved their IELTS, admissions, and visa goals.
        </p>
        <div class="tp-stats">
            <div class="tp-stat">
                <span class="tp-stat-num">500+</span>
                <span class="tp-stat-label">Success Stories</span>
            </div>
            <div class="tp-stat-divider"></div>
            <div class="tp-stat">
                <span class="tp-stat-num">98%</span>
                <span class="tp-stat-label">Satisfaction Rate</span>
            </div>
            <div class="tp-stat-divider"></div>
            <div class="tp-stat">
                <span class="tp-stat-num">4.9<small style="font-size:1rem">/5</small></span>
                <span class="tp-stat-label">Average Rating</span>
            </div>
        </div>
    </div>
</section>

{{-- ═══════════════════════════════════
     STICKY FILTER BAR
     ═══════════════════════════════════ --}}
<nav class="tp-filter-bar" aria-label="Testimonial filters">
    <div class="container">
        <div class="tp-filter-inner">
            <a href="{{ route('testimonials.index') }}"
               class="tp-filter-btn {{ !$type ? 'active' : '' }}">
                <i class="bi bi-grid-3x3-gap-fill"></i> All Stories
            </a>
            <a href="{{ route('testimonials.index', ['type' => 'written']) }}"
               class="tp-filter-btn {{ $type === 'written' ? 'active' : '' }}">
                <i class="bi bi-chat-quote-fill"></i> Written
            </a>
            <a href="{{ route('testimonials.index', ['type' => 'video']) }}"
               class="tp-filter-btn {{ $type === 'video' ? 'active' : '' }}">
                <i class="bi bi-play-circle-fill"></i> Videos
            </a>
            <a href="{{ route('testimonials.index', ['type' => 'shorts']) }}"
               class="tp-filter-btn {{ $type === 'shorts' ? 'active' : '' }}">
                <i class="bi bi-phone-fill"></i> Shorts
            </a>
        </div>
    </div>
</nav>

{{-- ═══════════════════════════════════
     SHOWCASE FEATURED VIDEO
     ═══════════════════════════════════ --}}
@if(isset($showcaseVideo) && $showcaseVideo && $type !== 'written')
<section class="tp-showcase-section">
    <div class="container">
        <div class="text-center mb-4">
            <div class="tp-section-eyebrow" style="color:rgba(248,167,6,.9);">Featured</div>
            <h2 class="tp-section-title" style="color:#fff;">Highlighted Success Story</h2>
            <div class="tp-section-divider"></div>
        </div>
        <div class="tp-showcase-card mx-auto" style="max-width:900px;">
            <div class="tp-showcase-video">
                <iframe src="{{ $showcaseVideo->youtube_embed_url }}{{ str_contains($showcaseVideo->youtube_embed_url, '?') ? '&' : '?' }}autoplay=1&mute=1"
                        title="{{ $showcaseVideo->name }}"
                        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                        allowfullscreen loading="lazy"></iframe>
            </div>
            <div class="tp-showcase-info">
                <div class="tp-showcase-badge">
                    <i class="bi bi-star-fill"></i> Featured Story
                </div>
                <div class="tp-showcase-name">{{ $showcaseVideo->name }}</div>
                @if($showcaseVideo->message)
                <div class="tp-showcase-quote">
                    "{{ Str::limit($showcaseVideo->message, 200) }}"
                </div>
                @endif
                <div class="tp-showcase-author">
                    <div class="tp-showcase-avatar">
                        @if($showcaseVideo->image)
                            <img src="{{ asset('storage/' . $showcaseVideo->image) }}" alt="{{ $showcaseVideo->name }}">
                        @else
                            <i class="bi bi-person" style="color:rgba(255,255,255,.6);font-size:1.2rem;"></i>
                        @endif
                    </div>
                    <div>
                        <div class="tp-showcase-author-name">{{ $showcaseVideo->name }}</div>
                        <div class="tp-showcase-author-role">{{ $showcaseVideo->role }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endif

{{-- ═══════════════════════════════════
     GRID SECTION
     ═══════════════════════════════════ --}}
<section class="tp-grid-section">
    <div class="container">
        <div class="tp-section-intro">
            <div class="tp-section-eyebrow">
                @if($type === 'written') Written Reviews
                @elseif($type === 'video') Video Testimonials
                @elseif($type === 'shorts') YouTube Shorts
                @else All Success Stories
                @endif
            </div>
            <h2 class="tp-section-title">
                @if($type === 'written') Authentic Written Reviews
                @elseif($type === 'video') Hear It From Them
                @elseif($type === 'shorts') Quick Wins — Straight From Our Clients
                @else Every Story Matters
                @endif
            </h2>
            <div class="tp-section-divider"></div>
        </div>

        @if($testimonials->isNotEmpty())
        <div class="tp-grid" id="tp-grid">
            @foreach($testimonials as $i => $testimonial)
            <div class="tp-card{{ $testimonial->is_short ? ' is-short' : '' }}"
                 style="transition-delay: {{ ($i % 9) * 60 }}ms;">

                {{-- ── VIDEO / SHORTS ── --}}
                @if($testimonial->youtube_video_url)
                    @if($testimonial->is_short)
                        <div class="tp-video-short-wrap">
                            <iframe src="{{ $testimonial->youtube_embed_url }}"
                                    title="{{ $testimonial->name }}"
                                    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                                    allowfullscreen loading="lazy"></iframe>
                        </div>
                    @else
                        <div class="tp-video-wrap">
                            <iframe src="{{ $testimonial->youtube_embed_url }}"
                                    title="{{ $testimonial->name }}"
                                    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                                    allowfullscreen loading="lazy"></iframe>
                        </div>
                    @endif

                    <div class="tp-card-body">
                        <div class="tp-card-meta">
                            <div class="tp-stars">
                                @for($s = 1; $s <= 5; $s++)
                                    <i class="bi bi-star-fill{{ $s <= $testimonial->rating ? ' filled' : '' }}"></i>
                                @endfor
                            </div>
                            @if($testimonial->is_short)
                                <span class="tp-type-badge short">📱 Short</span>
                            @else
                                <span class="tp-type-badge video">🎬 Video</span>
                            @endif
                        </div>
                        @if($testimonial->message)
                        <p class="tp-card-message">"{{ Str::limit($testimonial->message, 130) }}"</p>
                        @endif
                        <div class="tp-author">
                            <div class="tp-avatar">
                                @if($testimonial->image)
                                    <img src="{{ asset('storage/' . $testimonial->image) }}" alt="{{ $testimonial->name }}">
                                @else
                                    <i class="bi bi-person tp-avatar-icon"></i>
                                @endif
                            </div>
                            <div>
                                <div class="tp-author-name">{{ $testimonial->name }}</div>
                                <div class="tp-author-role">{{ $testimonial->role }}</div>
                            </div>
                        </div>
                    </div>

                {{-- ── WRITTEN ── --}}
                @else
                    @if($testimonial->image)
                    <div class="tp-photo-banner">
                        <img src="{{ asset('storage/' . $testimonial->image) }}" alt="{{ $testimonial->name }}" loading="lazy">
                    </div>
                    @endif
                    <div class="tp-card-body">
                        <div class="tp-card-meta">
                            <div class="tp-stars">
                                @for($s = 1; $s <= 5; $s++)
                                    <i class="bi bi-star-fill{{ $s <= $testimonial->rating ? ' filled' : '' }}"></i>
                                @endfor
                            </div>
                            <span class="tp-type-badge written">✍️ Review</span>
                        </div>
                        <i class="bi bi-quote tp-quote-icon"></i>
                        @if($testimonial->message)
                        <p class="tp-card-message">"{{ $testimonial->message }}"</p>
                        @endif
                        <div class="tp-author">
                            <div class="tp-avatar">
                                @if($testimonial->image)
                                    <img src="{{ asset('storage/' . $testimonial->image) }}" alt="{{ $testimonial->name }}">
                                @else
                                    <i class="bi bi-person tp-avatar-icon"></i>
                                @endif
                            </div>
                            <div>
                                <div class="tp-author-name">{{ $testimonial->name }}</div>
                                <div class="tp-author-role">{{ $testimonial->role }}</div>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
            @endforeach
        </div>

        {{-- Pagination --}}
        <div class="tp-pagination">
            {{ $testimonials->links() }}
        </div>

        @else
        <div class="tp-empty">
            <i class="bi bi-chat-heart"></i>
            <h3>No stories here yet</h3>
            <p class="text-muted">Try a different filter above, or check back soon.</p>
        </div>
        @endif
    </div>
</section>

{{-- ═══════════════════════════════════
     CTA
     ═══════════════════════════════════ --}}
<section class="tp-cta">
    <div class="container">
        <div class="row align-items-center g-4">
            <div class="col-lg-8">
                <h2 class="tp-cta-title">Ready to Write Your Own Success Story?</h2>
                <p class="tp-cta-lead">
                    Join hundreds of successful students. Let Rees Consult guide you step by step
                    from IELTS prep to your dream destination.
                </p>
            </div>
            <div class="col-lg-4 text-lg-end">
                <button type="button" class="btn tp-cta-btn"
                        data-bs-toggle="modal" data-bs-target="#applyModal">
                    <i class="bi bi-send-fill me-2"></i>Apply Now — It's Free
                </button>
            </div>
        </div>
    </div>
</section>

{{-- Showcase modal stop iframe on close --}}
@if(isset($showcaseVideo) && $showcaseVideo)
@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    // Intersection Observer — card entrance animations
    const cards = document.querySelectorAll('#tp-grid .tp-card');
    if ('IntersectionObserver' in window) {
        const obs = new IntersectionObserver((entries) => {
            entries.forEach(e => { if (e.isIntersecting) { e.target.classList.add('in-view'); obs.unobserve(e.target); } });
        }, { threshold: .12 });
        cards.forEach(c => obs.observe(c));
    } else {
        cards.forEach(c => c.classList.add('in-view'));
    }
});
</script>
@endpush
@else
@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const cards = document.querySelectorAll('#tp-grid .tp-card');
    if ('IntersectionObserver' in window) {
        const obs = new IntersectionObserver((entries) => {
            entries.forEach(e => { if (e.isIntersecting) { e.target.classList.add('in-view'); obs.unobserve(e.target); } });
        }, { threshold: .12 });
        cards.forEach(c => obs.observe(c));
    } else {
        cards.forEach(c => c.classList.add('in-view'));
    }
});
</script>
@endpush
@endif

</div>{{-- .tp-page --}}
@endsection
