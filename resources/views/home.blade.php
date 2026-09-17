@extends('layouts.app')

@section('title', 'Work Abroad, Study Abroad & IELTS Preparation in Ghana — Rees Consult')

@section('description',
    "Ghana's award-winning consultancy for work abroad job placements, study abroad school admissions, and IELTS, GRE, SAT, TOEFL & OET test preparation. British Council affiliate based in Accra. Book a free session today.")

@section('keywords',
    'work abroad from Ghana, job placement abroad Ghana, study abroad consultancy Ghana, IELTS preparation Ghana, IELTS coaching Accra, GRE prep Ghana, SAT preparation Ghana, TOEFL preparation Ghana, OET preparation Ghana nurses, NCLEX review Ghana, study abroad agency Accra, work abroad agency Ghana, scholarship Ghanaian students, international education consultancy Accra')

@section('og_title', 'Work Abroad, Study Abroad & IELTS Preparation in Ghana — Rees Consult')

@section('og_description',
    "Ghana's award-winning agency for overseas job placements, school admissions, and expert IELTS, GRE, SAT & TOEFL coaching. British Council affiliate. Accra-based with global reach.")

@section('content')
    @php
        $heroSliders = \App\Models\HeroSlider::active()->reorder('updated_at', 'desc')->get();
        $currentSlider = $heroSliders->first();
    @endphp

    <!-- Hero Section -->
    <section class="hero-section hero-redesign"
        @if ($currentSlider) style="background: linear-gradient(120deg, rgba(7, 41, 77, {{ min(1, max(0, $currentSlider->overlay_opacity)) }}), rgba(7, 41, 77, {{ max(0, min(1, $currentSlider->overlay_opacity - 0.25)) }})), url('{{ $currentSlider->image_path ? asset('storage/' . $currentSlider->image_path) : $currentSlider->image_url }}') center/cover;" @endif>
        <!-- Carousel Navigation -->
        <div class="carousel-nav left">
            <button><i class="bi bi-chevron-left"></i></button>
        </div>
        <div class="carousel-nav right">
            <button><i class="bi bi-chevron-right"></i></button>
        </div>

        <div class="container">
            <div class="row align-items-center g-5">
                <div class="col-lg-7">
                    <div class="hero-content">
                        <span class="hero-badge"><i class="bi bi-patch-check-fill"></i> British Council Registration Partner</span>
                        <h1>
                            Travel <span class="highlight">Abroad</span><br>
                            With Ease
                        </h1>
                        <p class="hero-lead">
                            Expert one-on-one guidance for your exams, school admissions and overseas job placement —
                            connecting you to global opportunities for study and work.
                        </p>
                        <ul>
                            <li>IELTS, GRE, SAT, TOEFL, G-MAT, TESOL, NCLEX &amp; OET tutoring for exam success</li>
                            <li>Education &amp; job travel consultancy for studying and working abroad</li>
                        </ul>
                        <div class="hero-buttons">
                            <button type="button" class="btn btn-apply" data-bs-toggle="modal" data-bs-target="#applyModal">
                                Apply Now <i class="bi bi-arrow-right ms-1"></i>
                            </button>
                            <a href="{{ route('about') }}" class="btn btn-learn">
                                Learn More
                            </a>
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
                        <h3>Ready to begin?</h3>
                        <p>Start a quick application and our advisors will build the right plan for your goals.</p>
                        <ul class="hero-apply-list">
                            <li><i class="bi bi-check-circle-fill"></i> Personalised study &amp; travel roadmap</li>
                            <li><i class="bi bi-check-circle-fill"></i> Expert exam preparation</li>
                            <li><i class="bi bi-check-circle-fill"></i> School &amp; job application support</li>
                            <li><i class="bi bi-check-circle-fill"></i> Visa &amp; relocation guidance</li>
                        </ul>
                        <button type="button" class="btn hero-apply-btn" data-bs-toggle="modal" data-bs-target="#applyModal">
                            Start My Application <i class="bi bi-arrow-right ms-1"></i>
                        </button>
                        <p class="hero-apply-note"><i class="bi bi-clock"></i> Takes under 2 minutes</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- About Section -->
    <section class="about-section scroll-reveal">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6 mb-4 mb-lg-0">
                    <div class="about-content">
                        <div class="about-label">About Us</div>
                        <h2 class="about-title">Ree's Consult</h2>
                        <div class="about-text">
                            <p>Providing expert guidance and preparation for individuals taking the International English
                                Language Testing System (IELTS) including GRE, SAT, TOEFL, G-MAT, TESOL, NCLEX, OETexam.</p>
                            <p>We also assist clients with studying and working abroad, including travel arrangements, visa
                                applications, and career pathways. Ree's Consult aims to empower individuals to achieve
                                their global aspirations through education and career opportunities.</p>
                        </div>
                        <a href="{{ route('about') }}" class="btn btn-warning text-white fw-bold mt-3 px-4 py-2"
                            style="background-color: #F8A706; border: none;">Explore More</a>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="about-image-wrapper scroll-reveal-right">
                        <img src="{{ asset('images/rees-core-team.jpg') }}" alt="Rees Consult team of education and work abroad consultants in Accra, Ghana"
                            class="img-fluid about-image" style="height: 400px; object-fit: center center;">
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Partnership Section -->
    <section class="partnership-section scroll-reveal">
        <div class="container">
            <div class="partnership-logos mb-5">
                <p class="partner-eyebrow">Accredited &amp; trusted partners</p>
                <div class="partner-logo-grid">
                    <div class="partner-logo">
                        <img src="{{ asset('images/British-Council-recrute.jpg') }}" alt="British Council — Rees Consult Registration Partner">
                    </div>
                    <div class="partner-logo">
                        <img src="{{ asset('images/AIMS.webp') }}" alt="AIMS — African Institute for Mathematical Sciences partner">
                    </div>
                    <div class="partner-logo">
                        <img src="{{ asset('images/idp.jpg') }}" alt="IDP Education — IELTS partner">
                    </div>
                    <div class="partner-logo">
                        <img src="{{ asset('images/NAFSA logo.png') }}" alt="NAFSA Association of International Educators partner">
                    </div>
                    <div class="partner-logo partner-logo-wide">
                        <img src="{{ asset('images/BC-IELTS_Side-By-Side_Strap_RGB_Screen-2048x549.png') }}"
                            alt="British Council IELTS — Official IELTS registration partner Ghana">
                    </div>
                </div>
            </div>

            <div class="row justify-content-center">
                <div class="col-lg-10">
                    <h3 class="partnership-subtitle text-center">British Council Registration Partners (Affiliates)</h3>
                    <h4 class="fw-bold mb-3">What is IELTS?</h4>
                    <div class="partnership-content">
                        <p>The International English Language Testing System (IELTS) is the most widely recognised English
                            language test in the world.  is accepted by over 12,500 organisations in more than 140
                            countries and is developed by some of the world's leading experts in language assessment.</p>

                        <p class="mt-4">You can take IELTS to:</p>
                        <ul class="list-unstyled">
                            <li class="mb-2">- Apply to a university or higher education institution for a course taught
                                in English in any country worldwide.</li>
                            <li class="mb-2">- Spend a short period of studying abroad.</li>
                            <li class="mb-2">- Work in a professional organisation that requires advanced English
                                language
                                proficiency.</li>
                            <li class="mb-2">- Immigrate to an English-speaking country such as Canada, Australia, or New
                                Zealand.</li>
                        </ul>

                        <div class="quote-box scroll-reveal-left">
                            <i class="bi bi-quote quote-icon"></i>
                            <p class="quote-text">Education is the passport to the future, for tomorrow belongs to those
                                who
                                prepare for it today.</p>
                            <p class="quote-author">— Malcolm X</p>
                        </div>

                        <h4 class="fw-bold mt-5 mb-3">Which IELTS test is right for me?</h4>
                        <p>IELTS opens the pathway for you to discover the world, get the job you want, and demonstrate your
                            English language abilities. The test you choose depends on your goal.</p>
                        <p class="fw-bold">There are two types of the IELTS test available – IELTS Academic and IELTS
                            General Training:</p>
                        <ul class="list-unstyled">
                            <li class="mb-3">• <strong>IELTS Academic:</strong> It measures if your English language
                                proficiency level is suitable for an academic environment. The test reflects aspects of
                                academic language and assesses whether you are ready to begin training or a course of study
                                in English.</li>
                            <li>• <strong>IELTS General Training:</strong> It measures English language knowledge in a
                                practical, everyday context. The test tasks reflect real-life situations in social or
                                workplace settings.</li>
                        </ul>
                        <p class="mt-3 text-muted">Please check which test you are required to take with the institutions
                            or organisations.</p>

                        <a href="#" class="btn btn-danger mt-3 px-4 py-2">Read More <i
                                class="bi bi-info-circle-fill ms-1"></i></a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Team Section -->
    <section class="team-section scroll-reveal">
        <div class="container">
            <div class="section-label">Staff</div>
            <h2 class="section-title">Meet our Staff</h2>

            <div class="row mb-5">
                <div class="col-lg-6">
                    <p class="text-muted">Are you ready to take your career to the next level? At Ree's Consult, we're
                        looking for passionate, skilled, and motivated individuals to join our growing team. Our mission is
                        to provide world-class educational and job consulting as well as test preparation services that
                        empower African students to successfully pursue their academic, career and professional goals
                        internationally.</p>

                    <h5 class="fw-bold mt-4">Why Choose Ree's Consult?</h5>
                    <div class="team-benefits">
                        <ul>
                            <li><strong>Innovative Work Environment:</strong> We thrive on creativity and collaboration.
                                Here, every team member's ideas are valued.</li>
                            <li><strong>Career Development:</strong> We are committed to your growth. Benefit from career
                                advancement opportunities and mentorship.</li>
                            <li><strong>Meaningful Impact:</strong> Your work will directly contribute to our clients'
                                success and drive our company forward.</li>
                            <li><strong>Competitive Benefits:</strong> Enjoy a competitive salary, health benefits, paid
                                time off, and flexible work options.</li>
                        </ul>
                        <a href="#" class="btn btn-primary mt-3" style="background-color: #07294D;">Career With
                            Us</a>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="row g-4">
                        @forelse($teamMembers as $index => $member)
                            <div class="col-md-6 scroll-reveal-right"
                                style="transition-delay: {{ ($index + 1) * 0.1 }}s;">
                                <div class="team-card">
                                    @if ($member->image)
                                        <img src="{{ asset('storage/' . $member->image) }}" alt="{{ $member->name }}">
                                    @else
                                        <img src="https://images.unsplash.com/photo-1573496359142-b8d87734a5a2?w=500&q=80"
                                            alt="{{ $member->name }}">
                                    @endif
                                    <div class="team-card-overlay">
                                        <div class="team-card-name">{{ $member->name }}</div>
                                        <div class="team-card-role">{{ $member->role }}</div>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="col-12 text-center">
                                <p class="text-muted">No team members available</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Services Section -->
    <section class="services-section scroll-reveal">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-5 mb-4 mb-lg-0">
                    <div class="position-relative">
                        <img src="{{ asset('images/our services lady with t-shirt.jpg') }}" alt="Rees Consult study abroad, work abroad and test preparation services in Ghana"
                            class="img-fluid rounded-4 shadow-lg">
                        <!-- Decorative triangle -->
                        <div
                            style="position: absolute; top: 20%; left: -30px; width: 0; height: 0; border-top: 50px solid transparent; border-bottom: 50px solid transparent; border-right: 80px solid #e83e8c; transform: rotate(15deg); z-index: -1;">
                        </div>
                    </div>
                </div>
                <div class="col-lg-7 ps-lg-5">
                    <div class="section-label">Our Services</div>
                    <h2 class="section-title">Find Our Featured Services</h2>

                    <ul class="list-unstyled mt-4 mb-4">
                        @forelse($services as $service)
                            <li class="mb-3 d-flex align-items-center fs-5">
                                <i class="bi {{ $service->icon }} fs-3 text-primary me-2"></i> {{ $service->title }}
                            </li>
                        @empty
                            <li class="mb-3 d-flex align-items-center fs-5">
                                <i class="bi bi-dot fs-3 text-primary me-2"></i> No services available
                            </li>
                        @endforelse
                    </ul>

                    <a href="{{ route('services.index') }}" class="btn btn-warning text-white fw-bold px-4 py-2"
                        style="background-color: #F8A706; border: none;">Explore More</a>
                </div>
            </div>
        </div>
    </section>

    <!-- Testimonials Section -->
    <section class="testimonials-section scroll-reveal">
        <div class="container">
            <div class="text-white mb-5 text-center text-md-start">
                <div class="text-uppercase mb-2" style="letter-spacing: 1px; font-size: 0.9rem;">Feedback</div>
                <h2 class="display-5 fw-bold">What Clients Say</h2>
            </div>
            
            @if($videoTestimonials && $videoTestimonials->count() > 0)
                <div class="mb-5">
                    <h4 class="text-warning mb-4">Video Testimonials</h4>
                    <div class="video-testimonials-slider" id="videoTestimonialsSlider">
                        <div class="vt-slider-track">
                            @foreach($videoTestimonials as $index => $videoTestimonial)
                                <div class="vt-slide {{ $index === 0 ? 'vt-active' : '' }}" data-index="{{ $index }}">
                                    <div class="rounded shadow-lg overflow-hidden border border-warning border-2">
                                        <div class="row g-0 bg-dark text-white">
                                            <div class="col-md-7">
                                                <div class="ratio ratio-16x9 h-100">
                                                    <iframe src="{{ $videoTestimonial->youtube_embed_url }}" title="{{ $videoTestimonial->name }} Testimonial" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen loading="lazy"></iframe>
                                                </div>
                                            </div>
                                            <div class="col-md-5 d-flex flex-column justify-content-center p-4 p-md-5">
                                                <i class="bi bi-quote fs-1 text-warning mb-3 opacity-50"></i>
                                                @if($videoTestimonial->message)
                                                    <p class="fs-5 fst-italic mb-4">"{{ $videoTestimonial->message }}"</p>
                                                @endif
                                                <div>
                                                    <h5 class="fw-bold mb-1">{{ $videoTestimonial->name }}</h5>
                                                    <div class="text-warning small mb-2">{{ $videoTestimonial->role }}</div>
                                                    <div>
                                                        @for ($i = 1; $i <= 5; $i++)
                                                            <i class="bi bi-star{{ $i <= $videoTestimonial->rating ? '-fill' : '' }} text-warning"></i>
                                                        @endfor
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        @if($videoTestimonials->count() > 1)
                            <div class="vt-controls mt-3 d-flex justify-content-center align-items-center gap-3">
                                <button class="vt-btn vt-prev" aria-label="Previous testimonial">
                                    <i class="bi bi-chevron-left"></i>
                                </button>
                                <div class="vt-dots">
                                    @foreach($videoTestimonials as $index => $vt)
                                        <span class="vt-dot {{ $index === 0 ? 'active' : '' }}" data-index="{{ $index }}"></span>
                                    @endforeach
                                </div>
                                <button class="vt-btn vt-next" aria-label="Next testimonial">
                                    <i class="bi bi-chevron-right"></i>
                                </button>
                            </div>
                        @endif
                    </div>
                </div>
            @endif

            @if($testimonials && $testimonials->count() > 0)
                <h4 class="text-warning mb-4">Written Testimonials</h4>
                <div class="row g-4">
                    @forelse($testimonials as $testimonial)
                        <div class="col-md-6">
                            <div class="d-flex gap-4">
                                <div class="flex-shrink-0">
                                    @if ($testimonial->image)
                                        <img src="{{ asset('storage/' . $testimonial->image) }}"
                                            alt="{{ $testimonial->name }}"
                                            class="rounded-circle border border-3 border-warning"
                                            style="width: 80px; height: 80px; object-fit: cover;">
                                    @else
                                        <div class="rounded-circle border border-3 border-warning bg-secondary d-flex align-items-center justify-content-center"
                                            style="width: 80px; height: 80px;">
                                            <i class="bi bi-person text-white fs-3"></i>
                                        </div>
                                    @endif
                                    <div class="bg-warning rounded-circle d-flex align-items-center justify-content-center position-absolute"
                                        style="width: 30px; height: 30px; margin-top: -20px; margin-left: 50px;">
                                        <i class="bi bi-quote text-white"></i>
                                    </div>
                                </div>
                                <div>
                                    <p class="text-white opacity-75 mb-4">{{ $testimonial->message }}</p>
                                    <h5 class="fw-bold text-white mb-1">{{ $testimonial->name }}</h5>
                                    <div class="text-white opacity-50">{{ $testimonial->role }}</div>
                                    <div class="mt-2">
                                        @for ($i = 1; $i <= 5; $i++)
                                            <i
                                                class="bi bi-star{{ $i <= $testimonial->rating ? '-fill' : '' }} text-warning"></i>
                                        @endfor
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                    @endforelse
                </div>
            @endif
        </div>
    </section>


@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const slider = document.getElementById('videoTestimonialsSlider');
    if (!slider) return;

    const slides = slider.querySelectorAll('.vt-slide');
    const dots = slider.querySelectorAll('.vt-dot');
    const prevBtn = slider.querySelector('.vt-prev');
    const nextBtn = slider.querySelector('.vt-next');
    const totalSlides = slides.length;

    if (totalSlides <= 1) return;

    let currentIndex = 0;
    let isAnimating = false;
    let autoPlayTimer = null;
    const AUTO_PLAY_INTERVAL = 6000;

    function goToSlide(nextIndex) {
        if (isAnimating || nextIndex === currentIndex) return;
        isAnimating = true;

        const currentSlide = slides[currentIndex];
        const nextSlide = slides[nextIndex];

        // Position the incoming slide off-screen to the right
        nextSlide.style.transition = 'none';
        nextSlide.classList.remove('vt-active', 'vt-exit-left');
        nextSlide.classList.add('vt-enter-right');

        // Force reflow so the browser registers the starting position
        void nextSlide.offsetWidth;

        // Re-enable transition and animate both slides
        nextSlide.style.transition = '';
        currentSlide.classList.remove('vt-active');
        currentSlide.classList.add('vt-exit-left');
        nextSlide.classList.remove('vt-enter-right');
        nextSlide.classList.add('vt-active');

        // Update dots
        dots.forEach(d => d.classList.remove('active'));
        if (dots[nextIndex]) dots[nextIndex].classList.add('active');

        // Clean up after transition finishes
        setTimeout(function () {
            currentSlide.classList.remove('vt-exit-left');
            isAnimating = false;
        }, 850);

        currentIndex = nextIndex;
    }

    function goToPrevSlide() {
        const prevIndex = (currentIndex - 1 + totalSlides) % totalSlides;

        if (isAnimating) return;
        isAnimating = true;

        const currentSlide = slides[currentIndex];
        const prevSlide = slides[prevIndex];

        // Position incoming slide off-screen to the left
        prevSlide.style.transition = 'none';
        prevSlide.classList.remove('vt-active', 'vt-exit-left', 'vt-enter-right');
        prevSlide.style.transform = 'translateX(-100%)';
        prevSlide.style.opacity = '0';

        void prevSlide.offsetWidth;

        prevSlide.style.transition = '';
        prevSlide.style.transform = '';
        prevSlide.style.opacity = '';

        // Current slide exits to the right
        currentSlide.classList.remove('vt-active');
        currentSlide.style.transform = 'translateX(100%)';
        currentSlide.style.opacity = '0';
        prevSlide.classList.add('vt-active');

        dots.forEach(d => d.classList.remove('active'));
        if (dots[prevIndex]) dots[prevIndex].classList.add('active');

        setTimeout(function () {
            currentSlide.style.transform = '';
            currentSlide.style.opacity = '';
            isAnimating = false;
        }, 850);

        currentIndex = prevIndex;
    }

    function nextSlide() {
        goToSlide((currentIndex + 1) % totalSlides);
    }

    function startAutoPlay() {
        stopAutoPlay();
        autoPlayTimer = setInterval(nextSlide, AUTO_PLAY_INTERVAL);
    }

    function stopAutoPlay() {
        if (autoPlayTimer) {
            clearInterval(autoPlayTimer);
            autoPlayTimer = null;
        }
    }

    // Button handlers
    if (nextBtn) {
        nextBtn.addEventListener('click', function () {
            stopAutoPlay();
            nextSlide();
            startAutoPlay();
        });
    }
    if (prevBtn) {
        prevBtn.addEventListener('click', function () {
            stopAutoPlay();
            goToPrevSlide();
            startAutoPlay();
        });
    }

    // Dot handlers
    dots.forEach(function (dot) {
        dot.addEventListener('click', function () {
            var idx = parseInt(this.getAttribute('data-index'));
            stopAutoPlay();
            goToSlide(idx);
            startAutoPlay();
        });
    });

    // Pause on hover
    slider.addEventListener('mouseenter', stopAutoPlay);
    slider.addEventListener('mouseleave', startAutoPlay);

    startAutoPlay();
});
</script>
@endpush

@endsection
