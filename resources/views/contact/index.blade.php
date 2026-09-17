@extends('layouts.app')

@section('title', 'Contact Rees Consult - Get Expert IELTS & Study Abroad Guidance')

@section('description',
    'Contact Rees Consult for IELTS tutoring, test preparation, and study abroad consultancy. Reach
    out to our expert team for personalized guidance and support.')

@section('keywords', 'contact Rees Consult, IELTS consultation, study abroad help, education consultancy contact')

@section('og_title', 'Contact Rees Consult - Expert Education Guidance')

@section('og_description',
    'Get in touch with our expert team for IELTS preparation and international education
    support.')

@section('content')
    @php
        use App\Helpers\HeroImageHelper;
        $heroImage = HeroImageHelper::getHeroImageData('contact');
        $imageUrl = $heroImage
            ? HeroImageHelper::getHeroImageUrl('contact')
            : 'https://images.unsplash.com/photo-1423666639041-f142fcb93370?w=1600';
        $overlayOpacity = $heroImage ? $heroImage->overlay_opacity : 0.8;
    @endphp

    <!-- Page Header -->
    <div class="page-header"
        style="background: linear-gradient(rgba(10, 30, 66, {{ $overlayOpacity }}), rgba(10, 30, 66, {{ $overlayOpacity }})), url('{{ $imageUrl }}') center/cover; padding: 100px 0; color: white; text-align: center;">
        <div class="container">
            <h1 class="display-4 fw-bold mb-3">Contact Us</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb justify-content-center mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-white text-decoration-none">Home</a>
                    </li>
                    <li class="breadcrumb-item active text-white-50" aria-current="page">Contact</li>
                </ol>
            </nav>
        </div>
    </div>

    <section class="py-5">
        <div class="container py-5">
            <div class="row g-5">
                <!-- Contact Info -->
                <div class="col-lg-5">
                    <div class="mb-5">
                        <h2 class="fw-bold mb-4 text-dark">Get in Touch</h2>
                        <p class="text-muted mb-4">Get one on one with our experts to guide you through your school or job
                            application process.</p>

                        <div class="d-flex align-items-center mb-4">
                            <div class="flex-shrink-0 bg-light rounded-circle p-3 text-primary me-3">
                                <i class="bi bi-telephone fs-4"></i>
                            </div>
                            <div>
                                <h6 class="fw-bold mb-1">Phone</h6>
                                <p class="text-muted mb-0">0256212634</p>
                            </div>
                        </div>

                        <div class="d-flex align-items-center mb-4">
                            <div class="flex-shrink-0 bg-light rounded-circle p-3 text-primary me-3">
                                <i class="bi bi-envelope fs-4"></i>
                            </div>
                            <div>
                                <h6 class="fw-bold mb-1">Email</h6>
                                <p class="text-muted mb-0">info@reesconsult.com.com</p>
                            </div>
                        </div>

                        <div class="d-flex align-items-center mb-4">
                            <div class="flex-shrink-0 bg-light rounded-circle p-3 text-primary me-3">
                                <i class="bi bi-geo-alt fs-4"></i>
                            </div>
                            <div>
                                <h6 class="fw-bold mb-1">Location</h6>
                                <p class="text-muted mb-0">UQ79, Nii Kwaofio St, Accra, Ghana</p>
                            </div>
                        </div>
                    </div>

                    <h3 class="fw-bold mb-4 text-dark">Find our location</h3>
                    <div class="rounded-4 overflow-hidden shadow-sm">
                        <iframe
                            src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3970.254918769348!2d-0.12904849999999998!3d5.6762416!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0xfdf833d1606f49b%3A0xcb967029b3beaa04!2sRee's%20Consult!5e0!3m2!1sen!2scm!4v1766156993870!5m2!1sen!2scm"
                            width="100%" height="300" style="border:0;" allowfullscreen="" loading="lazy"></iframe>
                    </div>
                </div>

                <!-- Contact Form -->
                <div class="col-lg-7">
                    <div class="card border-0 shadow-sm rounded-4 p-4 p-md-5">
                        @if (session('success'))
                            <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
                                <i class="bi bi-check-circle me-2"></i> {{ session('success') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        @endif

                        <form action="{{ route('contact.store') }}" method="POST">
                            @csrf
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <div class="form-floating mb-3">
                                        <input type="text" class="form-control bg-light border-0" id="name"
                                            name="name" placeholder="First Name" required>
                                        <label for="name">Your Name</label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-floating mb-3">
                                        <input type="email" class="form-control bg-light border-0" id="email"
                                            name="email" placeholder="Your Email" required>
                                        <label for="email">Your Email</label>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="form-floating mb-3">
                                        <input type="tel" class="form-control bg-light border-0" id="phone"
                                            name="phone" placeholder="Phone Number">
                                        <label for="phone">Phone Number (Optional)</label>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="form-floating mb-3">
                                        <input type="text" class="form-control bg-light border-0" id="subject"
                                            name="subject" placeholder="Subject" required>
                                        <label for="subject">Your Subject</label>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="form-floating mb-3">
                                        <textarea class="form-control bg-light border-0" id="message" name="message" placeholder="Message"
                                            style="height: 200px" required></textarea>
                                        <label for="message">Your Message</label>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <button type="submit"
                                        class="btn btn-primary w-100 py-3 fw-bold rounded-3">Submit</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
