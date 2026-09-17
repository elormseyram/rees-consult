@extends('layouts.app')

@section('title', 'Blog — Work Abroad, Study Abroad & IELTS Tips | Rees Consult Ghana')

@section('description', 'Expert guides on work abroad from Ghana, study abroad applications, IELTS preparation, GRE & SAT tips, and international scholarship alerts. Practical advice for Ghanaians going global.')

@section('keywords', 'work abroad from Ghana blog, study abroad guides Ghana, IELTS tips Ghana, how to get job abroad Ghana, scholarship alerts Ghana students, GRE SAT TOEFL tips, education blog Accra')

@section('og_title', 'Rees Consult Blog — Work Abroad, Study Abroad & Test Prep Guides')

@section('og_description', 'Guides and tips on working abroad, studying abroad, and acing your IELTS, GRE, SAT and TOEFL from Ghana’s award-winning education consultancy.')

@section('content')
    @php
        use App\Helpers\HeroImageHelper;
        $heroImage = HeroImageHelper::getHeroImageData('blog');
        $imageUrl = $heroImage
            ? HeroImageHelper::getHeroImageUrl('blog')
            : 'https://images.unsplash.com/photo-1499750310159-5b5f226932b7?w=1600';
        $overlayOpacity = $heroImage ? $heroImage->overlay_opacity : 0.8;
    @endphp

    <!-- Page Header -->
    <div class="page-header"
        style="background: linear-gradient(rgba(10, 30, 66, {{ $overlayOpacity }}), rgba(10, 30, 66, {{ $overlayOpacity }})), url('{{ $imageUrl }}') center/cover; padding: 100px 0; color: white; text-align: center;">
        <div class="container">
            <h1 class="display-4 fw-bold mb-3">Our Blog</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb justify-content-center mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-white text-decoration-none">Home</a>
                    </li>
                    <li class="breadcrumb-item active text-white-50" aria-current="page">Blog</li>
                </ol>
            </nav>
        </div>
    </div>

    <section class="py-5">
        <div class="container py-5">
            <div class="row g-4">
                @forelse($posts as $post)
                    <div class="col-md-6 col-lg-4">
                        <div class="card h-100 border-0 shadow-sm hover-lift transition-all">
                            <div class="position-relative overflow-hidden" style="height: 240px;">
                                @if ($post->image)
                                    <img src="{{ $post->image_url }}" alt="{{ $post->title }}"
                                        class="w-100 h-100 object-fit-cover">
                                @else
                                    <img src="https://images.unsplash.com/photo-1432821596592-e2c18b78144f?w=800"
                                        alt="Blog Post" class="w-100 h-100 object-fit-cover">
                                @endif
                                <div
                                    class="position-absolute bottom-0 start-0 bg-white px-3 py-1 m-3 rounded-pill small fw-bold shadow-sm">
                                    {{ $post->published_at->format('M d, Y') }}
                                </div>
                            </div>
                            <div class="card-body p-4">
                                <h5 class="card-title fw-bold mb-3">
                                    <a href="{{ route('blog.show', $post->slug) }}"
                                        class="text-dark text-decoration-none stretched-link">
                                        {{ $post->title }}
                                    </a>
                                </h5>
                                <p class="card-text text-muted mb-0">
                                    {{ Str::limit(strip_tags($post->content), 120) }}
                                </p>
                            </div>
                            <div class="card-footer bg-white border-top-0 p-4 pt-0">
                                <div class="d-flex align-items-center text-primary fw-bold small">
                                    Read More <i class="bi bi-arrow-right ms-2"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12 text-center py-5">
                        <h3 class="text-muted">No posts found.</h3>
                        <p class="text-muted">Check back later for updates!</p>
                    </div>
                @endforelse
            </div>

            <div class="d-flex justify-content-center mt-5">
                {{ $posts->links() }}
            </div>
        </div>
    </section>
@endsection
