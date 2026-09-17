@extends('layouts.app')

@section('title', 'Awards & Recognition — Rees Consult')
@section('description', 'Explore the awards and recognition Rees Consult has received for excellence in IELTS preparation and study-abroad consultancy across Africa.')
@section('keywords', 'Rees Consult awards, IELTS recognition, study abroad, Ghana education awards')
@section('og_title', 'Awards & Recognition — Rees Consult')
@section('og_description', 'Rees Consult has earned recognition across Africa for excellence in IELTS preparation and study-abroad advisory.')

@push('styles')
<style>
/* ═══════════════════════════════════════════════════════
   AWARDS PAGE — Premium Redesign
   Brand: Navy #07294D  |  Gold #F8A706
   ═══════════════════════════════════════════════════════ */
:root {
  --aw-navy:  #07294D;
  --aw-dark:  #0a1f35;
  --aw-gold:  #F8A706;
  --aw-gold2: #ffc733;
  --aw-light: #f4f6fb;
  --aw-r:     16px;
  --aw-shadow: 0 4px 28px rgba(7,41,77,.10);
}
*, *::before, *::after { box-sizing: border-box; }
.aw-page { overflow-x: hidden; }

/* ════════════════════════════════════
   HERO
   ════════════════════════════════════ */
.aw-hero {
  position: relative;
  background: var(--aw-dark);
  min-height: 400px;
  display: flex; align-items: center; justify-content: center; text-align: center;
  overflow: hidden;
}
/* Gold orb effects */
.aw-hero-orb {
  position: absolute; border-radius: 50%; pointer-events: none;
}
.aw-hero-orb-1 {
  width: 600px; height: 600px;
  background: radial-gradient(circle, rgba(248,167,6,.13) 0%, transparent 65%);
  top: -200px; right: -100px;
}
.aw-hero-orb-2 {
  width: 400px; height: 400px;
  background: radial-gradient(circle, rgba(248,167,6,.08) 0%, transparent 70%);
  bottom: -150px; left: -80px;
}
/* Trophy watermark */
.aw-hero-wm {
  position: absolute; font-size: clamp(160px, 28vw, 280px);
  color: rgba(248,167,6,.04); right: -20px; bottom: -30px;
  line-height: 1; pointer-events: none; user-select: none;
}
.aw-hero-inner { position: relative; z-index: 2; padding: clamp(72px,14vw,120px) 20px clamp(56px,10vw,90px); }
.aw-hero-badge {
  display: inline-flex; align-items: center; gap: 8px;
  background: rgba(248,167,6,.15); border: 1px solid rgba(248,167,6,.4);
  color: var(--aw-gold); padding: 6px 18px; border-radius: 999px;
  font-size: .72rem; font-weight: 700; letter-spacing: 2px; text-transform: uppercase;
  margin-bottom: 1.25rem;
}
.aw-hero h1 {
  font-size: clamp(2rem, 5.5vw, 3.6rem); font-weight: 900; color: #fff; line-height: 1.1;
  margin-bottom: .75rem;
}
.aw-hero h1 span { color: var(--aw-gold); }
.aw-hero-lead {
  color: rgba(255,255,255,.68); font-size: clamp(.9rem, 2.2vw, 1.05rem);
  max-width: 580px; margin: 0 auto 2.2rem;
}
/* Scroll cue */
.aw-scroll-cue {
  display: inline-flex; flex-direction: column; align-items: center; gap: 6px;
  color: rgba(255,255,255,.35); font-size: .72rem; letter-spacing: 1px;
  text-decoration: none; margin-top: .5rem;
}
.aw-scroll-cue i { font-size: 1.1rem; animation: aw-bounce .9s ease-in-out infinite alternate; }
@keyframes aw-bounce { from { transform: translateY(0); } to { transform: translateY(6px); } }

/* ════════════════════════════════════
   SECTION HEADERS (shared)
   ════════════════════════════════════ */
.aw-section-head { text-align: center; margin-bottom: 2.8rem; }
.aw-section-eyebrow {
  font-size: .72rem; font-weight: 700; letter-spacing: 2px;
  text-transform: uppercase; color: var(--aw-gold); margin-bottom: .4rem;
}
.aw-section-title {
  font-size: clamp(1.5rem, 3.5vw, 2.2rem); font-weight: 800; color: var(--aw-navy);
}
.aw-section-divider {
  width: 48px; height: 4px;
  background: linear-gradient(90deg, var(--aw-gold), var(--aw-gold2));
  border-radius: 4px; margin: .8rem auto 0;
}

/* ════════════════════════════════════
   PHOTO GALLERY SECTION
   ════════════════════════════════════ */
.aw-gallery-section {
  background: #fff; padding: clamp(52px, 9vw, 80px) 0;
}

/* CSS Columns masonry */
.aw-masonry {
  column-count: 3; column-gap: 18px;
}
@media (max-width: 991.98px) { .aw-masonry { column-count: 2; } }
@media (max-width: 575.98px) { .aw-masonry { column-count: 1; } }

.aw-masonry-item {
  break-inside: avoid; -webkit-column-break-inside: avoid;
  margin-bottom: 18px; display: block;
  /* entrance */
  opacity: 0; transform: translateY(20px);
  transition: opacity .45s ease, transform .45s ease;
}
.aw-masonry-item.in-view { opacity: 1; transform: translateY(0); }

/* Photo card */
.aw-photo-card {
  display: block; position: relative; border-radius: var(--aw-r); overflow: hidden;
  cursor: pointer; box-shadow: var(--aw-shadow); text-decoration: none;
  transition: transform .28s ease, box-shadow .28s ease; background: var(--aw-light);
}
.aw-photo-card:hover { transform: translateY(-5px) scale(1.01); box-shadow: 0 16px 48px rgba(7,41,77,.18); }
.aw-photo-card img { width: 100%; height: auto; display: block; transition: transform .45s ease; }
.aw-photo-card:hover img { transform: scale(1.04); }

/* Hover overlay */
.aw-photo-overlay {
  position: absolute; inset: 0;
  background: linear-gradient(to top, rgba(7,41,77,.82) 0%, rgba(7,41,77,.1) 50%, transparent 100%);
  opacity: 0; transition: opacity .28s;
  display: flex; flex-direction: column; justify-content: flex-end;
  padding: 1rem 1.1rem .9rem;
}
.aw-photo-card:hover .aw-photo-overlay { opacity: 1; }
.aw-photo-overlay-title { color: #fff; font-size: .9rem; font-weight: 700; line-height: 1.3; margin-bottom: .2rem; }
.aw-photo-overlay-by { color: var(--aw-gold); font-size: .75rem; font-weight: 600; }
.aw-zoom-icon {
  position: absolute; top: .75rem; right: .75rem;
  width: 34px; height: 34px; border-radius: 50%;
  background: rgba(255,255,255,.15); backdrop-filter: blur(6px);
  display: flex; align-items: center; justify-content: center;
  color: #fff; font-size: 1rem; opacity: 0; transition: opacity .28s, background .2s;
}
.aw-photo-card:hover .aw-zoom-icon { opacity: 1; }
.aw-zoom-icon:hover { background: rgba(248,167,6,.7); }

/* No-photo placeholder */
.aw-photo-placeholder {
  border-radius: var(--aw-r); overflow: hidden; margin-bottom: 0;
}
.aw-photo-placeholder-inner {
  background: linear-gradient(135deg, #fff8e1, #fff3cc);
  padding: 48px 24px; text-align: center; border-radius: var(--aw-r);
  border: 1px solid rgba(248,167,6,.2);
}
.aw-photo-placeholder-inner i { font-size: 3rem; color: var(--aw-gold); display: block; margin-bottom: .7rem; }
.aw-photo-placeholder-inner .title { font-weight: 700; color: var(--aw-navy); font-size: .95rem; }
.aw-photo-placeholder-inner .by { color: var(--aw-gold); font-size: .78rem; font-weight: 600; margin-top: .3rem; }

/* Empty state */
.aw-empty { text-align: center; padding: 60px 24px; color: #adb5bd; }
.aw-empty i { font-size: 3rem; display: block; margin-bottom: .9rem; color: rgba(7,41,77,.12); }

/* ════════════════════════════════════
   SHOWCASE FEATURED VIDEO
   ════════════════════════════════════ */
.aw-showcase-section {
  background: linear-gradient(135deg, var(--aw-navy) 0%, var(--aw-dark) 100%);
  padding: clamp(52px, 9vw, 80px) 0;
}
.aw-showcase-card {
  border-radius: 24px; overflow: hidden;
  background: rgba(255,255,255,.04);
  border: 1px solid rgba(255,255,255,.1);
  display: grid; grid-template-columns: 1fr 1fr;
}
@media (max-width: 767.98px) { .aw-showcase-card { grid-template-columns: 1fr; } }
.aw-showcase-video { position: relative; width: 100%; aspect-ratio: 16/9; background: #000; }
.aw-showcase-video iframe { position: absolute; inset: 0; width: 100%; height: 100%; border: none; }
.aw-showcase-info {
  padding: clamp(28px, 5vw, 52px);
  display: flex; flex-direction: column; justify-content: center;
}
.aw-showcase-badge {
  display: inline-flex; align-items: center; gap: 6px;
  background: rgba(248,167,6,.2); border: 1px solid rgba(248,167,6,.4);
  color: var(--aw-gold); padding: 5px 14px; border-radius: 999px;
  font-size: .72rem; font-weight: 700; letter-spacing: 1.5px; text-transform: uppercase;
  margin-bottom: 1.1rem; align-self: flex-start;
}
.aw-showcase-title { font-size: clamp(1.1rem, 2.5vw, 1.65rem); font-weight: 800; color: #fff; margin-bottom: .7rem; }
.aw-showcase-quote {
  color: rgba(255,255,255,.7); font-size: .92rem; line-height: 1.65;
  font-style: italic; margin-bottom: 1.4rem;
  border-left: 3px solid var(--aw-gold); padding-left: 1rem;
}
.aw-showcase-author { display: flex; align-items: center; gap: 12px; }
.aw-showcase-avatar {
  width: 44px; height: 44px; border-radius: 50%; overflow: hidden;
  border: 2px solid rgba(248,167,6,.5); flex-shrink: 0;
  background: rgba(255,255,255,.1); display: flex; align-items: center; justify-content: center;
}
.aw-showcase-avatar img { width: 100%; height: 100%; object-fit: cover; }
.aw-showcase-author-name { font-weight: 700; color: #fff; font-size: .9rem; }
.aw-showcase-author-role { color: rgba(255,255,255,.55); font-size: .78rem; }

/* ════════════════════════════════════
   AWARD VIDEOS GRID
   ════════════════════════════════════ */
.aw-videos-section {
  background: var(--aw-light); padding: clamp(52px, 9vw, 80px) 0;
}
.aw-video-grid {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(min(100%, 300px), 1fr));
  gap: 24px;
}
.aw-video-card {
  background: #fff; border-radius: var(--aw-r);
  box-shadow: var(--aw-shadow); overflow: hidden;
  display: flex; flex-direction: column;
  transition: transform .28s ease, box-shadow .28s ease;
  opacity: 0; transform: translateY(20px);
}
.aw-video-card.in-view { opacity: 1; transform: translateY(0); transition: opacity .45s ease, transform .45s ease, box-shadow .28s ease; }
.aw-video-card:hover { transform: translateY(-5px); box-shadow: 0 14px 44px rgba(7,41,77,.15); }
.aw-video-iframe-wrap {
  position: relative; width: 100%; padding-top: 56.25%; background: #000; flex-shrink: 0;
}
.aw-video-iframe-wrap iframe { position: absolute; inset: 0; width: 100%; height: 100%; border: none; }
.aw-video-card-body { padding: 1rem 1.2rem 1.25rem; }
.aw-video-card-title { font-size: .9rem; font-weight: 700; color: var(--aw-navy); margin-bottom: .2rem; line-height: 1.3; }
.aw-video-card-role { font-size: .75rem; color: #6c757d; }
/* Trophy accent bar */
.aw-video-card::before {
  content: '';
  display: block; height: 3px;
  background: linear-gradient(90deg, var(--aw-gold), var(--aw-gold2));
}

/* ════════════════════════════════════
   LIGHTBOX
   ════════════════════════════════════ */
#awLightbox .modal-dialog { max-width: min(92vw, 900px); margin: auto; }
#awLightbox .modal-content { background: var(--aw-dark); border: none; border-radius: 20px; overflow: hidden; }
#awLightbox .modal-body { padding: 0; position: relative; }
#aw-lb-img { width: 100%; height: auto; display: block; max-height: 75vh; object-fit: contain; background: var(--aw-dark); }
.aw-lb-caption { padding: 1rem 1.4rem 1.2rem; background: var(--aw-dark); }
.aw-lb-title { color: #fff; font-weight: 700; font-size: 1rem; margin-bottom: .2rem; }
.aw-lb-by { color: var(--aw-gold); font-size: .8rem; font-weight: 600; }
.aw-lb-date { color: rgba(255,255,255,.4); font-size: .72rem; margin-top: .15rem; }
/* Nav */
.aw-lb-nav {
  position: absolute; top: 50%; transform: translateY(-50%);
  width: 42px; height: 42px; border-radius: 50%;
  background: rgba(255,255,255,.14); backdrop-filter: blur(8px);
  border: none; color: #fff; font-size: 1.1rem;
  display: flex; align-items: center; justify-content: center;
  cursor: pointer; transition: background .2s; z-index: 10;
}
.aw-lb-nav:hover { background: rgba(248,167,6,.7); }
.aw-lb-prev { left: .75rem; }
.aw-lb-next { right: .75rem; }
.aw-lb-close {
  position: absolute; top: .65rem; right: .65rem;
  width: 36px; height: 36px; border-radius: 50%;
  background: rgba(255,255,255,.15); border: none;
  color: #fff; font-size: 1.1rem;
  display: flex; align-items: center; justify-content: center;
  cursor: pointer; transition: background .2s; z-index: 11;
}
.aw-lb-close:hover { background: rgba(220,53,69,.8); }

/* ════════════════════════════════════
   CTA
   ════════════════════════════════════ */
.aw-cta {
  background: #fff; padding: clamp(52px, 9vw, 80px) 0;
  text-align: center;
}
.aw-cta-inner {
  background: linear-gradient(135deg, var(--aw-navy), var(--aw-dark));
  border-radius: 28px; padding: clamp(44px, 8vw, 72px) clamp(24px, 5vw, 64px);
  position: relative; overflow: hidden;
}
.aw-cta-inner::before {
  content: '';
  position: absolute; top: -80px; right: -60px;
  width: 320px; height: 320px; border-radius: 50%;
  background: radial-gradient(circle, rgba(248,167,6,.18) 0%, transparent 70%);
  pointer-events: none;
}
.aw-cta-inner::after {
  content: '';
  position: absolute; bottom: -60px; left: -40px;
  width: 220px; height: 220px; border-radius: 50%;
  background: radial-gradient(circle, rgba(248,167,6,.1) 0%, transparent 70%);
  pointer-events: none;
}
.aw-cta-title { font-size: clamp(1.5rem, 3.5vw, 2.3rem); font-weight: 800; color: #fff; margin-bottom: .7rem; }
.aw-cta-lead { color: rgba(255,255,255,.65); font-size: 1rem; margin-bottom: 2rem; }
.aw-cta-btn {
  background: var(--aw-gold); color: var(--aw-navy) !important;
  font-weight: 800; border: none; border-radius: 12px;
  padding: .9rem 2.4rem; font-size: 1rem;
  box-shadow: 0 6px 24px rgba(248,167,6,.4);
  transition: transform .2s, box-shadow .2s;
}
.aw-cta-btn:hover { transform: translateY(-2px); box-shadow: 0 10px 32px rgba(248,167,6,.55); }

/* ── Utility ── */
@media (max-width: 575.98px) { .aw-video-grid { gap: 16px; } }
</style>
@endpush

@section('content')
<div class="aw-page">

{{-- ═══════════════════════════════════
     HERO
     ═══════════════════════════════════ --}}
<section class="aw-hero">
    <div class="aw-hero-orb aw-hero-orb-1"></div>
    <div class="aw-hero-orb aw-hero-orb-2"></div>
    <div class="aw-hero-wm" aria-hidden="true">🏆</div>
    <div class="aw-hero-inner container">
        <div class="aw-hero-badge">
            <i class="bi bi-trophy-fill"></i> Recognition & Achievements
        </div>
        <h1>Our <span>Awards</span> &amp; Honours</h1>
        <p class="aw-hero-lead">
            Rees Consult's commitment to excellence in IELTS preparation
            and study-abroad advisory has earned recognition across Africa and beyond.
        </p>
        {{-- Scroll cue points to showcase if it exists, otherwise straight to gallery --}}
        <a href="{{ (isset($showcaseVideo) && $showcaseVideo) ? '#showcase-video' : '#gallery' }}" class="aw-scroll-cue">
            <span>Explore</span>
            <i class="bi bi-chevron-double-down"></i>
        </a>
    </div>
</section>

{{-- ═══════════════════════════════════
     SHOWCASE FEATURED AWARD VIDEO
     (appears first, right after hero)
     ═══════════════════════════════════ --}}
@if(isset($showcaseVideo) && $showcaseVideo)
<section class="aw-showcase-section" id="showcase-video">
    <div class="container">
        <div class="aw-section-head">
            <div class="aw-section-eyebrow" style="color:rgba(248,167,6,.85);">Watch & Celebrate</div>
            <h2 class="aw-section-title" style="color:#fff;">Featured Award Video</h2>
            <div class="aw-section-divider"></div>
        </div>
        <div class="aw-showcase-card mx-auto" style="max-width:900px;">
            <div class="aw-showcase-video">
                <iframe src="{{ $showcaseVideo->youtube_embed_url }}{{ str_contains($showcaseVideo->youtube_embed_url, '?') ? '&' : '?' }}autoplay=1&mute=1"
                        title="{{ $showcaseVideo->name }}"
                        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                        allowfullscreen loading="lazy"></iframe>
            </div>
            <div class="aw-showcase-info">
                <div class="aw-showcase-badge">
                    <i class="bi bi-trophy-fill"></i> Featured
                </div>
                <div class="aw-showcase-title">{{ $showcaseVideo->name }}</div>
                @if($showcaseVideo->message)
                <div class="aw-showcase-quote">"{{ Str::limit($showcaseVideo->message, 200) }}"</div>
                @endif
                <div class="aw-showcase-author">
                    <div class="aw-showcase-avatar">
                        @if($showcaseVideo->image)
                            <img src="{{ asset('storage/' . $showcaseVideo->image) }}" alt="{{ $showcaseVideo->name }}">
                        @else
                            <i class="bi bi-person" style="color:rgba(255,255,255,.6);font-size:1.2rem;"></i>
                        @endif
                    </div>
                    <div>
                        <div class="aw-showcase-author-name">{{ $showcaseVideo->name }}</div>
                        <div class="aw-showcase-author-role">{{ $showcaseVideo->role }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endif

{{-- ═══════════════════════════════════
     AWARD PHOTO GALLERY
     ═══════════════════════════════════ --}}
<section class="aw-gallery-section" id="gallery">
    <div class="container">
        <div class="aw-section-head">
            <div class="aw-section-eyebrow">Honoured by the best</div>
            <h2 class="aw-section-title">Award Gallery</h2>
            <div class="aw-section-divider"></div>
        </div>

        @if($awards->isNotEmpty())
        <div class="aw-masonry" id="aw-gallery">
            @foreach($awards as $index => $award)
            <div class="aw-masonry-item" style="transition-delay: {{ ($index % 6) * 80 }}ms;">
                @if($award->photo)
                    <a class="aw-photo-card"
                       href="#"
                       data-index="{{ $index }}"
                       data-img="{{ asset('storage/' . $award->photo) }}"
                       data-title="{{ $award->title }}"
                       data-by="{{ $award->awarded_by ?? '' }}"
                       data-date="{{ $award->formatted_date ?? '' }}"
                       role="button"
                       aria-label="Preview {{ $award->title }}"
                       onclick="awOpenLightbox({{ $index }}); return false;">
                        <img src="{{ asset('storage/' . $award->photo) }}"
                             alt="{{ $award->title }}" loading="lazy">
                        <div class="aw-zoom-icon" aria-hidden="true">
                            <i class="bi bi-zoom-in"></i>
                        </div>
                        <div class="aw-photo-overlay" aria-hidden="true">
                            <div class="aw-photo-overlay-title">{{ $award->title }}</div>
                            @if($award->awarded_by)
                            <div class="aw-photo-overlay-by">
                                <i class="bi bi-patch-check-fill me-1"></i>{{ $award->awarded_by }}
                            </div>
                            @endif
                        </div>
                    </a>
                @else
                    <div class="aw-photo-placeholder">
                        <div class="aw-photo-placeholder-inner">
                            <i class="bi bi-trophy-fill"></i>
                            <div class="title">{{ $award->title }}</div>
                            @if($award->awarded_by)
                            <div class="by"><i class="bi bi-patch-check-fill me-1"></i>{{ $award->awarded_by }}</div>
                            @endif
                        </div>
                    </div>
                @endif
            </div>
            @endforeach
        </div>
        @else
        <div class="aw-empty">
            <i class="bi bi-trophy"></i>
            <h3 style="color:var(--aw-navy);">Award photos coming soon</h3>
            <p class="text-muted mb-0">Check back shortly to see our growing collection of accolades.</p>
        </div>
        @endif
    </div>
</section>



{{-- ═══════════════════════════════════
     AWARD VIDEOS GRID
     ═══════════════════════════════════ --}}
@if(isset($awardVideos) && $awardVideos->isNotEmpty())
<section class="aw-videos-section" id="videos">
    <div class="container">
        <div class="aw-section-head">
            <div class="aw-section-eyebrow">Watch & Celebrate</div>
            <h2 class="aw-section-title">Award Videos</h2>
            <div class="aw-section-divider"></div>
        </div>
        <div class="aw-video-grid" id="aw-video-grid">
            @foreach($awardVideos as $i => $video)
            <div class="aw-video-card" style="transition-delay: {{ ($i % 9) * 70 }}ms;">
                <div class="aw-video-iframe-wrap">
                    <iframe src="{{ $video->youtube_embed_url }}"
                            title="{{ $video->name }}"
                            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                            allowfullscreen loading="lazy"></iframe>
                </div>
                <div class="aw-video-card-body">
                    <div class="aw-video-card-title">{{ $video->name }}</div>
                    @if($video->role)
                    <div class="aw-video-card-role">{{ $video->role }}</div>
                    @endif
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>
@endif

{{-- ═══════════════════════════════════
     CTA
     ═══════════════════════════════════ --}}
<section class="aw-cta">
    <div class="container">
        <div class="aw-cta-inner">
            <h2 class="aw-cta-title" style="position:relative;z-index:2;">
                Recognised for Excellence.<br>Ready to Help You Succeed.
            </h2>
            <p class="aw-cta-lead" style="position:relative;z-index:2;">
                Start your IELTS journey with a team that sets the standard and has the accolades to prove it.
            </p>
            <button type="button" class="btn aw-cta-btn" style="position:relative;z-index:2;"
                    data-bs-toggle="modal" data-bs-target="#applyModal">
                <i class="bi bi-send-fill me-2"></i>Get Started Today
            </button>
        </div>
    </div>
</section>

</div>{{-- .aw-page --}}

{{-- ═══════════════════════════════════
     LIGHTBOX MODAL
     ═══════════════════════════════════ --}}
<div class="modal fade" id="awLightbox" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-body p-0" style="position:relative;">
                <button class="aw-lb-close" onclick="awCloseLightbox()" aria-label="Close">
                    <i class="bi bi-x-lg"></i>
                </button>
                <button class="aw-lb-nav aw-lb-prev" onclick="awShiftLightbox(-1)" aria-label="Previous">
                    <i class="bi bi-chevron-left"></i>
                </button>
                <button class="aw-lb-nav aw-lb-next" onclick="awShiftLightbox(1)" aria-label="Next">
                    <i class="bi bi-chevron-right"></i>
                </button>
                <img id="aw-lb-img" src="" alt="">
            </div>
            <div class="aw-lb-caption">
                <div class="aw-lb-title" id="aw-lb-title"></div>
                <div class="aw-lb-by"    id="aw-lb-by"></div>
                <div class="aw-lb-date"  id="aw-lb-date"></div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
(function () {
    /* ── Gallery Intersection Observer (photos + videos) ── */
    if ('IntersectionObserver' in window) {
        const obs = new IntersectionObserver((entries) => {
            entries.forEach(e => { if (e.isIntersecting) { e.target.classList.add('in-view'); obs.unobserve(e.target); } });
        }, { threshold: .1 });
        document.querySelectorAll('.aw-masonry-item, .aw-video-card').forEach(el => obs.observe(el));
    } else {
        document.querySelectorAll('.aw-masonry-item, .aw-video-card').forEach(el => el.classList.add('in-view'));
    }

    /* ── Lightbox ── */
    const cards  = [...document.querySelectorAll('#aw-gallery .aw-photo-card')];
    const photos = cards.map(c => ({
        img  : c.dataset.img,
        title: c.dataset.title,
        by   : c.dataset.by   || '',
        date : c.dataset.date || '',
    }));

    let current  = 0;
    let bsModal  = null;

    function getModal() {
        if (!bsModal) bsModal = new bootstrap.Modal(document.getElementById('awLightbox'), { keyboard: true });
        return bsModal;
    }

    function render(index) {
        if (!photos.length) return;
        current = ((index % photos.length) + photos.length) % photos.length;
        const p   = photos[current];
        const img = document.getElementById('aw-lb-img');
        img.src = ''; img.alt = p.title; img.src = p.img;
        document.getElementById('aw-lb-title').textContent = p.title;
        document.getElementById('aw-lb-by').textContent    = p.by   ? '⭐ ' + p.by : '';
        document.getElementById('aw-lb-date').textContent  = p.date || '';
        const showNav = photos.length > 1;
        document.querySelector('.aw-lb-prev').style.display = showNav ? '' : 'none';
        document.querySelector('.aw-lb-next').style.display = showNav ? '' : 'none';
    }

    window.awOpenLightbox  = function (i)   { render(i); getModal().show(); };
    window.awShiftLightbox = function (dir) { render(current + dir); };
    window.awCloseLightbox = function ()    { getModal().hide(); };

    /* Keyboard navigation */
    document.addEventListener('keydown', function (e) {
        const lb = document.getElementById('awLightbox');
        if (!lb || !lb.classList.contains('show')) return;
        if (e.key === 'ArrowLeft')  awShiftLightbox(-1);
        if (e.key === 'ArrowRight') awShiftLightbox(1);
        if (e.key === 'Escape')     awCloseLightbox();
    });

    /* Smooth scroll for anchor links */
    document.querySelectorAll('a.aw-scroll-cue[href^="#"]').forEach(a => {
        a.addEventListener('click', e => {
            e.preventDefault();
            const t = document.querySelector(a.getAttribute('href'));
            if (t) t.scrollIntoView({ behavior: 'smooth', block: 'start' });
        });
    });
})();
</script>
@endpush
