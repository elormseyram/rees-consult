@extends('layouts.app')

@section('title', 'About Rees Consult - IELTS, GRE, SAT, TOEFL, G-MAT, TESOL, NCLEX, OET & Study Abroad Experts')

@section('description',
    'Learn about Rees Consult mission to empower African students through world-class IELTS
    tutoring, test preparation, and international education consultancy services.')

@section('keywords',
    'about Rees Consult, IELTS, GRE, SAT, TOEFL, G-MAT, TESOL, NCLEX, OET experts, education consultancy, study abroad services, test preparation
    company')

@section('og_title', 'About Rees Consult - Empowering African Students')

@section('og_description',
    'Discover our mission, values, and commitment to helping African students achieve their
    international education goals.')

@section('content')
    @php
        use App\Helpers\HeroImageHelper;
        use App\Helpers\ServiceOfferingHelper;
        use App\Helpers\PricingHelper;
        $heroImage = HeroImageHelper::getHeroImageData('about');
        $imageUrl = $heroImage
            ? HeroImageHelper::getHeroImageUrl('about')
            : 'https://images.unsplash.com/photo-1523240795612-9a054b0db644?w=1600';
        $overlayOpacity = $heroImage ? $heroImage->overlay_opacity : 0.8;
        $offerings = ServiceOfferingHelper::getAllByCategory();
        $pricings = PricingHelper::getAllPricings();
    @endphp

    <!-- Page Header -->
    <div class="page-header"
        style="background: linear-gradient(rgba(10, 30, 66, {{ $overlayOpacity }}), rgba(10, 30, 66, {{ $overlayOpacity }})), url('{{ $imageUrl }}') center/cover; padding: 100px 0; color: white; text-align: center;">
        <div class="container">
            <h1 class="display-4 fw-bold mb-3">About Us</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb justify-content-center mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-white text-decoration-none">Home</a>
                    </li>
                    <li class="breadcrumb-item active text-white-50" aria-current="page">About Us</li>
                </ol>
            </nav>
        </div>
    </div>

    <!-- Welcome Section -->
    <section class="py-5">
        <div class="container py-5">
            <div class="row align-items-center">
                <div class="col-lg-6 mb-5 mb-lg-0">
                    <div class="text-uppercase mb-2 ls-1" style="color: #F8A706;">About Us</div>
                    <h2 class="display-5 fw-bold mb-4" style="color: #07294D;">Welcome to Ree's Consult</h2>
                    <p class="lead text-muted mb-4">Providing expert guidance and preparation for individuals taking the
                        International English Language Testing System (IELTS) exam including GRE, SAT, TOEFL, G-MAT, TESOL, NCLEX, OET.</p>
                    <p class="text-muted mb-4">We also assist clients with studying and working abroad, including travel
                        arrangements, visa applications, and career pathways. Ree's Consult aims to empower individuals to
                        achieve their global aspirations through education and career opportunities.</p>
                    <a href="#" class="btn btn-primary px-4 py-2">Explore More</a>
                </div>
                <div class="col-lg-6 position-relative">
                    <!-- Decorative Pattern -->
                    <div class="position-absolute top-0 end-0 translate-middle-y d-none d-lg-block">
                        <div class="rounded-circle bg-warning opacity-75" style="width: 100px; height: 100px;"></div>
                    </div>
                    <div class="row g-2">
                        <div class="col-12">
                            <!-- Placeholder for the dotted pattern and triangle image from design -->
                            <div class="position-relative p-5 bg-light rounded-3 overflow-hidden">
                                <div
                                    style="background-image: radial-gradient(#cbd5e1 2px, transparent 2px); background-size: 20px 20px; height: 300px; width: 100%;">
                                </div>
                                <div class="position-absolute top-50 start-50 translate-middle">
                                    <div
                                        style="width: 0; height: 0; border-top: 60px solid transparent; border-bottom: 60px solid transparent; border-left: 100px solid #e83e8c; transform: rotate(-15deg);">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Core Values Section -->
    <section class="py-5 bg-light">
        <div class="container py-5">
            <div class="row g-4">
                <div class="col-md-4">
                    <div class="h-100 p-4 bg-white rounded-3 shadow-sm hover-lift transition-all">
                        <div class="display-4 fw-bold text-muted opacity-25 mb-3">01</div>
                        <h3 class="h4 fw-bold mb-3" style="color: #07294D;">Our Core Values</h3>
                        <p class="text-muted">At Ree's Consult, we value excellence, innovation, and client satisfaction. We
                            strive to deliver exceptional educational and career pathway services, foster collaborative
                            relationships, and continuously improve our expertise.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="h-100 p-4 bg-white rounded-3 shadow-sm hover-lift transition-all">
                        <div class="display-4 fw-bold text-muted opacity-25 mb-3">02</div>
                        <h3 class="h4 fw-bold mb-3" style="color: #07294D;">Our Mission</h3>
                        <p class="text-muted">To provide world-class educational and job consulting as well as test
                            preparation services that empower African students to successfully pursue their academic, career
                            and professional goals internationally.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="h-100 p-4 bg-white rounded-3 shadow-sm hover-lift transition-all">
                        <div class="display-4 fw-bold text-muted opacity-25 mb-3">03</div>
                        <h3 class="h4 fw-bold mb-3" style="color: #07294D;">Our Visions</h3>
                        <p class="text-muted">Transforming lives through education and career mobility, connecting people,
                            cultures, and opportunities worldwide.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Test Preparation Section -->
    @if (isset($offerings['test_preparation']) && $offerings['test_preparation']->count() > 0)
        <section class="py-5 bg-light">
            <div class="container py-5">
                <div class="text-uppercase mb-2 ls-1" style="color: #F8A706;">Services</div>
                <h2 class="display-5 fw-bold mb-5" style="color: #07294D;">Tutoring Standardised Tests</h2>

                <div class="row g-4">
                    @foreach ($offerings['test_preparation'] as $test)
                        <div class="col-md-6 col-lg-4">
                            <div class="h-100 p-4 bg-white rounded-3 shadow-sm hover-lift transition-all text-center">
                                @if ($test->icon)
                                    <div class="display-4 mb-3" style="color: #F8A706;"><i
                                            class="bi {{ $test->icon }}"></i></div>
                                @endif
                                <h4 class="fw-bold mb-2" style="color: #07294D;">{{ $test->title }}</h4>
                                @if ($test->description)
                                    <p class="text-muted small">{{ $test->description }}</p>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    <!-- Schools & Scholarships Section -->
    @if (isset($offerings['school_scholarship']) && $offerings['school_scholarship']->count() > 0)
        <section class="py-5">
            <div class="container py-5">
                <div class="row g-4">
                    @foreach ($offerings['school_scholarship'] as $school)
                        <div class="col-lg-12">
                            <div class="p-5 bg-light rounded-3 shadow-sm">
                                @if ($school->icon)
                                    <div class="display-4 mb-3" style="color: #F8A706;"><i
                                            class="bi {{ $school->icon }}"></i></div>
                                @endif
                                <h3 class="fw-bold mb-3" style="color: #07294D;">{{ $school->title }}</h3>
                                @if ($school->description)
                                    <p class="text-muted lead">{{ $school->description }}</p>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    <!-- Jobs Abroad Section -->
    @if (isset($offerings['job_abroad']) && $offerings['job_abroad']->count() > 0)
        <section class="py-5 bg-light">
            <div class="container py-5">
                <div class="row g-4">
                    @foreach ($offerings['job_abroad'] as $job)
                        <div class="col-lg-12">
                            <div class="p-5 bg-white rounded-3 shadow-sm">
                                @if ($job->icon)
                                    <div class="display-4 mb-3" style="color: #F8A706;"><i
                                            class="bi {{ $job->icon }}"></i></div>
                                @endif
                                <h3 class="fw-bold mb-3" style="color: #07294D;">{{ $job->title }}</h3>
                                @if ($job->description)
                                    <p class="text-muted lead">{{ $job->description }}</p>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    <!-- Cost Section -->
    <section class="py-5 position-relative"
        style="background: linear-gradient(rgba(10, 30, 66, 0.95), rgba(10, 30, 66, 0.95)), url('https://images.unsplash.com/photo-1541339907198-e08756dedf3f?w=1600') center/cover fixed;">
        <div class="container py-5">
            <div class="row justify-content-center">
                <div class="col-lg-8 text-center text-white">
                    <h2 class="display-5 fw-bold mb-4">Cost Involved</h2>
                    <div class="display-3 fw-bold mb-2" style="color: #F8A706;">GHS 100,000.00</div>
                    <div class="h4 mb-4">or</div>
                    <div class="display-3 fw-bold" style="color: #F8A706;">$10,000 USD</div>
                </div>
            </div>
        </div>
    </section>

    <!-- Benefits Section -->
    @if (isset($offerings['benefits']) && $offerings['benefits']->count() > 0)
        <section class="py-5">
            <div class="container py-5">
                <div class="text-uppercase mb-2 ls-1" style="color: #F8A706;">What You Get</div>
                <h2 class="display-5 fw-bold mb-5" style="color: #07294D;">Benefits Included</h2>

                <div class="row g-4">
                    @foreach ($offerings['benefits'] as $benefit)
                        <div class="col-md-6 col-lg-3">
                            <div class="h-100 p-4 bg-light rounded-3 text-center">
                                @if ($benefit->icon)
                                    <div class="display-4 mb-3" style="color: #F8A706;"><i
                                            class="bi {{ $benefit->icon }}"></i></div>
                                @endif
                                <h4 class="fw-bold" style="color: #07294D;">{{ $benefit->title }}</h4>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    <!-- Countries Section -->
    @if (isset($offerings['countries']) && $offerings['countries']->count() > 0)
        <section class="py-5 bg-light">
            <div class="container py-5">
                <div class="text-uppercase mb-2 ls-1" style="color: #F8A706;">Destinations</div>
                <h2 class="display-5 fw-bold mb-5" style="color: #07294D;">Available Countries</h2>

                <div class="row g-4">
                    @foreach ($offerings['countries'] as $country)
                        <div class="col-md-6 col-lg-4">
                            <div class="h-100 p-4 bg-white rounded-3 shadow-sm text-center">
                                @if ($country->icon)
                                    <div class="display-4 mb-3" style="color: #F8A706;"><i
                                            class="bi {{ $country->icon }}"></i></div>
                                @endif
                                <h4 class="fw-bold" style="color: #07294D;">{{ $country->title }}</h4>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    <!-- Vacancies Section -->
    @if (isset($offerings['vacancies']) && $offerings['vacancies']->count() > 0)
        <section class="py-5">
            <div class="container py-5">
                <div class="text-uppercase mb-2 ls-1" style="color: #F8A706;">Job Opportunities</div>
                <h2 class="display-5 fw-bold mb-5" style="color: #07294D;">Available Vacancies</h2>

                <div class="row g-4">
                    @foreach ($offerings['vacancies'] as $vacancy)
                        <div class="col-md-6 col-lg-4">
                            <div class="h-100 p-4 bg-light rounded-3 text-center">
                                @if ($vacancy->icon)
                                    <div class="display-4 mb-3" style="color: #F8A706;"><i
                                            class="bi {{ $vacancy->icon }}"></i></div>
                                @endif
                                <h4 class="fw-bold" style="color: #07294D;">{{ $vacancy->title }}</h4>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    <!-- Duration Section -->
    <section class="py-5 position-relative"
        style="background: linear-gradient(rgba(10, 30, 66, 0.95), rgba(10, 30, 66, 0.95)), url('https://images.unsplash.com/photo-1541339907198-e08756dedf3f?w=1600') center/cover fixed;">
        <div class="container py-5">
            <div class="row justify-content-center">
                <div class="col-lg-8 text-center text-white">
                    <h2 class="display-5 fw-bold mb-4">Process Duration</h2>
                    <div class="display-2 fw-bold" style="color: #F8A706;">6 Months</div>
                </div>
            </div>
        </div>
    </section>

    <!-- Partners Section -->
    @if (isset($offerings['partners']) && $offerings['partners']->count() > 0)
        <section class="py-5">
            <div class="container py-5">
                <div class="text-uppercase mb-2 ls-1" style="color: #F8A706;">Our Partners</div>
                <h2 class="display-5 fw-bold mb-5" style="color: #07294D;">Trusted Partners</h2>

                <div class="row g-4 align-items-center">
                    @foreach ($offerings['partners'] as $partner)
                        <div class="col-md-6 col-lg-4">
                            <div class="h-100 p-4 bg-light rounded-3 shadow-sm text-center">
                                @if ($partner->icon)
                                    <div class="display-4 mb-3" style="color: #F8A706;"><i
                                            class="bi {{ $partner->icon }}"></i></div>
                                @endif
                                <h5 class="fw-bold" style="color: #07294D;">{{ $partner->title }}</h5>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    <!-- Pricing Section -->
    @if ($pricings->count() > 0)
        <section class="py-5 bg-light">
            <div class="container py-5">
                <div class="text-uppercase mb-2 ls-1" style="color: #F8A706;">Pricing</div>
                <h2 class="display-5 fw-bold mb-5" style="color: #07294D;">Price List (USD & GHS)</h2>

                <div class="table-responsive">
                    <table class="table table-bordered text-center align-middle">
                        <thead class="table-dark">
                            <tr>
                                <th style="color: white !important;">Test Name</th>
                                <th colspan="2" style="color: white !important;">Group of 10</th>
                                <th colspan="2" style="color: white !important;">One-on-One</th>
                            </tr>
                            <tr>
                                <th style="color: white !important;"></th>
                                <th style="color: white !important;">In Person</th>
                                <th style="color: white !important;">Online</th>
                                <th style="color: white !important;">In Person</th>
                                <th style="color: white !important;">Online</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($pricings as $pricing)
                                <tr>
                                    <td class="fw-bold" style="color: #07294D;">{{ $pricing->test_name }}</td>
                                    <td>
                                        <span class="fw-bold text-primary">${{ number_format(round($pricing->group_in_person / 12), 0) }}</span>
                                        <span class="text-muted d-block small">(GH₵ {{ number_format($pricing->group_in_person) }})</span>
                                    </td>
                                    <td>
                                        <span class="fw-bold text-primary">${{ number_format(round($pricing->group_online / 12), 0) }}</span>
                                        <span class="text-muted d-block small">(GH₵ {{ number_format($pricing->group_online) }})</span>
                                    </td>
                                    <td>
                                        <span class="fw-bold text-primary">${{ number_format(round($pricing->one_on_one_in_person / 12), 0) }}</span>
                                        <span class="text-muted d-block small">(GH₵ {{ number_format($pricing->one_on_one_in_person) }})</span>
                                    </td>
                                    <td>
                                        <span class="fw-bold text-primary">${{ number_format(round($pricing->one_on_one_online / 12), 0) }}</span>
                                        <span class="text-muted d-block small">(GH₵ {{ number_format($pricing->one_on_one_online) }})</span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </section>
    @endif

    <!-- Stats Section -->
    <section class="py-5 position-relative"
        style="background: linear-gradient(rgba(10, 30, 66, 0.9), rgba(10, 30, 66, 0.9)), url('https://images.unsplash.com/photo-1541339907198-e08756dedf3f?w=1600') center/cover fixed;">
        <div class="container py-5">
            <div class="row text-center text-white g-4">
                <div class="col-md-3 col-6">
                    <div class="display-4 fw-bold mb-2">1250+</div>
                    <div class="bg-warning mx-auto mb-3" style="width: 50px; height: 3px;"></div>
                    <div class="h6 text-uppercase ls-1">Students Enrolled</div>
                </div>
                <div class="col-md-3 col-6">
                    <div class="display-4 fw-bold mb-2">890+</div>
                    <div class="bg-warning mx-auto mb-3" style="width: 50px; height: 3px;"></div>
                    <div class="h6 text-uppercase ls-1">Courses Uploaded</div>
                </div>
                <div class="col-md-3 col-6">
                    <div class="display-4 fw-bold mb-2">2140+</div>
                    <div class="bg-warning mx-auto mb-3" style="width: 50px; height: 3px;"></div>
                    <div class="h6 text-uppercase ls-1">People Certified</div>
                </div>
                <div class="col-md-3 col-6">
                    <div class="display-4 fw-bold mb-2">170+</div>
                    <div class="bg-warning mx-auto mb-3" style="width: 50px; height: 3px;"></div>
                    <div class="h6 text-uppercase ls-1">Global Teachers</div>
                </div>
            </div>
        </div>
    </section>

    <!-- Team Section (Reused/Adapted) -->
    <section class="py-5">
        <div class="container py-5">
            <div class="text-uppercase mb-2 ls-1" style="color: #F8A706;">Staff</div>
            <h2 class="display-5 fw-bold mb-5" style="color: #07294D;">Meet Our Staff</h2>

            <div class="row g-4">
                @forelse($teamMembers as $member)
                    <div class="col-md-6 col-lg-3">
                        <div class="team-card position-relative overflow-hidden rounded-3 shadow-sm">
                            <img src="{{ $member->image ? asset('storage/' . $member->image) : 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=500&q=80' }}"
                                alt="{{ $member->name }}" class="w-100" style="height: 400px; object-fit: cover;">
                            <div class="position-absolute bottom-0 start-0 w-100 p-3 bg-white border-top">
                                <h5 class="fw-bold mb-1">{{ $member->name }}</h5>
                                <p class="text-muted small mb-0">{{ $member->role }}</p>
                                @if ($member->social_links && count($member->social_links) > 0)
                                    <div class="mt-2">
                                        @foreach ($member->social_links as $platform => $url)
                                            @if ($url)
                                                <a href="{{ $url }}" class="text-dark me-2" target="_blank"><i
                                                        class="bi bi-{{ $platform }}"></i></a>
                                            @endif
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12 text-center py-5">
                        <p class="text-muted">No team members available at the moment.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </section>

    <!-- Testimonials Section -->
    <section class="py-5 position-relative"
        style="background: linear-gradient(rgba(0, 0, 0, 0.8), rgba(0, 0, 0, 0.8)), url('https://images.unsplash.com/photo-1522202176988-66273c2fd55f?w=1600') center/cover fixed;">
        <div class="container py-5">
            <div class="text-white mb-5">
                <div class="text-uppercase mb-2 ls-1" style="font-size: 0.9rem; opacity: 0.9; color: #F8A706 !important;">
                    Feedback</div>
                <h2 class="display-5 fw-bold">What Students Say</h2>
            </div>

            <div class="row g-5">
                @forelse($testimonials as $testimonial)
                    <div class="col-lg-6">
                        <div class="d-flex gap-4">
                            <div class="flex-shrink-0 position-relative">
                                @if ($testimonial->image)
                                    <img src="{{ asset('storage/' . $testimonial->image) }}"
                                        alt="{{ $testimonial->name }}" class="rounded-3 shadow-sm"
                                        style="width: 100px; height: 100px; object-fit: cover;">
                                @else
                                    <div class="rounded-3 shadow-sm bg-secondary d-flex align-items-center justify-content-center"
                                        style="width: 100px; height: 100px;">
                                        <i class="bi bi-person text-white fs-1"></i>
                                    </div>
                                @endif
                                <div class="position-absolute top-0 start-100 translate-middle bg-primary rounded-circle d-flex align-items-center justify-content-center shadow-sm"
                                    style="width: 40px; height: 40px; border: 2px solid rgba(255,255,255,0.2);">
                                    <i class="bi bi-quote text-white fs-5"></i>
                                </div>
                            </div>
                            <div>
                                <p class="text-white opacity-75 mb-4 lh-lg">{{ $testimonial->message }}</p>
                                <h5 class="fw-bold text-white mb-1">{{ $testimonial->name }}</h5>
                                <div class="text-white opacity-50 small">{{ $testimonial->role }}</div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12 text-center py-5">
                        <p class="text-white opacity-75">No testimonials available at the moment.</p>
                    </div>
                @endforelse
            </div>

            <!-- Pagination Dots -->
            <div class="d-flex justify-content-center gap-2 mt-5">
                <span class="rounded-circle bg-primary" style="width: 12px; height: 12px; cursor: pointer;"></span>
                <span class="rounded-circle bg-white opacity-25"
                    style="width: 12px; height: 12px; cursor: pointer;"></span>
            </div>
        </div>
    </section>
@endsection
