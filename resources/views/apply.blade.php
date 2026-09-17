@extends('layouts.app')

@section('title', 'Apply Now - Study & Work Abroad with Rees Consult')

@section('description',
    'Start your application to study or work abroad. Expert IELTS/test preparation, school admissions and overseas
    job placement. Apply now and get a personalised plan from Rees Consult.')

@section('keywords',
    'apply study abroad, work abroad application, IELTS preparation, study abroad Ghana, job abroad, Rees Consult apply')

@section('og_title', 'Apply Now - Study & Work Abroad with Rees Consult')
@section('og_description', 'Start your application today. Personalised guidance for exams, school admissions and overseas job placement.')

@section('content')
    @php
        $heroSliders = \App\Models\HeroSlider::active()->reorder('updated_at', 'desc')->get();
        $currentSlider = $heroSliders->first();
    @endphp

    <!-- ===== Hero ===== -->
    <section class="hero-section hero-redesign"
        @if ($currentSlider) style="background: linear-gradient(120deg, rgba(7, 41, 77, {{ min(1, max(0, $currentSlider->overlay_opacity)) }}), rgba(7, 41, 77, {{ max(0, min(1, $currentSlider->overlay_opacity - 0.25)) }})), url('{{ $currentSlider->image_path ? asset('storage/' . $currentSlider->image_path) : $currentSlider->image_url }}') center/cover;"
        @else style="background: linear-gradient(120deg, rgba(7,41,77,0.92), rgba(7,41,77,0.7)), url('{{ asset('images/rees-core-team.jpg') }}') center/cover;" @endif>
        <div class="container">
            <div class="row align-items-center g-5">
                <div class="col-lg-7">
                    <div class="hero-content">
                        <span class="hero-badge"><i class="bi bi-patch-check-fill"></i> British Council Registration Partner</span>
                        <h1>
                            Your Journey to <span class="highlight">Study &amp; Work Abroad</span><br>
                            Starts Here
                        </h1>
                        <p class="hero-lead">
                            Tell us your goal and our advisors will build a personalised plan — exam preparation,
                            school admissions or overseas job placement. It takes under 2 minutes to apply.
                        </p>
                        <ul>
                            <li>IELTS, GRE, SAT, TOEFL, G-MAT, TESOL, NCLEX &amp; OET tutoring</li>
                            <li>School &amp; university applications and visa guidance</li>
                            <li>Overseas job placement &amp; relocation support</li>
                        </ul>
                        <div class="hero-buttons">
                            <button type="button" class="btn btn-apply" data-bs-toggle="modal" data-bs-target="#applyModal">
                                Apply Now <i class="bi bi-arrow-right ms-1"></i>
                            </button>
                            <a href="#how-it-works" class="btn btn-learn">How It Works</a>
                        </div>
                        <div class="hero-stats">
                            <div class="hero-stat">
                                <span class="hero-stat-num">10+</span>
                                <span class="hero-stat-label">Exams &amp; programs</span>
                            </div>
                            <div class="hero-stat">
                                <span class="hero-stat-num">140+</span>
                                <span class="hero-stat-label">Countries reached</span>
                            </div>
                            <div class="hero-stat">
                                <span class="hero-stat-num">1:1</span>
                                <span class="hero-stat-label">Personal guidance</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-5">
                    <div class="hero-apply-card">
                        <h3>Start your application</h3>
                        <p>A few quick questions so we can match you with the right plan for your goals.</p>
                        <ul class="hero-apply-list">
                            <li><i class="bi bi-check-circle-fill"></i> Personalised study &amp; travel roadmap</li>
                            <li><i class="bi bi-check-circle-fill"></i> Expert exam preparation</li>
                            <li><i class="bi bi-check-circle-fill"></i> School &amp; job application support</li>
                            <li><i class="bi bi-check-circle-fill"></i> Visa &amp; relocation guidance</li>
                        </ul>
                        <button type="button" class="btn hero-apply-btn" data-bs-toggle="modal" data-bs-target="#applyModal">
                            Apply Now <i class="bi bi-arrow-right ms-1"></i>
                        </button>
                        <p class="hero-apply-note"><i class="bi bi-clock"></i> Takes under 2 minutes • Free, no obligation</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ===== Trust bar ===== -->
    <section class="lp-trustbar">
        <div class="container">
            <p class="lp-trustbar-label">Trusted partners &amp; affiliations</p>
            <div class="d-flex justify-content-center align-items-center gap-4 gap-md-5 flex-wrap">
                <img src="{{ asset('images/British-Council-recrute.jpg') }}" alt="British Council">
                <img src="{{ asset('images/AIMS.webp') }}" alt="AIMS">
                <img src="{{ asset('images/idp.jpg') }}" alt="IDP">
                <img src="{{ asset('images/NAFSA logo.png') }}" alt="NAFSA">
            </div>
        </div>
    </section>

    <!-- ===== How it works ===== -->
    <section class="lp-section" id="how-it-works">
        <div class="container">
            <div class="text-center mb-5">
                <div class="section-label">Simple Process</div>
                <h2 class="section-title">How It Works</h2>
            </div>
            <div class="row g-4">
                <div class="col-md-4">
                    <div class="lp-step">
                        <div class="lp-step-num">1</div>
                        <i class="bi bi-pencil-square lp-step-icon"></i>
                        <h5>Apply in 2 minutes</h5>
                        <p>Answer a few quick questions about your goal, timeline and budget.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="lp-step">
                        <div class="lp-step-num">2</div>
                        <i class="bi bi-people lp-step-icon"></i>
                        <h5>Get matched with an advisor</h5>
                        <p>Our team reviews your details and reaches out with a personalised plan.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="lp-step">
                        <div class="lp-step-num">3</div>
                        <i class="bi bi-airplane lp-step-icon"></i>
                        <h5>Prepare &amp; travel</h5>
                        <p>Ace your exams, secure admission or a job, and travel abroad with confidence.</p>
                    </div>
                </div>
            </div>
            <div class="text-center mt-5">
                <button type="button" class="btn btn-apply px-4 py-2" data-bs-toggle="modal" data-bs-target="#applyModal">
                    Start My Application <i class="bi bi-arrow-right ms-1"></i>
                </button>
            </div>
        </div>
    </section>

    <!-- ===== What we help with (services) ===== -->
    @if ($services->count())
        <section class="lp-section lp-section-alt">
            <div class="container">
                <div class="text-center mb-5">
                    <div class="section-label">What We Help With</div>
                    <h2 class="section-title">Choose Your Path</h2>
                </div>
                <div class="row g-4">
                    @foreach ($services as $service)
                        <div class="col-md-6 col-lg-4">
                            <div class="lp-service-card">
                                <div class="lp-service-icon">
                                    <i class="bi {{ $service->icon ?: 'bi-mortarboard' }}"></i>
                                </div>
                                <h5>{{ $service->title }}</h5>
                                @if ($service->subtitle)
                                    <p class="lp-service-sub">{{ $service->subtitle }}</p>
                                @elseif ($service->description)
                                    <p class="lp-service-sub">{{ \Illuminate\Support\Str::limit(strip_tags($service->description), 90) }}</p>
                                @endif
                                <button type="button" class="lp-service-link" data-bs-toggle="modal" data-bs-target="#applyModal">
                                    Apply <i class="bi bi-arrow-right"></i>
                                </button>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    <!-- ===== Why choose us ===== -->
    <section class="lp-section">
        <div class="container">
            <div class="text-center mb-5">
                <div class="section-label">Why Rees Consult</div>
                <h2 class="section-title">You're in Expert Hands</h2>
            </div>
            <div class="row g-4">
                <div class="col-md-6 col-lg-3">
                    <div class="lp-benefit"><i class="bi bi-award"></i><h6>Proven Expertise</h6><p>Years of helping students succeed in exams and admissions.</p></div>
                </div>
                <div class="col-md-6 col-lg-3">
                    <div class="lp-benefit"><i class="bi bi-person-check"></i><h6>1:1 Guidance</h6><p>Personal advisors who guide you at every step.</p></div>
                </div>
                <div class="col-md-6 col-lg-3">
                    <div class="lp-benefit"><i class="bi bi-globe2"></i><h6>Global Reach</h6><p>Pathways to the UK, Canada, USA, Europe and beyond.</p></div>
                </div>
                <div class="col-md-6 col-lg-3">
                    <div class="lp-benefit"><i class="bi bi-shield-check"></i><h6>Trusted Partner</h6><p>British Council registration partner you can rely on.</p></div>
                </div>
            </div>
        </div>
    </section>

    <!-- ===== Testimonials ===== -->
    @if ($testimonials->count())
        <section class="lp-section lp-section-alt">
            <div class="container">
                <div class="text-center mb-5">
                    <div class="section-label">Success Stories</div>
                    <h2 class="section-title">What Our Clients Say</h2>
                </div>
                <div class="row g-4 justify-content-center">
                    @foreach ($testimonials as $testimonial)
                        <div class="col-md-6 col-lg-4">
                            <div class="lp-testimonial">
                                <i class="bi bi-quote lp-quote"></i>
                                <p class="lp-testimonial-text">{{ $testimonial->message }}</p>
                                <div class="d-flex align-items-center gap-3 mt-3">
                                    @if ($testimonial->image)
                                        <img src="{{ asset('storage/' . $testimonial->image) }}" alt="{{ $testimonial->name }}" class="lp-testimonial-img">
                                    @else
                                        <div class="lp-testimonial-img d-flex align-items-center justify-content-center bg-secondary text-white"><i class="bi bi-person"></i></div>
                                    @endif
                                    <div>
                                        <div class="fw-bold">{{ $testimonial->name }}</div>
                                        <div class="text-muted small">{{ $testimonial->role }}</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    <!-- ===== Final CTA ===== -->
    <section class="lp-cta">
        <div class="container text-center">
            <h2>Ready to start your journey abroad?</h2>
            <p>Apply now — it's free, takes under 2 minutes, and could change your future.</p>
            <button type="button" class="btn lp-cta-btn" data-bs-toggle="modal" data-bs-target="#applyModal">
                Apply Now <i class="bi bi-arrow-right ms-1"></i>
            </button>
        </div>
    </section>
@endsection
