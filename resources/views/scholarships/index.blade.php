@extends('layouts.app')

@section('title', 'Scholarships for Ghanaian Students — Study Abroad & Work Abroad Funding | Rees Consult')

@section('description', 'Discover available fully funded scholarships and grants for Ghanaian students looking to study abroad or work abroad. Rees Consult guides you through every application step in Accra and beyond.')

@section('keywords', 'scholarships Ghanaian students, fully funded scholarships Ghana, study abroad scholarships, work abroad funding Ghana, international scholarships Africa, scholarship applications Ghana, school abroad funding Accra')

@section('og_title', 'Scholarships for Ghanaian Students — Study & Work Abroad Funding')

@section('og_description', 'Find fully funded scholarship opportunities for Ghanaians and get expert help with your applications from Rees Consult, Accra.')

@section('content')
@php
    use App\Helpers\HeroImageHelper;
    $heroImage = HeroImageHelper::getHeroImageData('scholarships');
    $imageUrl = $heroImage ? HeroImageHelper::getHeroImageUrl('scholarships') : 'https://images.unsplash.com/photo-1523240795612-9a054b0db644?w=1600';
    $overlayOpacity = $heroImage ? $heroImage->overlay_opacity : 0.8;
@endphp

<!-- Page Header -->
<div class="page-header" style="background: linear-gradient(rgba(10, 30, 66, {{ $overlayOpacity }}), rgba(10, 30, 66, {{ $overlayOpacity }})), url('{{ $imageUrl }}') center/cover; padding: 100px 0; color: white; text-align: center;">
    <div class="container">
        <h1 class="display-4 fw-bold mb-3">Available Scholarships</h1>
        <p class="lead mb-0">Explore scholarship opportunities for studying abroad</p>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb justify-content-center mb-0 mt-3">
                <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-white text-decoration-none">Home</a></li>
                <li class="breadcrumb-item active text-white-50" aria-current="page">Scholarships</li>
            </ol>
        </nav>
    </div>
</div>

<!-- Scholarships Section -->
<section class="py-5">
    <div class="container py-5">
        <!-- Filters -->
        <div class="row mb-5">
            <div class="col-lg-12">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <form method="GET" action="{{ route('scholarships.index') }}" class="row g-3">
                            <div class="col-md-4">
                                <input type="text" name="search" class="form-control" placeholder="Search scholarships..." value="{{ request('search') }}">
                            </div>
                            <div class="col-md-4">
                                <select name="country" class="form-select">
                                    <option value="">All Countries</option>
                                    @foreach($countries as $country)
                                    <option value="{{ $country }}" {{ request('country') == $country ? 'selected' : '' }}>{{ $country }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4">
                                <button type="submit" class="btn btn-primary w-100">
                                    <i class="bi bi-search"></i> Search
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Scholarships Grid -->
        <div class="row g-4">
            @forelse($scholarships as $scholarship)
            <div class="col-md-6 col-lg-4">
                <div class="card h-100 shadow-sm hover-lift transition-all">
                    <div class="card-body d-flex flex-column">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <div>
                                <h5 class="card-title fw-bold mb-1">{{ $scholarship->name }}</h5>
                                <p class="text-muted small mb-0">
                                    <i class="bi bi-geo-alt"></i> {{ $scholarship->country }}
                                </p>
                            </div>
                        </div>

                        <p class="card-text text-muted flex-grow-1">
                            {{ Str::limit($scholarship->description, 100) }}
                        </p>

                        <div class="mb-3">
                            <div class="d-flex align-items-center mb-2">
                                <i class="bi bi-calendar-event text-primary me-2"></i>
                                <span class="small">
                                    <strong>Deadline:</strong> {{ $scholarship->deadline ?? 'Not specified' }}
                                </span>
                            </div>
                        </div>

                        <a href="{{ route('scholarships.show', $scholarship->id) }}" class="btn btn-primary btn-sm">
                            View Details <i class="bi bi-arrow-right"></i>
                        </a>
                    </div>
                </div>
            </div>
            @empty
            <div class="col-12">
                <div class="alert alert-info text-center">
                    <i class="bi bi-info-circle"></i> No scholarships found matching your criteria.
                </div>
            </div>
            @endforelse
        </div>

        <!-- Pagination -->
        <div class="d-flex justify-content-center mt-5">
            {{ $scholarships->links() }}
        </div>
    </div>
</section>

<!-- Basic Requirements Section -->
<section class="py-5 bg-light">
    <div class="container py-5">
        <div class="row">
            <div class="col-lg-8 mx-auto">
                <h2 class="display-5 fw-bold mb-4 text-center" style="color: #07294D;">Basic Requirements for Scholarship Application</h2>
                
                <div class="card shadow-sm">
                    <div class="card-body">
                        <ol class="list-group list-group-numbered">
                            <li class="list-group-item">Transcript from the school</li>
                            <li class="list-group-item">Certificates of completion</li>
                            <li class="list-group-item">Undergraduate certification if applying for a postgraduate programme</li>
                            <li class="list-group-item">Passport</li>
                            <li class="list-group-item">English proficiency test scores</li>
                            <li class="list-group-item">Passport picture (in some cases)</li>
                            <li class="list-group-item">Recommendation letter (in some cases)</li>
                            <li class="list-group-item">Scholarship essay/cover letter (in some cases)</li>
                        </ol>

                        <div class="alert alert-warning mt-4 mb-0">
                            <i class="bi bi-exclamation-triangle"></i>
                            <strong>Important Note:</strong> Some scholarships require you to have admission in a school in that specific country before applying. You would apply with the admission letter.
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="py-5" style="background: linear-gradient(135deg, #07294D 0%, #F8A706 100%);">
    <div class="container py-5">
        <div class="row align-items-center">
            <div class="col-lg-8">
                <h2 class="display-5 fw-bold text-white mb-3">Need Help with Your Application?</h2>
                <p class="lead text-white-50 mb-0">Our expert team can guide you through the entire scholarship application process and help you prepare all required documents.</p>
            </div>
            <div class="col-lg-4 text-lg-end mt-4 mt-lg-0">
                <a href="{{ route('services.index') }}" class="btn btn-warning btn-lg">
                    <i class="bi bi-send"></i> Apply Now
                </a>
            </div>
        </div>
    </div>
</section>
@endsection
