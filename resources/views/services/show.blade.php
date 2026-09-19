@extends('layouts.app')

@section('title', $service->title . ' in Ghana \u2014 Rees Consult')
@section('description', Str::limit(strip_tags($service->description), 155) ?: 'Expert ' . $service->title . ' coaching and consultancy in Accra, Ghana. Work abroad, study abroad and test preparation services from Rees Consult, British Council affiliate.')
@section('keywords', $service->title . ' Ghana, ' . $service->title . ' Accra, work abroad Ghana, study abroad Ghana, IELTS preparation Ghana, test prep Accra, international education consultancy Ghana')
@section('og_title', $service->title . ' \u2014 Rees Consult Ghana')
@section('og_description', Str::limit(strip_tags($service->description), 155) ?: 'Expert ' . $service->title . ' services from Rees Consult, Ghana\'s award-winning consultancy.')

@push('styles')
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "Course",
  "name": "{{ $service->title }}",
  "description": "{{ Str::limit(strip_tags($service->description), 300) }}",
  "provider": {
    "@type": "EducationalOrganization",
    "name": "Rees Consult",
    "url": "{{ url('/') }}",
    "address": {
      "@type": "PostalAddress",
      "streetAddress": "UQ79, Nii Kwaofio St",
      "addressLocality": "Accra",
      "addressCountry": "GH"
    }
  },
  "url": "{{ request()->url() }}"
}
</script>
@endpush

@section('content')
@php
    $heroImage = \App\Helpers\HeroImageHelper::getHeroImageData('services');
    $imageUrl = $heroImage ? \App\Helpers\HeroImageHelper::getHeroImageUrl('services') : 'https://images.unsplash.com/photo-1522202176988-66273c2fd55f?w=1600';
    $overlayOpacity = $heroImage ? $heroImage->overlay_opacity : 0.9;
@endphp

<!-- Page Header -->
<div class="page-header" style="background: linear-gradient(rgba(10, 30, 66, {{ $overlayOpacity }}), rgba(10, 30, 66, {{ $overlayOpacity }})), url('{{ $imageUrl }}') center/cover; padding: 100px 0; color: white;">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8 text-center">
                <div class="mb-4">
                    <i class="bi {{ $service->icon }} display-1"></i>
                </div>
                <h1 class="display-4 fw-bold mb-3">{{ $service->title }}</h1>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb justify-content-center mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-white text-decoration-none">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('services.index') }}" class="text-white text-decoration-none">Services</a></li>
                        <li class="breadcrumb-item active text-white-50" aria-current="page">{{ Str::limit($service->title, 20) }}</li>
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
                <div class="content lh-lg">
                    {!! $service->description !!}
                </div>

                {{-- ============================================================ --}}
                {{-- PRICING SECTION — rendered differently per service category   --}}
                {{-- ============================================================ --}}
                @php
                    $tabMap = [
                        'standardized_test' => 'tests',
                        'school_application' => 'school',
                        'job_abroad' => 'jobs',
                    ];
                    $tabSlug = $tabMap[$service->category] ?? 'tests';
                @endphp

                <div class="my-5">
                    <h4 class="fw-bold text-dark mb-4">
                        <i class="bi bi-tag-fill text-primary me-2"></i>Pricing &amp; Fees
                    </h4>

                    @if($service->category === 'standardized_test')
                        {{-- ---- STANDARDIZED TEST: 4-format price grid ---- --}}
                        @if($pricing)
                        @php
                            $formats = [
                                ['label' => 'Group — Online',      'icon' => 'bi-camera-video',    'ghs' => $pricing->group_online,          'usd' => round($pricing->group_online / 12)],
                                ['label' => 'Group — In-Person',   'icon' => 'bi-people-fill',     'ghs' => $pricing->group_in_person,       'usd' => round($pricing->group_in_person / 12)],
                                ['label' => '1-on-1 — Online',     'icon' => 'bi-person-video2',   'ghs' => $pricing->one_on_one_online,     'usd' => round($pricing->one_on_one_online / 12)],
                                ['label' => '1-on-1 — In-Person',  'icon' => 'bi-person-check-fill','ghs'=> $pricing->one_on_one_in_person,  'usd' => round($pricing->one_on_one_in_person / 12)],
                            ];
                            $minGhs = min(array_column($formats, 'ghs'));
                            $maxGhs = max(array_column($formats, 'ghs'));
                        @endphp

                        {{-- Range summary badge --}}
                        <div class="alert border-0 rounded-4 mb-4 py-3 px-4"
                             style="background: linear-gradient(135deg,#eef2ff 0%,#f0f9ff 100%);">
                            <div class="d-flex align-items-center gap-3">
                                <i class="bi bi-bar-chart-fill text-primary fs-3"></i>
                                <div>
                                    <div class="small text-muted fw-semibold text-uppercase letter-spacing-1">Tuition Range</div>
                                    <div class="fw-bold text-primary fs-5">
                                        GH₵ {{ number_format($minGhs) }} – GH₵ {{ number_format($maxGhs) }}
                                        <span class="text-muted fw-normal fs-6">
                                            (${{ number_format(round($minGhs/12)) }} – ${{ number_format(round($maxGhs/12)) }} USD)
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- 4-format grid --}}
                        <div class="row g-3 mb-4">
                            @foreach($formats as $fmt)
                            <div class="col-6 col-md-3">
                                <div class="card border-0 shadow-sm rounded-4 h-100 text-center p-3"
                                     style="background:#ffffff;">
                                    <i class="bi {{ $fmt['icon'] }} fs-3 text-primary mb-2"></i>
                                    <div class="small fw-bold text-dark mb-1">{{ $fmt['label'] }}</div>
                                    <div class="fw-bold text-primary">GH₵ {{ number_format($fmt['ghs']) }}</div>
                                    <div class="text-muted" style="font-size:0.78rem;">${{ number_format($fmt['usd']) }} USD</div>
                                </div>
                            </div>
                            @endforeach
                        </div>

                        {{-- Admin fee line --}}
                        <div class="d-flex align-items-center gap-2 p-3 rounded-3 border"
                             style="background:#fffbeb;">
                            <i class="bi bi-receipt text-warning fs-5"></i>
                            <span class="small text-muted">Admin / Registration Support Fee:</span>
                            <strong class="ms-auto text-dark">
                                GH₵ {{ number_format($service->processing_fee * 12, 0) }}
                                <span class="text-muted fw-normal">(~${{ number_format($service->processing_fee, 0) }} USD)</span>
                            </strong>
                        </div>
                        @else
                        <div class="alert alert-light border rounded-4">
                            <i class="bi bi-info-circle text-muted me-2"></i>
                            Contact us for current tuition rates for this programme.
                        </div>
                        @endif

                    @elseif($service->category === 'school_application')
                        {{-- ---- SCHOOL APPLICATION: assessment + visa fees ---- --}}
                        <div class="row g-3">
                            <div class="col-md-6">
                                <div class="card border-0 shadow-sm rounded-4 p-4 h-100"
                                     style="background:linear-gradient(135deg,#f0f9ff,#e8f4fd);">
                                    <div class="d-flex align-items-center gap-3 mb-3">
                                        <div class="rounded-circle d-flex align-items-center justify-content-center"
                                             style="width:48px;height:48px;background:#2E5BBA22;">
                                            <i class="bi bi-person-lines-fill text-primary fs-5"></i>
                                        </div>
                                        <div>
                                            <div class="small text-muted fw-semibold">Assessment &amp; Advisory Fee</div>
                                            <div class="fw-bold fs-5 text-primary">
                                                GH₵ {{ number_format($service->price * 12, 0) }}
                                            </div>
                                            <div class="text-muted small">${{ number_format($service->price, 0) }} USD</div>
                                        </div>
                                    </div>
                                    <p class="small text-muted mb-0">
                                        Covers pre-evaluation, school matching, personal statement reviews, and application submission support.
                                    </p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="card border-0 shadow-sm rounded-4 p-4 h-100"
                                     style="background:linear-gradient(135deg,#fffbeb,#fef9e7);">
                                    <div class="d-flex align-items-center gap-3 mb-3">
                                        <div class="rounded-circle d-flex align-items-center justify-content-center"
                                             style="width:48px;height:48px;background:#F8B50022;">
                                            <i class="bi bi-airplane-fill text-warning fs-5"></i>
                                        </div>
                                        <div>
                                            <div class="small text-muted fw-semibold">Visa Processing Support</div>
                                            <div class="fw-bold fs-5 text-warning">
                                                GH₵ {{ number_format($service->processing_fee * 12, 0) }}
                                            </div>
                                            <div class="text-muted small">${{ number_format($service->processing_fee, 0) }} USD</div>
                                        </div>
                                    </div>
                                    <p class="small text-muted mb-0">
                                        Covers student visa preparation, mock interview coaching, and documentation review.
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="alert alert-light border rounded-3 mt-3 small text-muted">
                            <i class="bi bi-info-circle-fill text-primary me-1"></i>
                            Fees are charged in two stages. A 40% commitment fee is required upon enrolment.
                        </div>

                    @elseif($service->category === 'job_abroad')
                        {{-- ---- JOB ABROAD: processing + commitment structure ---- --}}
                        @php
                            $fullGhs     = $service->price * 12;
                            $halfGhs     = $fullGhs / 2;
                            $commitGhs   = $halfGhs * 0.40;   // 40% of half-payment
                        @endphp
                        <div class="card border-0 shadow-sm rounded-4 p-4 mb-3"
                             style="background:linear-gradient(135deg,#f0fdf4,#dcfce7);">
                            <div class="d-flex align-items-center gap-3 mb-3">
                                <div class="rounded-circle d-flex align-items-center justify-content-center"
                                     style="width:52px;height:52px;background:#16a34a22;">
                                    <i class="bi bi-briefcase-fill text-success fs-4"></i>
                                </div>
                                <div>
                                    <div class="small text-muted fw-semibold">Total Processing Fee</div>
                                    <div class="fw-bold fs-4 text-success">
                                        GH₵ {{ number_format($fullGhs, 0) }}
                                    </div>
                                    <div class="text-muted small">${{ number_format($service->price, 0) }} USD</div>
                                </div>
                            </div>
                            <hr class="my-3">
                            <div class="row g-2 text-center">
                                <div class="col-6">
                                    <div class="bg-white rounded-3 p-3 shadow-sm">
                                        <div class="small text-muted mb-1">Half-Payment Option</div>
                                        <div class="fw-bold text-dark">GH₵ {{ number_format($halfGhs, 0) }}</div>
                                        <div class="text-muted" style="font-size:0.76rem;">~${{ number_format($service->price / 2, 0) }} USD</div>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="bg-white rounded-3 p-3 shadow-sm">
                                        <div class="small text-muted mb-1">40% Commitment Fee</div>
                                        <div class="fw-bold text-primary">GH₵ {{ number_format($commitGhs, 0) }}</div>
                                        <div class="text-muted" style="font-size:0.76rem;">Due at sign-up</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @if($service->processing_fee > 0)
                        <div class="d-flex align-items-center gap-2 p-3 rounded-3 border"
                             style="background:#fffbeb;">
                            <i class="bi bi-receipt text-warning fs-5"></i>
                            <span class="small text-muted">Admin Processing Fee:</span>
                            <strong class="ms-auto text-dark">
                                GH₵ {{ number_format($service->processing_fee * 12, 0) }}
                                <span class="text-muted fw-normal">(~${{ number_format($service->processing_fee, 0) }} USD)</span>
                            </strong>
                        </div>
                        @endif
                    @endif
                </div>

                {{-- ============================================================ --}}
                {{-- PAYMENT METHODS — Zenith USSD & Bank Transfer                --}}
                {{-- ============================================================ --}}
                <div class="my-5">
                    <h4 class="fw-bold text-dark mb-4">
                        <i class="bi bi-credit-card-2-front-fill text-success me-2"></i>How to Pay
                    </h4>

                    <ul class="nav nav-pills mb-3 gap-2" id="paymentMethodsTab" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active rounded-pill px-4 fw-semibold" id="ussd-tab" data-bs-toggle="pill" data-bs-target="#ussd-content" type="button" role="tab">
                                <i class="bi bi-phone me-1"></i> USSD / Mobile Money
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link rounded-pill px-4 fw-semibold" id="bank-tab" data-bs-toggle="pill" data-bs-target="#bank-content" type="button" role="tab">
                                <i class="bi bi-bank me-1"></i> Bank Transfer
                            </button>
                        </li>
                    </ul>

                    <div class="tab-content" id="paymentMethodsTabContent">
                        {{-- USSD Merchant Pay --}}
                        <div class="tab-pane fade show active" id="ussd-content" role="tabpanel">
                            <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                                <div class="card-header bg-success text-white py-3 px-4">
                                    <h6 class="mb-0 fw-bold"><i class="bi bi-phone-fill me-2"></i>Zenith Merchant Pay (USSD)</h6>
                                </div>
                                <div class="card-body p-4">
                                    <div class="d-flex flex-column gap-3">
                                        @php
                                            $ussdSteps = [
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
                                        @foreach($ussdSteps as $ussd)
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
                        <div class="tab-pane fade" id="bank-content" role="tabpanel">
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

                <!-- Related Services & Internal Links -->
                @if(isset($relatedServices) && $relatedServices->count() > 0)
                <div class="my-5 pt-4 border-top">
                    <h4 class="fw-bold text-dark mb-4">
                        <i class="bi bi-grid-fill text-primary me-2"></i>Explore Other Programs
                    </h4>
                    <div class="row g-4">
                        @foreach($relatedServices as $relServ)
                        <div class="col-md-4">
                            <div class="card h-100 border-0 shadow-sm p-3 rounded-3">
                                <div class="d-flex align-items-center mb-2">
                                    <i class="bi {{ $relServ->icon ?? 'bi-star' }} fs-4 text-primary me-2"></i>
                                    <h6 class="fw-bold text-dark mb-0">{{ Str::limit($relServ->title, 35) }}</h6>
                                </div>
                                <p class="small text-muted mb-3">{{ Str::limit(strip_tags($relServ->description), 80) }}</p>
                                <a href="{{ route('services.show', $relServ->slug) }}" class="small text-primary fw-bold text-decoration-none">
                                    Learn More <i class="bi bi-arrow-right ms-1"></i>
                                </a>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif

                @if(isset($relatedPosts) && $relatedPosts->count() > 0)
                <div class="mb-5">
                    <h4 class="fw-bold text-dark mb-4">
                        <i class="bi bi-journal-text text-warning me-2"></i>Recommended Work &amp; Study Guides
                    </h4>
                    <div class="row g-4">
                        @foreach($relatedPosts as $relPost)
                        <div class="col-md-4">
                            <div class="card h-100 border-0 shadow-sm rounded-3 overflow-hidden">
                                <div class="card-body p-3">
                                    <div class="text-muted small mb-1">{{ $relPost->published_at->format('M d, Y') }}</div>
                                    <h6 class="fw-bold text-dark mb-2">
                                        <a href="{{ route('blog.show', $relPost->slug) }}" class="text-dark text-decoration-none stretched-link">
                                            {{ Str::limit($relPost->title, 55) }}
                                        </a>
                                    </h6>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif

                <!-- Back Button -->
                <div class="border-top border-bottom py-4 my-5">
                    <div class="d-flex justify-content-between align-items-center">
                        <a href="{{ route('services.index') }}" class="btn btn-outline-primary rounded-pill px-4">
                            <i class="bi bi-arrow-left me-2"></i> Back to Services
                        </a>
                        <a href="{{ route('services.index') }}?tab={{ $tabSlug }}&service={{ $service->id }}"
                           class="btn btn-primary rounded-pill px-4">
                            Get Started <i class="bi bi-arrow-right ms-2"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
