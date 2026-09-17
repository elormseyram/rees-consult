@php
    use App\Helpers\HeroImageHelper;
    $heroImage = HeroImageHelper::getHeroImageData($pageSlug ?? 'home');
    $imageUrl = $heroImage ? HeroImageHelper::getHeroImageUrl($pageSlug ?? 'home') : $defaultImage;
    $overlayOpacity = $heroImage ? $heroImage->overlay_opacity : 0.8;
@endphp

<div class="page-header" style="background: linear-gradient(rgba(10, 30, 66, {{ $overlayOpacity }}), rgba(10, 30, 66, {{ $overlayOpacity }})), url('{{ $imageUrl }}') center/cover; padding: {{ $padding ?? '100px 0' }}; color: white; text-align: center;">
    <div class="container">
        {{ $slot }}
    </div>
</div>
