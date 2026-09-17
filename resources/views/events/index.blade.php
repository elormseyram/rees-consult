@extends('layouts.app')

@section('title', 'Events - IELTS Workshops, Seminars & Educational Events')

@section('description', 'Attend our IELTS workshops, seminars, and educational events. Learn from experts and connect with other students preparing for international education.')

@section('keywords', 'IELTS events, workshops, seminars, educational events, study abroad seminars')

@section('og_title', 'Rees Consult Events - Educational Workshops & Seminars')

@section('og_description', 'Join our expert-led workshops and seminars on IELTS preparation and international education.')

@section('content')
@php
    use App\Helpers\HeroImageHelper;
    $heroImage = HeroImageHelper::getHeroImageData('events');
    $imageUrl = $heroImage ? HeroImageHelper::getHeroImageUrl('events') : 'https://images.unsplash.com/photo-1544531586-fde5298cdd40?w=1600';
    $overlayOpacity = $heroImage ? $heroImage->overlay_opacity : 0.8;
@endphp

<!-- Page Header -->
<div class="page-header" style="background: linear-gradient(rgba(10, 30, 66, {{ $overlayOpacity }}), rgba(10, 30, 66, {{ $overlayOpacity }})), url('{{ $imageUrl }}') center/cover; padding: 100px 0; color: white; text-align: center;">
    <div class="container">
        <h1 class="display-4 fw-bold mb-3">Events</h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb justify-content-center mb-0">
                <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-white text-decoration-none">Home</a></li>
                <li class="breadcrumb-item active text-white-50" aria-current="page">Events</li>
            </ol>
        </nav>
    </div>
</div>

<!-- Events Grid -->
<section class="py-5 bg-light">
    <div class="container py-5">
        <div class="row g-4">
            @forelse($events as $event)
            <div class="col-lg-4 col-md-6">
                <div class="card h-100 border-0 shadow-sm rounded-4 overflow-hidden hover-lift transition-all">
                    <div class="position-relative">
                        @if($event->image)
                            <img src="{{ asset('storage/' . $event->image) }}" class="card-img-top" alt="{{ $event->title }}" style="height: 250px; object-fit: cover;">
                        @else
                            <img src="https://images.unsplash.com/photo-1544531586-fde5298cdd40?w=800&q=80" class="card-img-top" alt="{{ $event->title }}" style="height: 250px; object-fit: cover;">
                        @endif
                        
                        <!-- Time Badge -->
                        <div class="position-absolute top-0 start-0 m-3">
                            <span class="badge bg-primary rounded-pill px-3 py-2">
                                <i class="bi bi-clock me-1"></i> {{ $event->start_time->format('h:i a') }} - {{ $event->end_time->format('h:i a') }}
                            </span>
                        </div>
                        
                        <!-- Price Badge -->
                        <div class="position-absolute bottom-0 end-0 m-3 translate-middle-y" style="transform: translateY(50%);">
                            <span class="badge rounded-circle d-flex align-items-center justify-content-center shadow" 
                                  style="width: 60px; height: 60px; background-color: #007bff; font-size: 0.9rem;">
                                @if($event->price > 0)
                                    ${{ number_format($event->price, 0) }}
                                @else
                                    Free
                                @endif
                            </span>
                        </div>
                    </div>
                    
                    <div class="card-body p-4 pt-5">
                        <h3 class="h5 fw-bold mb-3">
                            <a href="{{ route('events.show', $event->slug) }}" class="text-dark text-decoration-none stretched-link">
                                {{ $event->title }}
                            </a>
                        </h3>
                        
                        <div class="d-flex align-items-center text-muted small mb-3">
                            <i class="bi bi-geo-alt me-2 text-primary"></i>
                            {{ $event->location }}
                        </div>
                    </div>
                </div>
            </div>
            @empty
            <div class="col-12 text-center py-5">
                <div class="display-1 text-muted mb-3"><i class="bi bi-calendar-x"></i></div>
                <h3>No Upcoming Events</h3>
                <p class="text-muted">Check back later for new events and workshops.</p>
            </div>
            @endforelse
        </div>
        
        <div class="mt-5 d-flex justify-content-center">
            {{ $events->links() }}
        </div>
    </div>
</section>
@endsection
