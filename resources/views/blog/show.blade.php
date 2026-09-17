@extends('layouts.app')

@section('title', $post->title . ' — Rees Consult Blog')
@section('description', Str::limit(strip_tags($post->content), 155))
@section('keywords', 'IELTS preparation Ghana, work abroad Ghana, study abroad Ghana, ' . Str::limit($post->title, 60) . ', education blog Ghana, Accra consultancy')
@section('og_title', $post->title . ' — Rees Consult')
@section('og_description', Str::limit(strip_tags($post->content), 155))
@if($post->image)
@section('og_image', $post->image_url)
@section('twitter_image', $post->image_url)
@endif

@push('styles')
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "Article",
  "headline": "{{ addslashes($post->title) }}",
  "description": "{{ addslashes(Str::limit(strip_tags($post->content), 155)) }}",
  "datePublished": "{{ $post->published_at->toIso8601String() }}",
  "dateModified": "{{ $post->updated_at->toIso8601String() }}",
  "author": {
    "@type": "Organization",
    "name": "Rees Consult"
  },
  "publisher": {
    "@type": "Organization",
    "name": "Rees Consult",
    "logo": {
      "@type": "ImageObject",
      "url": "{{ asset('reesconsult-logo.png') }}"
    }
  },
  "url": "{{ request()->url() }}"
}
</script>
@endpush

@section('content')
    @php
        use App\Helpers\HeroImageHelper;
        $heroImage = HeroImageHelper::getHeroImageData('blog');

        // Logic: 1. Post Image, 2. Blog Hero Image, 3. Default Unsplash
        $imageUrl = $post->image_url
            ? $post->image_url
            : ($heroImage
                ? HeroImageHelper::getHeroImageUrl('blog')
                : 'https://images.unsplash.com/photo-1499750310159-5b5f226932b7?w=1600');
        $overlayOpacity = $heroImage ? $heroImage->overlay_opacity : 0.9;
    @endphp

    <!-- Page Header -->
    <div class="page-header"
        style="background: linear-gradient(rgba(10, 30, 66, {{ $overlayOpacity }}), rgba(10, 30, 66, {{ $overlayOpacity }})), url('{{ $imageUrl }}') center/cover; padding: 100px 0; color: white;">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8 text-center">
                    <div class="mb-3">
                        <span
                            class="badge bg-warning text-dark px-3 py-2 rounded-pill">{{ $post->published_at->format('F d, Y') }}</span>
                    </div>
                    <h1 class="display-4 fw-bold mb-3">{{ $post->title }}</h1>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb justify-content-center mb-0">
                            <li class="breadcrumb-item"><a href="{{ route('home') }}"
                                    class="text-white text-decoration-none">Home</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('blog.index') }}"
                                    class="text-white text-decoration-none">Blog</a></li>
                            <li class="breadcrumb-item active text-white-50" aria-current="page">
                                {{ Str::limit($post->title, 20) }}</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </div>

    <section class="py-5">
        <div class="container py-5">
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <!-- Post Content -->
                    <article class="blog-post">
                        @if ($post->image)
                            <div class="mb-5 rounded-4 overflow-hidden shadow-sm">
                                <img src="{{ $post->image_url }}" alt="{{ $post->title }}" class="w-100">
                            </div>
                        @endif

                        <div class="content lh-lg">
                            {!! $post->content !!}
                        </div>
                    </article>

                    <!-- Related Posts Section -->
                    @if(isset($relatedPosts) && $relatedPosts->count() > 0)
                        <div class="mt-5 pt-4">
                            <h3 class="fw-bold mb-4" style="color: #07294D;">Related Articles & Guides</h3>
                            <div class="row g-4">
                                @foreach($relatedPosts as $related)
                                    <div class="col-md-4">
                                        <div class="card h-100 border-0 shadow-sm rounded-3 overflow-hidden">
                                            <div class="position-relative" style="height: 140px;">
                                                <img src="{{ $related->image_url ?: 'https://images.unsplash.com/photo-1432821596592-e2c18b78144f?w=600' }}" 
                                                     alt="{{ $related->title }}" 
                                                     class="w-100 h-100 object-fit-cover">
                                            </div>
                                            <div class="card-body p-3">
                                                <div class="text-muted small mb-1">{{ $related->published_at->format('M d, Y') }}</div>
                                                <h6 class="card-title fw-bold mb-2">
                                                    <a href="{{ route('blog.show', $related->slug) }}" class="text-dark text-decoration-none stretched-link">
                                                        {{ Str::limit($related->title, 50) }}
                                                    </a>
                                                </h6>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </section>
@endsection
