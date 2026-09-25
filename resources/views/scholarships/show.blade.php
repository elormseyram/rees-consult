@extends('layouts.app')

@section('title', $scholarship->name . ' Scholarship | Rees Consult Ghana')
@section('description', Str::limit(strip_tags($scholarship->description ?: 'Scholarship guidance and application support for students in Ghana.'), 155, '...'))
@section('og_title', $scholarship->name . ' Scholarship | Rees Consult Ghana')
@section('og_description', Str::limit(strip_tags($scholarship->description ?: 'Get scholarship guidance and application support from Rees Consult.'), 200, '...'))
@section('canonical', route('scholarships.show', $scholarship->id))

@push('styles')
<script type="application/ld+json">
{!! json_encode([
    '@context' => 'https://schema.org',
    '@type' => 'Scholarship',
    'name' => $scholarship->name,
    'description' => strip_tags($scholarship->description ?: 'Scholarship opportunity for international students.'),
    'url' => route('scholarships.show', $scholarship->id),
    'provider' => [
        '@type' => 'Organization',
        'name' => 'Rees Consult',
        'url' => url('/'),
    ],
    'areaServed' => [
        '@type' => 'Country',
        'name' => $scholarship->country,
    ],
], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT) !!}
</script>
@endpush

@section('content')
@php
    use App\Helpers\HeroImageHelper;
    $heroImage = HeroImageHelper::getHeroImageData('scholarships');
    $imageUrl = $heroImage ? HeroImageHelper::getHeroImageUrl('scholarships') : 'https://images.unsplash.com/photo-1523240795612-9a054b0db644?w=1600';
    $overlayOpacity = $heroImage ? $heroImage->overlay_opacity : 0.8;
@endphp

<!-- Page Header -->
<div class="page-header" style="background: linear-gradient(rgba(10, 30, 66, {{ $overlayOpacity }}), rgba(10, 30, 66, {{ $overlayOpacity }})), url('{{ $imageUrl }}') center/cover; padding: 80px 0; color: white; text-align: center;">
    <div class="container">
        <h1 class="display-4 fw-bold mb-3">{{ $scholarship->name }}</h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb justify-content-center mb-0">
                <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-white text-decoration-none">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('scholarships.index') }}" class="text-white text-decoration-none">Scholarships</a></li>
                <li class="breadcrumb-item active text-white-50" aria-current="page">{{ $scholarship->name }}</li>
            </ol>
        </nav>
    </div>
</div>

<!-- Scholarship Details -->
<section class="py-5">
    <div class="container py-5">
        <div class="row">
            <div class="col-lg-8">
                <!-- Basic Info Card -->
                <div class="card shadow-sm mb-4">
                    <div class="card-body">
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <h6 class="text-muted text-uppercase small mb-2">Country</h6>
                                    <h5 class="fw-bold">
                                        <i class="bi bi-geo-alt text-primary"></i> {{ $scholarship->country }}
                                    </h5>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <h6 class="text-muted text-uppercase small mb-2">Application Deadline</h6>
                                    <h5 class="fw-bold">
                                        <i class="bi bi-calendar-event text-primary"></i> {{ $scholarship->deadline ?? 'Not specified' }}
                                    </h5>
                                </div>
                            </div>
                        </div>

                        @if($scholarship->description)
                        <div class="mb-4">
                            <h6 class="text-muted text-uppercase small mb-2">About This Scholarship</h6>
                            <div class="text-muted lh-lg">{!! $scholarship->description !!}</div>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Requirements Card -->
                @if($scholarship->requirements && count($scholarship->requirements) > 0)
                <div class="card shadow-sm mb-4">
                    <div class="card-body">
                        <h5 class="card-title fw-bold mb-4">
                            <i class="bi bi-checklist text-primary"></i> Specific Requirements
                        </h5>
                        <ul class="list-group list-group-flush">
                            @foreach($scholarship->requirements as $requirement)
                            <li class="list-group-item d-flex align-items-start">
                                <i class="bi bi-check-circle-fill text-success me-3 mt-1"></i>
                                <span>{{ $requirement }}</span>
                            </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
                @endif

                <!-- General Requirements Card -->
                <div class="card shadow-sm mb-4">
                    <div class="card-body">
                        <h5 class="card-title fw-bold mb-4">
                            <i class="bi bi-file-earmark-check text-primary"></i> General Application Requirements
                        </h5>
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
                    </div>
                </div>

                <!-- Important Note -->
                <div class="alert alert-warning" role="alert">
                    <i class="bi bi-exclamation-triangle"></i>
                    <strong>Important:</strong> Some scholarships require you to have admission in a school in that specific country before applying. You would apply with the admission letter.
                </div>
            </div>

            <!-- Sidebar -->
            <div class="col-lg-4">
                <!-- Quick Info Card -->
                <div class="card shadow-sm mb-4 sticky-top" style="top: 20px;">
                    <div class="card-body">
                        <h5 class="card-title fw-bold mb-4">Quick Info</h5>
                        
                        <div class="mb-4">
                            <h6 class="text-muted small text-uppercase mb-2">Scholarship Name</h6>
                            <p class="fw-bold">{{ $scholarship->name }}</p>
                        </div>

                        <div class="mb-4">
                            <h6 class="text-muted small text-uppercase mb-2">Location</h6>
                            <p class="fw-bold">{{ $scholarship->country }}</p>
                        </div>

                        <div class="mb-4">
                            <h6 class="text-muted small text-uppercase mb-2">Deadline</h6>
                            <p class="fw-bold">{{ $scholarship->deadline ?? 'Not specified' }}</p>
                        </div>

                        <hr>

                        <a href="{{ route('services.index') }}" class="btn btn-primary w-100 mb-2">
                            <i class="bi bi-envelope"></i> Get Help
                        </a>
                        <a href="{{ route('scholarships.index') }}" class="btn btn-outline-secondary w-100">
                            <i class="bi bi-arrow-left"></i> Back to Scholarships
                        </a>
                    </div>
                </div>

                <!-- Related Scholarships -->
                @php
                    $relatedScholarships = \App\Models\Scholarship::active()
                        ->where('country', $scholarship->country)
                        ->where('id', '!=', $scholarship->id)
                        ->limit(3)
                        ->get();
                @endphp

                @if($relatedScholarships->count() > 0)
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title fw-bold mb-4">Other Scholarships in {{ $scholarship->country }}</h5>
                        <div class="list-group list-group-flush">
                            @foreach($relatedScholarships as $related)
                            <a href="{{ route('scholarships.show', $related->id) }}" class="list-group-item list-group-item-action">
                                <h6 class="mb-1 fw-bold">{{ $related->name }}</h6>
                                <small class="text-muted">Deadline: {{ $related->deadline ?? 'Not specified' }}</small>
                            </a>
                            @endforeach
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="py-5" style="background: linear-gradient(135deg, #07294D 0%, #F8A706 100%);">
    <div class="container py-5">
        <div class="row align-items-center">
            <div class="col-lg-8">
                <h2 class="display-5 fw-bold text-white mb-3">Ready to Apply?</h2>
                <p class="lead text-white-50 mb-0">Our expert consultants can help you prepare your application and guide you through every step of the process.</p>
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
