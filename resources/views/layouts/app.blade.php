<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Google tag (gtag.js) -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-1VBLWMXVND"></script>
    <script>
      window.dataLayer = window.dataLayer || [];
      function gtag(){dataLayer.push(arguments);}
      gtag('js', new Date());
      gtag('config', 'G-1VBLWMXVND');
    </script>

    @if(config('services.meta.pixel_id'))
    <!-- Meta Pixel Code -->
    <script>
    !function(f,b,e,v,n,t,s)
    {if(f.fbq)return;n=f.fbq=function(){n.callMethod?
    n.callMethod.apply(n,arguments):n.queue.push(arguments)};
    if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';
    n.queue=[];t=b.createElement(e);t.async=!0;
    t.src=v;s=b.getElementsByTagName(e)[0];
    s.parentNode.insertBefore(t,s)}(window, document,'script',
    'https://connect.facebook.net/en_US/fbevents.js');
    fbq('init', '{{ config('services.meta.pixel_id') }}');
    fbq('track', 'PageView');
    
    window.triggerMetaPixelEvent = function(eventName, eventId, eventData) {
        if (typeof fbq === 'function') {
            fbq('track', eventName, eventData || {}, { eventID: eventId });
        }
    };

    window.triggerMetaPixelEvents = function(events) {
        if (events && Array.isArray(events)) {
            events.forEach(function(event) {
                window.triggerMetaPixelEvent(event.name, event.id, event.data);
            });
        }
    };
    </script>
    <noscript>
    <img height="1" width="1" style="display:none"
    src="https://www.facebook.com/tr?id={{ config('services.meta.pixel_id') }}&ev=PageView&noscript=1"
    />
    </noscript>
    <!-- End Meta Pixel Code -->

    @if(session()->has('fb_events'))
    <!-- Meta Pixel Session Events -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            @foreach(session()->pull('fb_events', []) as $event)
                fbq('track', '{{ $event['name'] }}', {!! json_encode($event['data'] ?? new \stdClass()) !!}, { eventID: '{{ $event['id'] }}' });
            @endforeach
        });
    </script>
    @endif
    @endif

    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Rees Consult - Standardised Tests ( GRE, IELTS, SAT, etc) Preparation And Job&Study Abroad')</title>

    <!-- SEO Meta Tags -->
    <meta name="description" content="@yield('description', 'Rees Consult provides expert IELTS tutoring, test preparation (GRE, SAT, TOEFL), and study abroad consultancy for African students. Professional guidance for international education and career opportunities.')">
    <meta name="keywords" content="@yield('keywords', 'IELTS preparatory, test preparation, study abroad, education consultancy, GRE, SAT, TOEFL, international education, scholarship applications')">
    <meta name="author" content="Rees Consult">
    <meta name="robots" content="index, follow">
    <meta name="language" content="English">

    <!-- Open Graph Tags -->
    <meta property="og:type" content="website">
    <meta property="og:title" content="@yield('og_title', 'Rees Consult - IELTS & Study Abroad Consultancy')">
    <meta property="og:description" content="@yield('og_description', 'Expert guidance for IELTS, test preparation, and international education opportunities.')">
    <meta property="og:image" content="@yield('og_image', asset('reesconsult-logo.png'))">
    <meta property="og:url" content="{{ request()->url() }}">
    <meta property="og:site_name" content="Rees Consult">

    <!-- Twitter Card Tags -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="@yield('twitter_title', 'Rees Consult - IELTS & Study Abroad Consultancy')">
    <meta name="twitter:description" content="@yield('twitter_description', 'Expert guidance for IELTS, test preparation, and international education opportunities.')">
    <meta name="twitter:image" content="@yield('twitter_image', asset('reesconsult-logo.png'))">

    <!-- Canonical URL -->
    <link rel="canonical" href="{{ request()->url() }}">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet">

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">

    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.2/font/bootstrap-icons.css">

    <!-- Custom CSS (versioned by file mtime to bust browser/CDN cache on every change) -->
    @php
        $styleVer = @filemtime(public_path('css/style.css')) ?: '1';
        $sectionsVer = @filemtime(public_path('css/sections.css')) ?: '1';
    @endphp
    <link href="{{ asset('css/style.css') }}?v={{ $styleVer }}" rel="stylesheet">
    <link href="{{ asset('css/sections.css') }}?v={{ $sectionsVer }}" rel="stylesheet">

    <!-- Schema.org Structured Data -->
    <script type="application/ld+json">
    {
        "@@context": "https://schema.org",
        "@@type": ["EducationalOrganization", "LocalBusiness"],
        "name": "Rees Consult",
        "alternateName": "Ree's Consult",
        "url": "{{ url('/') }}",
        "logo": "{{ asset('reesconsult-logo.png') }}",
        "image": "{{ asset('reesconsult-logo.png') }}",
        "description": "Ghana's award-winning consultancy for work abroad job placements, study abroad school admissions, and IELTS, GRE, SAT, TOEFL, OET & NCLEX test preparation. British Council affiliate based in Accra.",
        "address": {
            "@@type": "PostalAddress",
            "streetAddress": "UQ79, Nii Kwaofio St",
            "addressLocality": "Accra",
            "addressRegion": "Greater Accra",
            "addressCountry": "GH"
        },
        "geo": {
            "@@type": "GeoCoordinates",
            "latitude": "5.6037",
            "longitude": "-0.1870"
        },
        "telephone": "+233256212634",
        "email": "info@reesconsult.com",
        "openingHours": "Mo-Fr 08:00-17:00, Sa 09:00-13:00",
        "priceRange": "GH₵₵",
        "hasMap": "https://maps.google.com/?q=Rees+Consult+Accra+Ghana",
        "sameAs": [
            "https://www.facebook.com/reeseconsult/",
            "https://x.com/ReesConsult",
            "https://www.instagram.com/reesconsult1/",
            "https://gh.linkedin.com/company/rees-consult",
            "https://www.tiktok.com/tag/REESConsult",
            "http://www.youtube.com/@ReesConsult"
        ],
        "contactPoint": {
            "@@type": "ContactPoint",
            "contactType": "Customer Service",
            "telephone": "+233256212634",
            "email": "info@reesconsult.com",
            "availableLanguage": "English"
        },
        "areaServed": ["Ghana", "Nigeria", "Kenya", "South Africa", "Cameroon", "Africa"],
        "knowsAbout": [
            "Work Abroad Job Placements",
            "Study Abroad School Admissions",
            "IELTS Preparation",
            "GRE Preparation",
            "SAT Preparation",
            "TOEFL Preparation",
            "OET Preparation for Healthcare Workers",
            "NCLEX Review",
            "G-MAT Preparation",
            "TESOL Certification",
            "Scholarship Assistance",
            "Visa Guidance"
        ],
        "award": "Global Trade & Business Award"
    }
    </script>

    <!-- Organization Schema -->
    <script type="application/ld+json">
    {
        "@@context": "https://schema.org",
        "@@type": "Organization",
        "name": "Rees Consult",
        "alternateName": "Ree's Consult",
        "url": "{{ url('/') }}",
        "logo": "{{ asset('reesconsult-logo.png') }}",
        "description": "Ghana's award-winning work abroad, study abroad and test preparation consultancy. British Council affiliate.",
        "telephone": "+233256212634",
        "email": "info@reesconsult.com",
        "foundingDate": "2023",
        "knowsLanguage": "en",
        "address": {
            "@@type": "PostalAddress",
            "streetAddress": "UQ79, Nii Kwaofio St",
            "addressLocality": "Accra",
            "addressRegion": "Greater Accra",
            "addressCountry": "GH"
        },
        "sameAs": [
            "https://www.facebook.com/reeseconsult/",
            "https://www.instagram.com/reesconsult1/",
            "https://gh.linkedin.com/company/rees-consult",
            "http://www.youtube.com/@ReesConsult"
        ]
    }
    </script>

    {{-- Page-specific styles injected here via @push('styles') --}}
    @stack('styles')
</head>

<body>
    <!-- Top Bar -->
    <div class="top-bar">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <span class="me-4">
                        <i class="bi bi-envelope me-2"></i>
                        <a href="mailto:info@reesconsult.com">Email: info@reesconsult.com</a>
                    </span>
                    <span>
                        <i class="bi bi-telephone me-2"></i>
                        <a href="tel:0256212634">Call: 0256212634</a>
                    </span>
                </div>
                <div class="col-md-6 text-end">
                    <span class="me-3">Follow Us:</span>
                    <a href="https://www.facebook.com/reeseconsult/" target="_blank" class="me-2"><i
                            class="bi bi-facebook"></i></a>
                    <a href="https://x.com/ReesConsult" target="_blank" class="me-2"><i
                            class="bi bi-twitter-x"></i></a>
                    <a href="https://www.instagram.com/reesconsult1/" target="_blank" class="me-2"><i
                            class="bi bi-instagram"></i></a>
                    <a href="https://gh.linkedin.com/company/rees-consult" target="_blank" class="me-2"><i
                            class="bi bi-linkedin"></i></a>
                    <a href="https://www.tiktok.com/tag/REESConsult" target="_blank" class="me-2"><i
                            class="bi bi-tiktok"></i></a>
                    <a href="http://www.youtube.com/@ReesConsult" target="_blank" class="me-2"><i
                            class="bi bi-youtube"></i></a>
                    <span class="ms-3">|</span>
                    <a href="https://affiliates-britishcouncil.org/?a=204&c=407&p=r&s1=" class="ms-3">BOOK IELTS EXAMS</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Navigation -->
    <nav class="navbar navbar-expand-lg main-nav sticky-top">
        <div class="container">
            <a href="{{ route('home') }}" class="navbar-brand">
                <img src="{{ asset('reesconsult-logo.png') }}" alt="Rees Consult">
            </a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContent"
                aria-controls="navbarContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarContent">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a href="{{ route('home') }}"
                            class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}">Home</a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('about') }}"
                            class="nav-link {{ request()->routeIs('about') ? 'active' : '' }}">About Us</a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('services.index') }}"
                            class="nav-link {{ request()->routeIs('services.*') ? 'active' : '' }}">Services</a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('scholarships.index') }}"
                            class="nav-link {{ request()->routeIs('scholarships.*') ? 'active' : '' }}">Scholarships</a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('testimonials.index') }}"
                            class="nav-link {{ request()->routeIs('testimonials.*') ? 'active' : '' }}">Testimonials</a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('awards.index') }}"
                            class="nav-link {{ request()->routeIs('awards.*') ? 'active' : '' }}">Awards</a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('contact.index') }}"
                            class="nav-link {{ request()->routeIs('contact.index') ? 'active' : '' }}">Contact</a>
                    </li>
                    <li class="nav-item dropdown">
                        <a href="#"
                            class="nav-link dropdown-toggle {{ request()->routeIs('events.*', 'blog.*') ? 'active' : '' }}"
                            id="moreDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            More
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="moreDropdown">
                            <li><a class="dropdown-item" href="{{ route('events.index') }}">Events</a></li>
                            <li><a class="dropdown-item" href="{{ route('blog.index') }}">Blog</a></li>
                        </ul>
                    </li>
                </ul>
                <div class="d-flex align-items-center">
                    <button type="button" class="btn-apply-nav ms-lg-3" data-bs-toggle="modal" data-bs-target="#applyModal">
                        Apply Now
                    </button>
                    <button class="btn btn-link ms-2">
                        <i class="bi bi-search"></i>
                    </button>
                </div>
            </div>
        </div>
    </nav>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show m-0 rounded-0 text-center" role="alert">
            <i class="bi bi-check-circle me-2"></i>
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show m-0 rounded-0 text-center" role="alert">
            <i class="bi bi-exclamation-circle me-2"></i>
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @yield('content')

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="row gy-4">
                <div class="col-lg-4 col-md-6">
                    <img src="{{ asset('reesconsult-logo.png') }}" alt="Rees Consult" class="footer-logo mb-3"
                        style="filter: brightness(0) invert(1);">
                    <p class="footer-description">
                        Ree's Consult aims to empower individuals to achieve their global aspirations through education
                        and career opportunities.
                    </p>
                </div>

                <div class="col-lg-2 col-md-6">
                    <h5 class="footer-title fw-bold mb-3">Company</h5>
                    <ul class="list-unstyled footer-links">
                        <li><a href="{{ route('home') }}" class="text-white-50 text-decoration-none">Home</a></li>
                        <li><a href="{{ route('about') }}" class="text-white-50 text-decoration-none">About Us</a>
                        </li>
                        <li><a href="{{ route('services.index') }}"
                                class="text-white-50 text-decoration-none">Services</a></li>
                        <li><a href="{{ route('scholarships.index') }}"
                                class="text-white-50 text-decoration-none">Scholarships</a></li>
                        <li><a href="{{ route('blog.index') }}" class="text-white-50 text-decoration-none">Blog</a>
                        </li>
                        <li><a href="{{ route('events.index') }}"
                                class="text-white-50 text-decoration-none">Events</a></li>
                        <li><a href="{{ route('testimonials.index') }}"
                                class="text-white-50 text-decoration-none">Testimonials</a></li>
                        <li><a href="{{ route('contact.index') }}" class="text-white-50 text-decoration-none">Contact
                                Us</a></li>
                    </ul>
                </div>

                <div class="col-lg-3 col-md-6">
                    <h5 class="footer-title">Newsletter</h5>
                    <p class="text-white-50 small mb-3">Subscribe to our newsletter to receive the latest updates and
                        offers.</p>
                    <form action="{{ route('newsletter.subscribe') }}" method="POST" class="footer-newsletter">
                        @csrf
                        <div class="input-group mb-3">
                            <input type="email" name="email"
                                class="form-control form-control-sm bg-transparent border-secondary text-white"
                                placeholder="Your Email" required>
                            <button class="btn btn-primary btn-sm" type="submit"><i class="bi bi-send"></i></button>
                        </div>
                    </form>
                </div>

                <div class="col-lg-4 col-md-6">
                    <h5 class="footer-title">Contact Us</h5>
                    <div class="footer-contact">
                        <p>UQ79, Nii Kwaofio St, Accra, Ghana</p>
                        <p><strong>Phone:</strong> 0256212634</p>
                        <p><strong>Email:</strong> info@reesconsult.com</p>
                    </div>
                    <div class="footer-social">
                        <a href="https://www.facebook.com/reeseconsult/" target="_blank"><i
                                class="bi bi-facebook"></i></a>
                        <a href="https://x.com/ReesConsult" target="_blank"><i class="bi bi-twitter-x"></i></a>
                        <a href="https://www.instagram.com/reesconsult1/" target="_blank"><i
                                class="bi bi-instagram"></i></a>
                        <a href="https://gh.linkedin.com/company/rees-consult" target="_blank"><i
                                class="bi bi-linkedin"></i></a>
                        <a href="https://www.tiktok.com/tag/REESConsult" target="_blank"><i
                                class="bi bi-tiktok"></i></a>
                        <a href="http://www.youtube.com/@ReesConsult" target="_blank"><i
                                class="bi bi-youtube"></i></a>
                    </div>
                </div>
            </div>

            <div class="footer-bottom">
                <div class="row align-items-center">
                    <div class="col-md-6 text-center text-md-start">
                        &copy; Copyright 2024 Ree's Consult.
                    </div>
                    <div class="col-md-6 text-center text-md-end">
                        <a href="#" class="me-3">Terms</a> |
                        <a href="#" class="mx-3">FAQs</a> |
                        <a href="#" class="ms-3">Purchase</a>
                    </div>
                </div>
            </div>
        </div>
    </footer>

    <!-- Scroll to Top Button -->
    <div class="scroll-top" onclick="window.scrollTo({top: 0, behavior: 'smooth'})">
        <i class="bi bi-arrow-up"></i>
    </div>

    <!-- Bootstrap 5 JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous">
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Scroll Animation Observer
            const observerOptions = {
                root: null,
                rootMargin: '0px',
                threshold: 0.1
            };

            const observer = new IntersectionObserver((entries, observer) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('active');
                    }
                });
            }, observerOptions);

            // Observe all elements with scroll-reveal classes
            document.querySelectorAll('.scroll-reveal, .scroll-reveal-left, .scroll-reveal-right').forEach(el => {
                observer.observe(el);
            });

            // Scroll to top button visibility
            window.addEventListener('scroll', function() {
                const scrollTopBtn = document.querySelector('.scroll-top');
                if (window.pageYOffset > 300) {
                    scrollTopBtn.style.opacity = '1';
                    scrollTopBtn.style.pointerEvents = 'all';
                } else {
                    scrollTopBtn.style.opacity = '0';
                    scrollTopBtn.style.pointerEvents = 'none';
                }
            });
            // Newsletter AJAX Submission
            const newsletterForm = document.querySelector('.footer-newsletter');
            if (newsletterForm) {
                newsletterForm.addEventListener('submit', function(e) {
                    e.preventDefault();

                    const form = this;
                    const input = form.querySelector('input[name="email"]');
                    const button = form.querySelector('button');
                    const originalBtnContent = button.innerHTML;

                    // Disable button and show loading state
                    button.disabled = true;
                    button.innerHTML =
                        '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>';

                    fetch(form.action, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')
                                    .content,
                                'Accept': 'application/json'
                            },
                            body: JSON.stringify({
                                email: input.value
                            })
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                // Trigger Meta Pixel events if present
                                if (window.triggerMetaPixelEvents && data.fb_events) {
                                    window.triggerMetaPixelEvents(data.fb_events);
                                }
                                // Show success message
                                input.value = '';
                                const successAlert = document.createElement('div');
                                successAlert.className =
                                    'alert alert-success alert-dismissible fade show mt-2 small';
                                successAlert.innerHTML = `
                                ${data.message}
                                <button type="button" class="btn-close small p-2" data-bs-dismiss="alert"></button>
                            `;
                                form.appendChild(successAlert);

                                // Remove alert after 5 seconds
                                setTimeout(() => successAlert.remove(), 5000);
                            } else {
                                // Show error message
                                const errorAlert = document.createElement('div');
                                errorAlert.className =
                                    'alert alert-danger alert-dismissible fade show mt-2 small';
                                errorAlert.innerHTML = `
                                ${data.message || 'Something went wrong. Please try again.'}
                                <button type="button" class="btn-close small p-2" data-bs-dismiss="alert"></button>
                            `;
                                form.appendChild(errorAlert);
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            alert('An error occurred. Please try again.');
                        })
                        .finally(() => {
                            button.disabled = false;
                            button.innerHTML = originalBtnContent;
                        });
                });
            }
        });
    </script>

    <!-- Apply Now qualification modal (available site-wide) -->
    @include('partials.apply-modal')

    @stack('scripts')
</body>

</html>
