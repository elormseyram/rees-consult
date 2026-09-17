@extends('layouts.app')

@section('title', 'Work Abroad, Study Abroad & Test Preparation Services in Ghana — Rees Consult')

@section('description', 'Explore Rees Consult’s full range of services: sponsored work abroad job placements in UK, Canada & Europe; study abroad school admissions; and IELTS, GRE, SAT, TOEFL, OET & NCLEX test preparation coaching in Accra, Ghana.')

@section('keywords', 'work abroad services Ghana, study abroad services Ghana, IELTS preparation course Ghana, IELTS coaching Accra, GRE intensive Ghana, SAT college prep Ghana, TOEFL preparation Ghana, OET medical prep Ghana nurses, NCLEX review Ghana, job placement abroad Ghana, school application abroad Ghana, work permit abroad')

@section('content')
@php
    use App\Helpers\HeroImageHelper;
    $heroImage = HeroImageHelper::getHeroImageData('services');
    $imageUrl = $heroImage ? HeroImageHelper::getHeroImageUrl('services') : 'https://images.unsplash.com/photo-1522202176988-66273c2fd55f?w=1600';
    $overlayOpacity = $heroImage ? $heroImage->overlay_opacity : 0.8;
@endphp

<!-- Custom Premium CSS Styles -->
<style>
    :root {
        --primary-blue: #2E5BBA;
        --secondary-yellow: #F8B500;
        --dark-navy: #0A1E42;
        --light-bg: #F4F6F9;
        --glass-bg: rgba(255, 255, 255, 0.85);
        --transition-smooth: all 0.4s cubic-bezier(0.165, 0.84, 0.44, 1);
    }

    .services-portal {
        background-color: var(--light-bg);
        font-family: 'Inter', sans-serif;
    }

    /* Premium Header */
    .portal-header {
        background: linear-gradient(135deg, var(--dark-navy) 0%, #17366b 100%);
        position: relative;
        overflow: hidden;
        padding: 80px 0;
    }
    .portal-header::after {
        content: '';
        position: absolute;
        width: 300px;
        height: 300px;
        background: radial-gradient(circle, rgba(248, 181, 0, 0.15) 0%, transparent 70%);
        top: -100px;
        right: -50px;
        border-radius: 50%;
    }

    /* Currency & Tabs Switchers */
    .premium-nav-tabs {
        border: none;
        background: #ffffff;
        padding: 10px;
        border-radius: 50px;
        box-shadow: 0 15px 35px rgba(10, 30, 66, 0.08);
        margin-bottom: 40px;
    }
    .premium-tab-link {
        border: none !important;
        border-radius: 30px !important;
        font-weight: 700 !important;
        color: var(--dark-navy) !important;
        padding: 12px 30px !important;
        transition: var(--transition-smooth);
        display: flex;
        align-items: center;
        gap: 10px;
    }
    .premium-tab-link.active {
        background: var(--primary-blue) !important;
        color: #ffffff !important;
        box-shadow: 0 8px 20px rgba(46, 91, 186, 0.3);
    }
    .premium-tab-link:hover:not(.active) {
        background: rgba(46, 91, 186, 0.08);
    }

    /* Cards & Interactions */
    .service-card {
        border: none;
        background: #ffffff;
        border-radius: 20px;
        overflow: hidden;
        box-shadow: 0 10px 30px rgba(10, 30, 66, 0.05);
        transition: var(--transition-smooth);
        position: relative;
    }
    .service-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 20px 40px rgba(10, 30, 66, 0.12);
    }
    .service-card-border-top {
        height: 6px;
        width: 100%;
        background: var(--primary-blue);
    }
    .icon-badge {
        width: 70px;
        height: 70px;
        border-radius: 18px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 2.2rem;
        background: rgba(46, 91, 186, 0.1);
        color: var(--primary-blue);
        margin: 0 auto 20px;
        transition: var(--transition-smooth);
    }
    .service-card:hover .icon-badge {
        background: var(--primary-blue);
        color: #ffffff;
        transform: rotate(6deg);
    }



    /* Country Pills Filter */
    .country-filter-pill {
        border: 1px solid rgba(10, 30, 66, 0.15);
        background: #ffffff;
        color: var(--dark-navy);
        font-weight: 600;
        padding: 8px 20px;
        border-radius: 30px;
        cursor: pointer;
        transition: var(--transition-smooth);
    }
    .country-filter-pill.active {
        background: var(--dark-navy);
        color: #ffffff;
        border-color: var(--dark-navy);
        box-shadow: 0 6px 15px rgba(10, 30, 66, 0.15);
    }

    /* Premium Form Styling */
    .premium-form {
        background: #ffffff;
        border-radius: 24px;
        box-shadow: 0 20px 50px rgba(10, 30, 66, 0.08);
        border: 1px solid rgba(10, 30, 66, 0.05);
    }
    .form-step-indicator {
        display: flex;
        justify-content: space-between;
        margin-bottom: 30px;
        position: relative;
    }
    .form-step-indicator::before {
        content: '';
        position: absolute;
        height: 4px;
        background: rgba(10, 30, 66, 0.1);
        width: 100%;
        top: 50%;
        transform: translateY(-50%);
        z-index: 1;
    }
    .form-step-node {
        width: 45px;
        height: 45px;
        border-radius: 50%;
        background: #ffffff;
        border: 3px solid rgba(10, 30, 66, 0.1);
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        z-index: 2;
        color: rgba(10, 30, 66, 0.5);
        transition: var(--transition-smooth);
    }
    .form-step-node.active {
        border-color: var(--primary-blue);
        background: var(--primary-blue);
        color: #ffffff;
        box-shadow: 0 0 15px rgba(46, 91, 186, 0.4);
    }
    .form-step-node.completed {
        border-color: var(--secondary-yellow);
        background: var(--secondary-yellow);
        color: var(--dark-navy);
    }

    /* Custom Floating Inputs */
    .form-floating > .form-control:focus,
    .form-floating > .form-select:focus {
        border-color: var(--primary-blue);
        box-shadow: 0 0 0 0.25rem rgba(46, 91, 186, 0.12);
    }

    /* Modal Animation */
    .modal.fade .modal-dialog {
        transform: scale(0.9) translateY(20px);
        transition: transform 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
    }
    .modal.show .modal-dialog {
        transform: scale(1) translateY(0);
    }
</style>

<!-- Portal Header -->
<div class="portal-header text-white text-center">
    <div class="container">
        <h1 class="display-4 fw-bold mb-3">Our Core Expertise</h1>
        <p class="lead mb-4">Choose from our specialized programs designed to connect you to global educational and professional opportunities.</p>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb justify-content-center mb-0">
                <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-white-50 text-decoration-none">Home</a></li>
                <li class="breadcrumb-item active text-white" aria-current="page">Services</li>
            </ol>
        </nav>
    </div>
</div>

<!-- Main Services Portal -->
<section class="py-5 services-portal">
    <div class="container py-4">
        
        <!-- Live Currency Switcher & Tabs Header (Switcher Removed) -->
        <div class="row align-items-center mb-5">
            <div class="col-12 text-center">
                <ul class="nav nav-tabs premium-nav-tabs d-inline-flex gap-2 mb-0" id="servicesTab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link premium-tab-link active" id="tests-tab" data-bs-toggle="tab" data-bs-target="#tests-content" type="button" role="tab">
                            <i class="bi bi-book"></i> Standardized Tests
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link premium-tab-link" id="school-tab" data-bs-toggle="tab" data-bs-target="#school-content" type="button" role="tab">
                            <i class="bi bi-university"></i> School Applications
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link premium-tab-link" id="jobs-tab" data-bs-toggle="tab" data-bs-target="#jobs-content" type="button" role="tab">
                            <i class="bi bi-briefcase"></i> Job Abroad
                        </button>
                    </li>
                </ul>
            </div>
        </div>

        <!-- Dynamic Tabs Content -->
        <div class="tab-content" id="servicesTabContent">
            
            <!-- ========================================== -->
            <!-- TAB 1: STANDARDIZED TESTS CONTENT          -->
            <!-- ========================================== -->
            <div class="tab-pane fade show active" id="tests-content" role="tabpanel">
                <div class="text-center mb-5">
                    <h2 class="fw-bold text-dark mb-2">Test Preparation & Exam Tuitions</h2>
                    <p class="text-muted max-width-600 mx-auto">Get tutored by certified instructors to secure high bands score. British Council Affiliate partner registration support included.</p>
                </div>

                <div class="row g-4">
                    @forelse($standardizedTests as $test)
                    @php
                        $firstWord = strtoupper(explode(' ', trim($test->title))[0]);
                        $pricing = $pricingsMap->get($firstWord);
                        if ($pricing) {
                            $rates = [
                                $pricing->group_online,
                                $pricing->group_in_person,
                                $pricing->one_on_one_online,
                                $pricing->one_on_one_in_person
                            ];
                            $minGhs = min($rates);
                            $maxGhs = max($rates);
                            $minUsd = round($minGhs / 12);
                            $maxUsd = round($maxGhs / 12);
                            
                            $groupOnlineGhs = $pricing->group_online;
                            $groupInPersonGhs = $pricing->group_in_person;
                            $oneOnOneOnlineGhs = $pricing->one_on_one_online;
                            $oneOnOneInPersonGhs = $pricing->one_on_one_in_person;
                        } else {
                            $minUsd = $test->price;
                            $maxUsd = $test->price;
                            $minGhs = $test->price * 12;
                            $maxGhs = $test->price * 12;
                            
                            $groupOnlineGhs = $test->price * 12;
                            $groupInPersonGhs = $test->price * 12;
                            $oneOnOneOnlineGhs = $test->price * 12;
                            $oneOnOneInPersonGhs = $test->price * 12;
                        }
                        
                        $groupOnlineUsd = round($groupOnlineGhs / 12);
                        $groupInPersonUsd = round($groupInPersonGhs / 12);
                        $oneOnOneOnlineUsd = round($oneOnOneOnlineGhs / 12);
                        $oneOnOneInPersonUsd = round($oneOnOneInPersonGhs / 12);
                    @endphp
                    <div class="col-lg-4 col-md-6">
                        <div class="service-card h-100">
                            <div class="service-card-border-top" style="background-color: {{ $test->color ?? '#2E5BBA' }}"></div>
                            <div class="card-body p-4 d-flex flex-column">
                                <div class="icon-badge" style="background-color: rgba(46, 91, 186, 0.08); color: {{ $test->color ?? '#2E5BBA' }}">
                                    <i class="bi {{ $test->icon ?? 'bi-book-fill' }}"></i>
                                </div>
                                <h3 class="h5 fw-bold text-center mb-2">{{ $test->title }}</h3>
                                <p class="text-muted text-center small mb-4">{{ $test->subtitle }}</p>
                                
                                <div class="content small text-muted mb-4 flex-grow-1">
                                    {!! Str::limit(strip_tags($test->description), 140) !!}
                                </div>

                                <div class="border-top pt-3 mt-auto">
                                    <div class="d-flex flex-column gap-2 mb-3">
                                        <div>
                                            <span class="small text-muted d-block">Program Tuition:</span>
                                            <strong class="h6 fw-bold text-primary">${{ number_format($minUsd) }} - ${{ number_format($maxUsd) }} <span class="text-muted fw-normal" style="font-size: 0.85rem;">(GH₵ {{ number_format($minGhs) }} - GH₵ {{ number_format($maxGhs) }})</span></strong>
                                        </div>
                                        <div class="d-flex justify-content-between align-items-center">
                                            <span class="small text-muted">Admin Support:</span>
                                            <strong class="text-dark small">${{ number_format($test->processing_fee, 0) }} (GH₵ {{ number_format($test->processing_fee * 12, 0) }})</strong>
                                        </div>
                                    </div>
                                    <div class="d-flex gap-2">
                                        <a href="{{ route('services.show', $test->slug) }}" class="btn btn-outline-secondary btn-sm rounded-pill w-100">Details</a>
                                        <button class="btn btn-primary btn-sm rounded-pill w-100" data-bs-toggle="modal" data-bs-target="#signupModal-{{ $test->id }}">Register</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- TEST PREP SIGNUP MODAL -->
                    <div class="modal fade" id="signupModal-{{ $test->id }}" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content border-0 rounded-4 shadow-lg">
                                <div class="modal-header border-0 bg-light rounded-top-4 p-4">
                                    <div>
                                        <h5 class="modal-title fw-bold text-dark"><i class="bi bi-pencil-square me-2 text-primary"></i>Tuition Enrollment</h5>
                                        <p class="text-muted small mb-0">{{ $test->title }} Prep Class</p>
                                    </div>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body p-4">
                                    <div class="alert alert-primary bg-light border-0 d-flex flex-column gap-2 p-3 mb-4 rounded-3 small">
                                        <div class="d-flex justify-content-between">
                                            <span>Tuition Fee:</span>
                                            <strong id="modal-tuition-fee-{{ $test->id }}">${{ number_format($groupInPersonUsd, 2) }} (GH₵ {{ number_format($groupInPersonGhs, 2) }})</strong>
                                        </div>
                                        <div class="d-flex justify-content-between">
                                            <span>Admin Fee:</span>
                                            <strong>${{ number_format($test->processing_fee, 2) }} (GH₵ {{ number_format($test->processing_fee * 12, 2) }})</strong>
                                        </div>
                                    </div>
                                    
                                    <form action="{{ route('services.signup') }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="service_id" value="{{ $test->id }}">
                                        
                                        <div class="row g-2 mb-3">
                                            <div class="col-md-6 form-floating">
                                                <input type="text" name="first_name" class="form-control" id="fn-{{ $test->id }}" placeholder="First Name" required>
                                                <label for="fn-{{ $test->id }}">First Name</label>
                                            </div>
                                            <div class="col-md-6 form-floating">
                                                <input type="text" name="last_name" class="form-control" id="ln-{{ $test->id }}" placeholder="Last Name" required>
                                                <label for="ln-{{ $test->id }}">Last Name</label>
                                            </div>
                                        </div>

                                        <div class="form-floating mb-3">
                                            <input type="email" name="email" class="form-control" id="em-{{ $test->id }}" placeholder="Email Address" required>
                                            <label for="em-{{ $test->id }}">Email Address</label>
                                        </div>

                                        <div class="form-floating mb-3">
                                            <input type="tel" name="phone" class="form-control" id="ph-{{ $test->id }}" placeholder="Phone Number" required>
                                            <label for="ph-{{ $test->id }}">Phone Number</label>
                                        </div>

                                        <div class="mb-3">
                                             <div class="form-floating mb-2">
                                                 <select name="preferred_class_type" class="form-select" id="ct-{{ $test->id }}" onchange="updateModalPrice(this, '{{ $test->id }}')" required>
                                                     <option value="group_online" data-ghs="{{ $groupOnlineGhs }}" data-usd="{{ $groupOnlineUsd }}">Group Classes - Online (${{ number_format($groupOnlineUsd) }} / GH₵ {{ number_format($groupOnlineGhs) }})</option>
                                                     <option value="group_in_person" data-ghs="{{ $groupInPersonGhs }}" data-usd="{{ $groupInPersonUsd }}" selected>Group Classes - In-Person (${{ number_format($groupInPersonUsd) }} / GH₵ {{ number_format($groupInPersonGhs) }})</option>
                                                     <option value="one_on_one_online" data-ghs="{{ $oneOnOneOnlineGhs }}" data-usd="{{ $oneOnOneOnlineUsd }}">1-on-1 Classes - Online (${{ number_format($oneOnOneOnlineUsd) }} / GH₵ {{ number_format($oneOnOneOnlineGhs) }})</option>
                                                     <option value="one_on_one_in_person" data-ghs="{{ $oneOnOneInPersonGhs }}" data-usd="{{ $oneOnOneInPersonUsd }}">1-on-1 Classes - In-Person (${{ number_format($oneOnOneInPersonUsd) }} / GH₵ {{ number_format($oneOnOneInPersonGhs) }})</option>
                                                 </select>
                                                 <label for="ct-{{ $test->id }}">Preferred Class Format</label>
                                             </div>
                                             <!-- Dynamic Price Display Badge Next to/Below Dropdown -->
                                             <div class="d-flex align-items-center justify-content-between p-2 px-3 rounded bg-light border border-light-subtle">
                                                 <span class="text-muted small fw-semibold"><i class="bi bi-tag-fill text-primary me-1"></i>Selected Format Fee:</span>
                                                 <strong class="text-primary small" id="dropdown-price-{{ $test->id }}">
                                                     ${{ number_format($groupInPersonUsd) }} (GH₵ {{ number_format($groupInPersonGhs) }})
                                                 </strong>
                                             </div>
                                         </div>

                                        <div class="form-floating mb-4">
                                            <textarea name="notes" class="form-control" placeholder="Any extra information" id="nt-{{ $test->id }}" style="height: 100px;"></textarea>
                                            <label for="nt-{{ $test->id }}">Extra Requests / Target Score</label>
                                        </div>

                                        <button type="submit" class="btn btn-primary w-100 py-3 rounded-pill fw-bold shadow">Submit Application</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="col-12 text-center py-5">
                        <i class="bi bi-slash-circle text-muted display-4 d-block mb-3"></i>
                        <h5>No Prep Tests Available</h5>
                    </div>
                    @endforelse
                </div>
            </div>

            <!-- ========================================== -->
            <!-- TAB 2: SCHOOL APPLICATIONS CONTENT         -->
            <!-- ========================================== -->
            <div class="tab-pane fade" id="school-content" role="tabpanel">
                <div class="text-center mb-5">
                    <h2 class="fw-bold text-dark mb-2">School Applications & Placements Abroad</h2>
                    <p class="text-muted max-width-600 mx-auto">Get placed into top-tier accredited global universities. Read package fees and submit your assessment below.</p>
                </div>

                <div class="row justify-content-center">
                    <div class="col-lg-5 mb-5 mb-lg-0">
                        <div class="card border-0 shadow-sm rounded-4 p-4 mb-4">
                            <h4 class="fw-bold text-primary mb-3"><i class="bi bi-info-circle-fill me-2"></i>Admissions Assistance Packages</h4>
                            
                            @forelse($schoolApplications as $package)
                            <div class="mb-4 pb-3 border-bottom">
                                <h5 class="fw-bold mb-1">{{ $package->title }}</h5>
                                <p class="text-muted small mb-2">{{ $package->subtitle }}</p>
                                <div class="d-flex flex-column gap-1">
                                    <span class="small text-muted">Assessment & Advisory Fee: <strong class="text-primary">${{ number_format($package->price, 0) }} (GH₵ {{ number_format($package->price * 12, 0) }})</strong></span>
                                    <span class="small text-muted">Visa Processing support: <strong class="text-dark">${{ number_format($package->processing_fee, 0) }} (GH₵ {{ number_format($package->processing_fee * 12, 0) }})</strong></span>
                                </div>
                            </div>
                            @empty
                            <p class="text-muted small">No dynamic school placement packages listed yet.</p>
                            @endforelse

                            <div class="bg-light p-3 rounded-3 mt-4 small text-muted">
                                <strong class="text-dark d-block mb-1">What we handle:</strong>
                                <ul class="mb-0 ps-3">
                                    <li>Pre-evaluation and matching lists</li>
                                    <li>Expert Personal Statement reviews</li>
                                    <li>Application Fee Waivers coordination</li>
                                    <li>100% student visa interview preparation mock runs</li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-7">
                        <div class="premium-form p-4 p-md-5">
                            <h4 class="fw-bold mb-4 text-center text-dark"><i class="bi bi-file-earmark-person-fill text-warning me-2"></i>Study Assessment Questionnaire</h4>
                            
                            <!-- Multi-step indicators -->
                            <div class="form-step-indicator">
                                <span class="form-step-node active" id="step-node-1">1</span>
                                <span class="form-step-node" id="step-node-2">2</span>
                                <span class="form-step-node" id="step-node-3">3</span>
                            </div>

                            <form action="{{ route('services.school-apply') }}" method="POST" enctype="multipart/form-data" id="schoolApplicationForm">
                                @csrf
                                
                                <!-- STEP 1: CONTACT DETAILS -->
                                <div class="form-step-section" id="step-section-1">
                                    <h5 class="fw-bold text-primary mb-3">Step 1: Lead Information</h5>
                                    <div class="row g-2 mb-3">
                                        <div class="col-md-6 form-floating">
                                            <input type="text" name="first_name" class="form-control" id="sc-fn" placeholder="First Name" required>
                                            <label for="sc-fn">First Name</label>
                                        </div>
                                        <div class="col-md-6 form-floating">
                                            <input type="text" name="last_name" class="form-control" id="sc-ln" placeholder="Last Name" required>
                                            <label for="sc-ln">Last Name</label>
                                        </div>
                                    </div>
                                    <div class="form-floating mb-3">
                                        <input type="email" name="email" class="form-control" id="sc-em" placeholder="Email Address" required>
                                        <label for="sc-em">Email Address</label>
                                    </div>
                                    <div class="form-floating mb-4">
                                        <input type="tel" name="phone" class="form-control" id="sc-ph" placeholder="Phone Number" required>
                                        <label for="sc-ph">Phone Number</label>
                                    </div>
                                    <button type="button" class="btn btn-primary rounded-pill px-4 float-end" onclick="nextFormStep(2)">Next <i class="bi bi-arrow-right"></i></button>
                                </div>

                                <!-- STEP 2: EDUCATION PLAN -->
                                <div class="form-step-section d-none" id="step-section-2">
                                    <h5 class="fw-bold text-primary mb-3">Step 2: Educational Intentions</h5>
                                    <div class="form-floating mb-3">
                                        <input type="text" name="course_of_interest" class="form-control" id="sc-coi" placeholder="Course of Interest" required>
                                        <label for="sc-coi">Target Course (e.g. Master in Data Science)</label>
                                    </div>
                                    <div class="form-floating mb-3">
                                        <select name="level_of_education" class="form-select" id="sc-loe" required>
                                            <option value="Undergraduate">Undergraduate Study (Bachelors)</option>
                                            <option value="Postgraduate" selected>Postgraduate Study (Masters)</option>
                                            <option value="PhD">PhD / Doctorate Placements</option>
                                            <option value="Diploma">Diploma / Certifications</option>
                                        </select>
                                        <label for="sc-loe">Level of Education</label>
                                    </div>
                                    <div class="mb-4">
                                        <label class="form-label text-dark fw-bold small d-block">Target Countries (Select all that apply)</label>
                                        <div class="row g-2">
                                            <div class="col-6">
                                                <input type="checkbox" name="target_countries[]" value="Canada" id="c-can" checked>
                                                <label for="c-can" class="small text-muted ms-1">Canada</label>
                                            </div>
                                            <div class="col-6">
                                                <input type="checkbox" name="target_countries[]" value="United Kingdom" id="c-uk" checked>
                                                <label for="c-uk" class="small text-muted ms-1">United Kingdom</label>
                                            </div>
                                            <div class="col-6">
                                                <input type="checkbox" name="target_countries[]" value="United States" id="c-us">
                                                <label for="c-us" class="small text-muted ms-1">United States</label>
                                            </div>
                                            <div class="col-6">
                                                <input type="checkbox" name="target_countries[]" value="Germany" id="c-ger">
                                                <label for="c-ger" class="small text-muted ms-1">Germany</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="d-flex justify-content-between">
                                        <button type="button" class="btn btn-outline-secondary rounded-pill px-4" onclick="prevFormStep(1)"><i class="bi bi-arrow-left"></i> Back</button>
                                        <button type="button" class="btn btn-primary rounded-pill px-4" onclick="nextFormStep(3)">Next <i class="bi bi-arrow-right"></i></button>
                                    </div>
                                </div>

                                <!-- STEP 3: QUALIFICATIONS & UPLOADS -->
                                <div class="form-step-section d-none" id="step-section-3">
                                    <h5 class="fw-bold text-primary mb-3">Step 3: Background & Attachments</h5>
                                    
                                    <div class="form-floating mb-3">
                                        <input type="text" name="highest_qualification" class="form-control" id="sc-hq" placeholder="Highest Qualification (e.g. BSc Chemistry)" required>
                                        <label for="sc-hq">Highest Degree / Qualification Obtained</label>
                                    </div>

                                    <div class="form-floating mb-3">
                                        <select name="budget" class="form-select" id="sc-bd" required>
                                            <option value="Under $5k">Under $5,000 USD / Year</option>
                                            <option value="$5k-$10k" selected>$5,000 - $10,000 USD / Year</option>
                                            <option value="$10k-$20k">$10,000 - $20,000 USD / Year</option>
                                            <option value="$20k+">Over $20,000 USD / Year</option>
                                        </select>
                                        <label for="sc-bd">Yearly Tuition Budget Allocation</label>
                                    </div>

                                    <div class="mb-3">
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" name="has_passport" id="sc-pass" value="1" checked>
                                            <label class="form-check-label small fw-bold text-dark" for="sc-pass">I possess an active Passport</label>
                                        </div>
                                    </div>

                                    <div class="row g-2 mb-4">
                                        <div class="col-md-6">
                                            <label class="form-label small fw-bold text-dark">Attach CV/Resume (Optional, PDF)</label>
                                            <input type="file" name="resume" class="form-control form-control-sm">
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label small fw-bold text-dark">Attach Academic Transcript (Optional)</label>
                                            <input type="file" name="transcript" class="form-control form-control-sm">
                                        </div>
                                    </div>

                                    <div class="d-flex justify-content-between">
                                        <button type="button" class="btn btn-outline-secondary rounded-pill px-4" onclick="prevFormStep(2)"><i class="bi bi-arrow-left"></i> Back</button>
                                        <button type="submit" class="btn btn-warning text-white fw-bold rounded-pill px-4">Submit Evaluation</button>
                                    </div>
                                </div>

                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ========================================== -->
            <!-- TAB 3: JOB ABROAD CONTENT                  -->
            <!-- ========================================== -->
            <div class="tab-pane fade" id="jobs-content" role="tabpanel">
                <div class="text-center mb-5">
                    <h2 class="fw-bold text-dark mb-2">Global Job Placements & sponsored Work Slots</h2>
                    <p class="text-muted max-width-600 mx-auto">Explore sponsored vacancies that come with active job sponsorship and visa coordination. Select target country pills to filter.</p>
                </div>

                <!-- Country Pill Filters -->
                <div class="d-flex justify-content-center gap-2 mb-5 flex-wrap">
                    <button class="country-filter-pill active" onclick="filterCountry('all')">All Destinations</button>
                    @foreach($jobAbroad->pluck('country')->unique()->sort() as $c)
                        <button class="country-filter-pill" onclick="filterCountry('{{ $c }}')">
                            {{ $c }}
                            @if($c === 'United Kingdom' || $c === 'UK') 🇬🇧
                            @elseif($c === 'Canada') 🇨🇦
                            @elseif($c === 'Australia') 🇦🇺
                            @elseif($c === 'New Zealand') 🇳🇿
                            @elseif($c === 'Poland') 🇵🇱
                            @elseif($c === 'Serbia') 🇷🇸
                            @elseif($c === 'Montenegro') 🇲🇪
                            @elseif($c === 'Ireland') 🇮🇪
                            @elseif($c === 'Norway') 🇳🇴
                            @elseif($c === 'Denmark') 🇩🇰
                            @endif
                        </button>
                    @endforeach
                </div>

                <!-- Jobs Display Grid -->
                <div class="row g-4" id="jobsGridContainer">
                    @forelse($jobAbroad as $job)
                    <div class="col-lg-6 col-md-12 job-item-card" data-country="{{ $job->country }}">
                        <div class="service-card h-100">
                            <div class="service-card-border-top" style="background-color: {{ $job->color ?? '#E83E8C' }}"></div>
                            <div class="card-body p-4 p-md-5">
                                <div class="d-flex align-items-center justify-content-between mb-3">
                                    <span class="badge bg-light text-primary px-3 py-2 rounded-pill fw-bold"><i class="bi bi-geo-alt-fill me-1"></i>{{ $job->country }}</span>
                                    <i class="bi {{ $job->icon ?? 'bi-briefcase-fill' }} h3 text-muted mb-0"></i>
                                </div>
                                
                                <h3 class="h4 fw-bold text-dark mb-1">{{ $job->title }}</h3>
                                <p class="text-muted small mb-4">{{ $job->subtitle }}</p>

                                <div class="small text-muted mb-4">
                                    {!! $job->description !!}
                                </div>

                                @if($job->features)
                                <div class="mb-4">
                                    <strong class="text-dark small d-block mb-2">Key Relocation Benefits:</strong>
                                    <ul class="row g-1 list-unstyled ps-0 mb-0">
                                        @foreach($job->features as $feature)
                                        <li class="col-md-6 small text-muted"><i class="bi bi-check-circle-fill text-success me-2"></i>{{ $feature }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                                @endif

                                <div class="border-top pt-4">
                                    <div class="row align-items-center">
                                        <div class="col-md-7 mb-3 mb-md-0">
                                            <div class="d-flex flex-column gap-1">
                                                <div>
                                                    <span class="small text-muted me-2">Placement Fee:</span>
                                                    <strong class="small fw-bold text-primary">${{ number_format($job->price, 0) }} (GH₵ {{ number_format($job->price * 12, 0) }})</strong>
                                                </div>
                                                <div>
                                                    <span class="small text-muted me-2">Visa Processing:</span>
                                                    <strong class="small fw-bold text-dark">${{ number_format($job->processing_fee, 0) }} (GH₵ {{ number_format($job->processing_fee * 12, 0) }})</strong>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-5 text-md-end">
                                            <button class="btn btn-warning text-white fw-bold px-4 py-2 rounded-pill shadow" data-bs-toggle="modal" data-bs-target="#jobApplyModal-{{ $job->id }}">Apply Now</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- JOB PLACEMENT APPLICATION MODAL -->
                    <div class="modal fade" id="jobApplyModal-{{ $job->id }}" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content border-0 rounded-4 shadow-lg">
                                <div class="modal-header border-0 bg-light rounded-top-4 p-4">
                                    <div>
                                        <h5 class="modal-title fw-bold text-dark"><i class="bi bi-briefcase-fill me-2 text-warning"></i>Job Placement Intake</h5>
                                        <p class="text-muted small mb-0">{{ $job->title }} - {{ $job->country }}</p>
                                    </div>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body p-4">
                                    <div class="alert alert-warning bg-light border-0 d-flex flex-column gap-2 p-3 mb-4 rounded-3 small">
                                        <div class="d-flex justify-content-between">
                                            <span>Placement cost:</span>
                                            <strong>${{ number_format($job->price, 2) }} (GH₵ {{ number_format($job->price * 12, 2) }})</strong>
                                        </div>
                                        <div class="d-flex justify-content-between">
                                            <span>Visa Coordination:</span>
                                            <strong>${{ number_format($job->processing_fee, 2) }} (GH₵ {{ number_format($job->processing_fee * 12, 2) }})</strong>
                                        </div>
                                    </div>

                                    <form action="{{ route('services.job-apply') }}" method="POST" enctype="multipart/form-data">
                                        @csrf
                                        <input type="hidden" name="service_id" value="{{ $job->id }}">

                                        <div class="row g-2 mb-3">
                                            <div class="col-md-6 form-floating">
                                                <input type="text" name="first_name" class="form-control" id="jb-fn-{{ $job->id }}" placeholder="First Name" required>
                                                <label for="jb-fn-{{ $job->id }}">First Name</label>
                                            </div>
                                            <div class="col-md-6 form-floating">
                                                <input type="text" name="last_name" class="form-control" id="jb-ln-{{ $job->id }}" placeholder="Last Name" required>
                                                <label for="jb-ln-{{ $job->id }}">Last Name</label>
                                            </div>
                                        </div>

                                        <div class="form-floating mb-3">
                                            <input type="email" name="email" class="form-control" id="jb-em-{{ $job->id }}" placeholder="Email Address" required>
                                            <label for="jb-em-{{ $job->id }}">Email Address</label>
                                        </div>

                                        <div class="form-floating mb-3">
                                            <input type="tel" name="phone" class="form-control" id="jb-ph-{{ $job->id }}" placeholder="Phone Number" required>
                                            <label for="jb-ph-{{ $job->id }}">Phone Number</label>
                                        </div>

                                        <div class="row g-2 mb-3">
                                            <div class="col-md-6 form-floating">
                                                <input type="number" name="experience_years" class="form-control" id="jb-ex-{{ $job->id }}" placeholder="Experience Years" min="0" required>
                                                <label for="jb-ex-{{ $job->id }}">Work Experience (Years)</label>
                                            </div>
                                            <div class="col-md-6 form-floating">
                                                <input type="text" name="current_occupation" class="form-control" id="jb-oc-{{ $job->id }}" placeholder="Current Occupation" required>
                                                <label for="jb-oc-{{ $job->id }}">Current Occupation</label>
                                            </div>
                                        </div>

                                        <div class="form-floating mb-3">
                                            <input type="text" name="highest_education" class="form-control" id="jb-he-{{ $job->id }}" placeholder="Highest Education Level" required>
                                            <label for="jb-he-{{ $job->id }}">Highest Education Degree</label>
                                        </div>

                                        <div class="mb-4">
                                            <label class="form-label small fw-bold text-dark">Attach Updated CV/Resume (Required, PDF)</label>
                                            <input type="file" name="resume" class="form-control" required>
                                        </div>

                                        <button type="submit" class="btn btn-warning text-white w-100 py-3 rounded-pill fw-bold shadow">Submit Placement Application</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="col-12 text-center py-5">
                        <i class="bi bi-slash-circle text-muted display-4 d-block mb-3"></i>
                        <h5>No Job Slots Open Right Now</h5>
                    </div>
                    @endforelse
                </div>
            </div>

        </div>

        {{-- ============================================================ --}}
        {{-- PAYMENT METHODS — Shared Across All Service Tabs             --}}
        {{-- ============================================================ --}}
        <div class="row justify-content-center mt-5 pt-4" id="paymentInfoSection">
            <div class="col-lg-10">
                <div class="text-center mb-4">
                    <h3 class="fw-bold text-dark"><i class="bi bi-credit-card-2-front-fill text-success me-2"></i>How to Pay</h3>
                    <p class="text-muted small">Complete your enrollment by using one of the payment methods below.</p>
                </div>

                <ul class="nav nav-pills justify-content-center mb-4 gap-2" id="idxPaymentTabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active rounded-pill px-4 fw-semibold" id="idx-ussd-tab" data-bs-toggle="pill" data-bs-target="#idx-ussd-content" type="button" role="tab">
                            <i class="bi bi-phone me-1"></i> USSD / Mobile Money
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link rounded-pill px-4 fw-semibold" id="idx-bank-tab" data-bs-toggle="pill" data-bs-target="#idx-bank-content" type="button" role="tab">
                            <i class="bi bi-bank me-1"></i> Bank Transfer
                        </button>
                    </li>
                </ul>

                <div class="tab-content" id="idxPaymentTabsContent">
                    {{-- USSD Merchant Pay --}}
                    <div class="tab-pane fade show active" id="idx-ussd-content" role="tabpanel">
                        <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                            <div class="card-header bg-success text-white py-3 px-4">
                                <h6 class="mb-0 fw-bold"><i class="bi bi-phone-fill me-2"></i>Zenith Merchant Pay (USSD)</h6>
                            </div>
                            <div class="card-body p-4">
                                <div class="d-flex flex-column gap-3">
                                    @php
                                        $idxUssdSteps = [
                                            ['step' => 1, 'text' => 'Dial *966*3#', 'color' => '#FF6B35'],
                                            ['step' => 2, 'text' => 'Enter 1 to select Zenith Merchant Pay', 'color' => '#2E5BBA'],
                                            ['step' => 3, 'text' => 'Enter Merchant Code: 9604', 'color' => '#16A34A'],
                                            ['step' => 4, 'text' => 'Enter 1 to proceed and enter the amount', 'color' => '#7C3AED'],
                                            ['step' => 5, 'text' => 'Enter 1 to proceed and select payment method (Mobile Money or Account)', 'color' => '#0891B2'],
                                            ['step' => 6, 'text' => 'Enter 1 to proceed and wait for the prompt to enter your PIN', 'color' => '#F59E0B'],
                                            ['step' => 7, 'text' => 'Enter your PIN to complete the transaction', 'color' => '#E83E8C'],
                                            ['step' => 8, 'text' => 'Let us know once payment is done so we can confirm. Thank you!', 'color' => '#6F42C1'],
                                        ];
                                    @endphp
                                    @foreach($idxUssdSteps as $ussd)
                                    <div class="d-flex align-items-start gap-3">
                                        <div class="flex-shrink-0 d-flex align-items-center justify-content-center rounded-circle fw-bold text-white"
                                             style="width: 36px; height: 36px; min-width: 36px; background-color: {{ $ussd['color'] }}; font-size: 0.85rem;">
                                            {{ $ussd['step'] }}
                                        </div>
                                        <div class="pt-1 fw-semibold" style="font-size: 0.92rem;">{{ $ussd['text'] }}</div>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Bank Transfer --}}
                    <div class="tab-pane fade" id="idx-bank-content" role="tabpanel">
                        <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                            <div class="card-header py-3 px-4" style="background: linear-gradient(135deg, #0A1E42, #17366b);">
                                <h6 class="mb-0 fw-bold text-white"><i class="bi bi-bank2 me-2"></i>Zenith Bank — Direct Transfer</h6>
                            </div>
                            <div class="card-body p-4">
                                <table class="table table-borderless mb-4">
                                    <tbody>
                                        <tr>
                                            <td class="text-muted fw-semibold" style="width: 40%;">Account Name</td>
                                            <td class="fw-bold text-dark">Ree's Business</td>
                                        </tr>
                                        <tr>
                                            <td class="text-muted fw-semibold">Branch</td>
                                            <td class="fw-bold text-dark">Kojo Thompson Road</td>
                                        </tr>
                                        <tr>
                                            <td class="text-muted fw-semibold">Account Type</td>
                                            <td class="fw-bold text-dark">GHS</td>
                                        </tr>
                                        <tr>
                                            <td class="text-muted fw-semibold">Account Number</td>
                                            <td class="fw-bold text-primary fs-5">9060614089</td>
                                        </tr>
                                        <tr>
                                            <td class="text-muted fw-semibold">Sort Code</td>
                                            <td class="fw-bold text-dark">120104</td>
                                        </tr>
                                        <tr>
                                            <td class="text-muted fw-semibold">Swift Code</td>
                                            <td class="fw-bold text-dark">ZEBLGHAC</td>
                                        </tr>
                                    </tbody>
                                </table>
                                <div class="alert border-0 rounded-3 p-3 small mb-0" style="background: #f0f9ff;">
                                    <i class="bi bi-person-badge-fill text-primary me-2"></i>
                                    Your relationship with Zenith Bank will be coordinated by
                                    <strong>Selina Owusua Bukari</strong>, who can be reached via:
                                    <div class="mt-2 ps-4">
                                        <div><strong>Tel:</strong> 0302-681966 / 688683</div>
                                        <div><strong>Mobile:</strong> 0558772052</div>
                                        <div><strong>Email:</strong> <a href="mailto:selina.bukari@zenithbank.com.gh">selina.bukari@zenithbank.com.gh</a></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</section>

<!-- Premium JavaScript Dynamic Currency Switcher, Tab Controls & Form Pacing -->
<script>
    // Dynamic Tuition Fee adjustment in Registration Modal based on preferred class type select dropdown
    function updateModalPrice(selectEl, testId) {
        const selectedOption = selectEl.options[selectEl.selectedIndex];
        const ghs = parseFloat(selectedOption.getAttribute('data-ghs')) || 0;
        const usd = parseFloat(selectedOption.getAttribute('data-usd')) || 0;
        
        const feeEl = document.getElementById(`modal-tuition-fee-${testId}`);
        if (feeEl) {
            feeEl.innerText = `$${usd.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 })} (GH₵ ${ghs.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 })})`;
        }
        
        // Also update the dropdown-price element next to the select field
        const dropdownPriceEl = document.getElementById(`dropdown-price-${testId}`);
        if (dropdownPriceEl) {
            dropdownPriceEl.innerHTML = `$${usd.toLocaleString('en-US')} (GH₵ ${ghs.toLocaleString('en-US')})`;
        }
    }

    // 2. School Multi-step Form Pacing Controllers
    function nextFormStep(step) {
        // Simple Form Validation before moving forward
        let valid = true;
        if (step === 2) {
            const fn = document.getElementById('sc-fn');
            const ln = document.getElementById('sc-ln');
            const em = document.getElementById('sc-em');
            const ph = document.getElementById('sc-ph');
            if(!fn.value || !ln.value || !em.value || !ph.value) {
                alert('Please fill out all personal information fields.');
                valid = false;
            }
        } else if (step === 3) {
            const coi = document.getElementById('sc-coi');
            const checkboxes = document.querySelectorAll('input[name="target_countries[]"]:checked');
            if(!coi.value) {
                alert('Please fill out your target course of interest.');
                valid = false;
            } else if (checkboxes.length === 0) {
                alert('Please select at least one target country.');
                valid = false;
            }
        }

        if(!valid) return;

        // Hide all steps
        document.querySelectorAll('.form-step-section').forEach(sec => sec.classList.add('d-none'));
        // Show current step section
        document.getElementById(`step-section-${step}`).classList.remove('d-none');

        // Update indicator states
        document.querySelectorAll('.form-step-node').forEach((node, idx) => {
            const nodeNum = idx + 1;
            node.classList.remove('active', 'completed');
            if (nodeNum < step) {
                node.classList.add('completed');
            } else if (nodeNum === step) {
                node.classList.add('active');
            }
        });
    }

    function prevFormStep(step) {
        document.querySelectorAll('.form-step-section').forEach(sec => sec.classList.add('d-none'));
        document.getElementById(`step-section-${step}`).classList.remove('d-none');

        document.querySelectorAll('.form-step-node').forEach((node, idx) => {
            const nodeNum = idx + 1;
            node.classList.remove('active', 'completed');
            if (nodeNum < step) {
                node.classList.add('completed');
            } else if (nodeNum === step) {
                node.classList.add('active');
            }
        });
    }

    // 3. Country filter pills for Job Abroad Tab
    function filterCountry(countryName) {
        // Toggle Active State on Filters
        document.querySelectorAll('.country-filter-pill').forEach(btn => {
            btn.classList.remove('active');
            if (btn.innerText.includes(countryName) || (countryName === 'all' && btn.innerText.includes('All'))) {
                btn.classList.add('active');
            }
        });

        // Hide / Show cards in Grid Container
        const cards = document.querySelectorAll('.job-item-card');
        cards.forEach(card => {
            const cardCountry = card.getAttribute('data-country');
            if (countryName === 'all' || cardCountry === countryName) {
                card.style.display = 'block';
            } else {
                card.style.display = 'none';
            }
        });
    }

    // 4. Deep-link handler — reads ?tab and ?service from the URL on page load.
    //    Triggered by "Get Started" on services/show.blade.php, which appends
    //    ?tab=tests|school|jobs&service={id} so the portal auto-activates the
    //    correct tab and opens the matching registration modal (or scrolls to the
    //    school application form).
    document.addEventListener('DOMContentLoaded', function () {
        const params   = new URLSearchParams(window.location.search);
        const tabParam = params.get('tab');      // 'tests' | 'school' | 'jobs'
        const svcParam = params.get('service');  // service id as string

        if (!tabParam) return;

        // Map slug → Bootstrap tab button id
        const tabBtnMap = { tests: 'tests-tab', school: 'school-tab', jobs: 'jobs-tab' };
        const tabBtnId  = tabBtnMap[tabParam];
        if (!tabBtnId) return;

        // Activate the target tab
        const tabBtn = document.getElementById(tabBtnId);
        if (tabBtn) {
            const bsTab = new bootstrap.Tab(tabBtn);
            bsTab.show();
        }

        if (!svcParam) return;

        if (tabParam === 'school') {
            // School Applications: no per-service modal — scroll the form into view
            setTimeout(function () {
                const form = document.getElementById('schoolApplicationForm');
                if (form) {
                    form.scrollIntoView({ behavior: 'smooth', block: 'center' });
                }
            }, 450);
        } else {
            // Tests & Jobs: open the Bootstrap modal that matches the service id
            setTimeout(function () {
                const modalEl = document.getElementById('signupModal-' + svcParam);
                if (modalEl) {
                    new bootstrap.Modal(modalEl).show();
                }
            }, 450); // delay lets the tab fade-in animation finish first
        }
    });

</script>
@endsection
