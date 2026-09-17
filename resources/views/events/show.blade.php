@extends('layouts.app')

@section('content')
@php
    use App\Helpers\HeroImageHelper;
    $heroImage = HeroImageHelper::getHeroImageData('events');
    $imageUrl = $heroImage ? HeroImageHelper::getHeroImageUrl('events') : ($event->image ? asset('storage/' . $event->image) : 'https://images.unsplash.com/photo-1544531586-fde5298cdd40?w=1600');
    $overlayOpacity = $heroImage ? $heroImage->overlay_opacity : 0.8;
@endphp

<!-- Page Header -->
<div class="page-header" style="background: linear-gradient(rgba(10, 30, 66, {{ $overlayOpacity }}), rgba(10, 30, 66, {{ $overlayOpacity }})), url('{{ $imageUrl }}') center/cover; padding: 100px 0; color: white; text-align: center;">
    <div class="container">
        <h1 class="display-4 fw-bold mb-3">{{ $event->title }}</h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb justify-content-center mb-0">
                <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-white text-decoration-none">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('events.index') }}" class="text-white text-decoration-none">Events</a></li>
                <li class="breadcrumb-item active text-white-50" aria-current="page">{{ Str::limit($event->title, 20) }}</li>
            </ol>
        </nav>
    </div>
</div>

<section class="py-5">
    <div class="container py-5">
        <div class="row g-5">
            <div class="col-lg-8">
                <div class="mb-4">
                    @if($event->image)
                        <img src="{{ asset('storage/' . $event->image) }}" alt="{{ $event->title }}" class="img-fluid rounded-4 w-100 mb-4 shadow-sm">
                    @endif
                    
                    <h2 class="fw-bold mb-4 text-dark">About This Event</h2>
                    <div class="event-description text-muted lh-lg">
                        {!! nl2br(e($event->description)) !!}
                    </div>
                </div>
            </div>
            
            <div class="col-lg-4">
                <div class="card border-0 shadow-sm rounded-4 p-4 sticky-top" style="top: 100px;">
                    <h4 class="fw-bold mb-4">Event Details</h4>
                    
                    <div class="d-flex align-items-start mb-4">
                        <div class="flex-shrink-0 bg-light rounded-circle p-3 text-primary me-3">
                            <i class="bi bi-calendar-event fs-4"></i>
                        </div>
                        <div>
                            <h6 class="fw-bold mb-1">Date & Time</h6>
                            <p class="text-muted mb-0">{{ $event->start_time->format('F d, Y') }}</p>
                            <small class="text-muted">{{ $event->start_time->format('h:i A') }} - {{ $event->end_time->format('h:i A') }}</small>
                        </div>
                    </div>
                    
                    <div class="d-flex align-items-start mb-4">
                        <div class="flex-shrink-0 bg-light rounded-circle p-3 text-primary me-3">
                            <i class="bi bi-geo-alt fs-4"></i>
                        </div>
                        <div>
                            <h6 class="fw-bold mb-1">Location</h6>
                            <p class="text-muted mb-0">{{ $event->location }}</p>
                        </div>
                    </div>
                    
                    <div class="d-flex align-items-start mb-4">
                        <div class="flex-shrink-0 bg-light rounded-circle p-3 text-primary me-3">
                            <i class="bi bi-tag fs-4"></i>
                        </div>
                        <div>
                            <h6 class="fw-bold mb-1">Cost</h6>
                            <p class="text-muted mb-0">
                                @if($event->price > 0)
                                    ${{ number_format($event->price, 2) }}
                                @else
                                    Free Entry
                                @endif
                            </p>
                        </div>
                    </div>
                    
                    <hr class="my-4">
                    
                    <a href="#" class="btn btn-primary w-100 py-3 fw-bold rounded-pill">Register Now</a>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
