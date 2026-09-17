<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Admin Panel') - {{ config('app.name', 'Rees Consult') }}</title>

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

    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- Custom CSS -->
    <link href="{{ asset('css/style.css') }}" rel="stylesheet">

    <style>
        /* ==========================================
           Admin Panel — Global Styles
           ========================================== */

        body.admin-body {
            font-family: 'Inter', sans-serif;
            background: #f1f3f8;
        }

        /* ---- Sidebar ---- */
        .admin-sidebar {
            min-height: 100vh;
            background: linear-gradient(180deg, #0f1b2d 0%, #1a2942 100%);
            color: white;
            position: sticky;
            top: 0;
            overflow-y: auto;
        }

        .admin-sidebar .sidebar-brand {
            padding: 1.25rem 1.5rem;
            border-bottom: 1px solid rgba(255,255,255,0.08);
        }
        .admin-sidebar .sidebar-brand h4 {
            color: #fff;
            font-weight: 700;
            letter-spacing: -0.5px;
        }
        .admin-sidebar .sidebar-brand small {
            color: rgba(255,255,255,0.5);
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        /* Section labels */
        .sidebar-section-label {
            font-size: 0.65rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            color: rgba(255,255,255,0.35);
            padding: 1rem 1.5rem 0.4rem;
        }

        /* Nav Links */
        .admin-sidebar .nav-link {
            color: #ffffff;
            padding: 0.6rem 1.5rem;
            font-size: 0.875rem;
            font-weight: 500;
            border-left: 3px solid transparent;
            transition: all 0.2s ease;
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .admin-sidebar .nav-link i {
            font-size: 1.1rem;
            width: 20px;
            text-align: center;
            flex-shrink: 0;
        }

        .admin-sidebar .nav-link:hover {
            color: #fff;
            background: rgba(255, 255, 255, 0.06);
            border-left-color: rgba(248, 167, 6, 0.5);
        }

        .admin-sidebar .nav-link.active {
            color: #fff;
            background: rgba(248, 167, 6, 0.12);
            border-left-color: #F8A706;
            font-weight: 600;
        }

        .admin-sidebar .nav-link .badge {
            font-size: 0.65rem;
            margin-left: auto;
        }

        /* Submenu */
        .admin-sidebar .submenu .nav-link {
            padding-left: 3rem;
            font-size: 0.82rem;
        }

        /* ---- Main Content ---- */
        .admin-main {
            background-color: #f1f3f8;
            min-height: 100vh;
        }

        /* Admin top bar */
        .admin-topbar {
            background: #fff;
            border-bottom: 1px solid #e5e7eb;
            padding: 0.75rem 1.5rem;
            display: flex;
            justify-content: flex-end;
            align-items: center;
            gap: 1rem;
        }

        /* Admin headings */
        .admin-main h1, .admin-main h2, .admin-main h3,
        .admin-main h4, .admin-main h5, .admin-main h6 {
            color: #1f2937;
        }

        /* Admin cards don't lift */
        .admin-main .card {
            border: 1px solid #e9ecef;
            box-shadow: 0 1px 3px rgba(0,0,0,0.04);
            border-radius: 10px;
            transition: box-shadow 0.2s ease;
        }
        .admin-main .card:hover {
            box-shadow: 0 2px 8px rgba(0,0,0,0.06);
            transform: none;
        }

        /* Admin tables */
        .admin-main .table {
            font-size: 0.9rem;
        }
        .admin-main .table thead th {
            font-size: 0.75rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #6b7280;
            border-bottom: 2px solid #e9ecef;
        }
        .admin-main .table tbody tr {
            transition: background 0.15s ease;
        }
        .admin-main .table tbody tr:hover {
            background: #f8fafc;
        }

        /* Action Buttons inside admin — solid, not outline */
        .admin-main .btn-action {
            font-size: 0.8rem;
            font-weight: 500;
            padding: 0.35rem 0.75rem;
            border-radius: 6px;
            display: inline-flex;
            align-items: center;
            gap: 0.35rem;
        }

        /* Breadcrumb-like page header */
        .admin-page-header {
            margin-bottom: 1.5rem;
        }
        .admin-page-header h1 {
            font-size: 1.5rem;
            font-weight: 700;
        }
        .admin-page-header p {
            color: #6b7280;
            margin-bottom: 0;
        }

        /* ---- Sidebar Divider ---- */
        .sidebar-divider {
            border: none;
            border-top: 1px solid rgba(255,255,255,0.08);
            margin: 0.5rem 1rem;
        }

        /* Mobile sidebar toggle */
        @media (max-width: 767.98px) {
            .admin-sidebar {
                position: fixed;
                z-index: 1050;
                width: 260px;
                transform: translateX(-100%);
                transition: transform 0.3s ease;
            }
            .admin-sidebar.show {
                transform: translateX(0);
            }
            .sidebar-backdrop {
                position: fixed;
                inset: 0;
                background: rgba(0,0,0,0.5);
                z-index: 1040;
                display: none;
            }
            .sidebar-backdrop.show {
                display: block;
            }
        }
    </style>
    @stack('styles')
</head>

<body class="admin-body">
    <div class="container-fluid p-0">
        <div class="row g-0">

            {{-- Mobile Backdrop --}}
            <div class="sidebar-backdrop" id="sidebarBackdrop" onclick="toggleSidebar()"></div>

            <!-- Sidebar -->
            <nav class="col-md-3 col-lg-2 admin-sidebar p-0" id="adminSidebar">
                <div class="position-sticky pt-0" style="top: 0;">
                    {{-- Brand --}}
                    <div class="sidebar-brand">
                        <h4 class="mb-0"><i class="bi bi-mortarboard-fill me-2" style="color: #F8A706;"></i>Rees Consult</h4>
                        <small>{{ auth()->user()->isAdmin() ? 'Administrator' : 'Employee' }} Panel</small>
                    </div>

                    {{-- Main Navigation --}}
                    <div class="sidebar-section-label">Main</div>
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}"
                                href="{{ route('admin.dashboard') }}">
                                <i class="bi bi-grid-1x2-fill"></i> Dashboard
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('admin.consultations.*') ? 'active' : '' }}"
                                href="{{ route('admin.consultations.index') }}">
                                <i class="bi bi-calendar2-check-fill"></i> Consultations
                            </a>
                        </li>
                        @if(auth()->user()->isAdmin())
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('admin.events.*') ? 'active' : '' }}"
                                href="{{ route('admin.events.index') }}">
                                <i class="bi bi-calendar-event-fill"></i> Events
                            </a>
                        </li>
                        @endif
                    </ul>

                    <div class="sidebar-section-label">Applicants & Leads</div>
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('admin.leads.*') ? 'active' : '' }}"
                                href="{{ route('admin.leads.index') }}">
                                <i class="bi bi-fire"></i> Apply Now Leads
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('admin.service-signups.*') ? 'active' : '' }}"
                                href="{{ route('admin.service-signups.index') }}">
                                <i class="bi bi-patch-check-fill"></i> Test enrollments
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('admin.school-applications.*') ? 'active' : '' }}"
                                href="{{ route('admin.school-applications.index') }}">
                                <i class="bi bi-mortarboard-fill"></i> School Applications
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('admin.job-applications.*') ? 'active' : '' }}"
                                href="{{ route('admin.job-applications.index') }}">
                                <i class="bi bi-globe-americas"></i> Job Placements
                            </a>
                        </li>
                    </ul>

                    <div class="sidebar-section-label">Messages</div>
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('admin.contact.*') ? 'active' : '' }}"
                                href="{{ route('admin.contact.index') }}">
                                <i class="bi bi-envelope-fill"></i> Inbox
                            </a>
                        </li>
                    </ul>

                    @if(auth()->user()->isAdmin())
                    <div class="sidebar-section-label">Content</div>
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('admin.posts.*') ? 'active' : '' }}"
                                href="{{ route('admin.posts.index') }}">
                                <i class="bi bi-journal-richtext"></i> Blog Posts
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('admin.services.*') ? 'active' : '' }}"
                                href="{{ route('admin.services.index') }}">
                                <i class="bi bi-briefcase-fill"></i> Services
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('admin.service-offerings.*') ? 'active' : '' }}"
                                href="{{ route('admin.service-offerings.index') }}">
                                <i class="bi bi-box-seam-fill"></i> Service Offerings
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('admin.scholarships.*') ? 'active' : '' }}"
                                href="{{ route('admin.scholarships.index') }}">
                                <i class="bi bi-mortarboard-fill"></i> Scholarships
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('admin.testimonials.*') ? 'active' : '' }}"
                                href="{{ route('admin.testimonials.index') }}">
                                <i class="bi bi-chat-quote-fill"></i> Testimonials
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('admin.awards.*') ? 'active' : '' }}"
                                href="{{ route('admin.awards.index') }}">
                                <i class="bi bi-trophy-fill"></i> Awards
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('admin.team.*') ? 'active' : '' }}"
                                href="{{ route('admin.team.index') }}">
                                <i class="bi bi-people-fill"></i> Team
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('admin.pricings.*') ? 'active' : '' }}"
                                href="{{ route('admin.pricings.index') }}">
                                <i class="bi bi-tags-fill"></i> Pricing
                            </a>
                        </li>
                    </ul>

                    <div class="sidebar-section-label">Media</div>
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('admin.hero-images.*') ? 'active' : '' }}"
                                href="{{ route('admin.hero-images.index') }}">
                                <i class="bi bi-image-fill"></i> Hero Images
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('admin.hero-sliders.*') ? 'active' : '' }}"
                                href="{{ route('admin.hero-sliders.index') }}">
                                <i class="bi bi-images"></i> Hero Slider
                            </a>
                        </li>
                    </ul>

                    <div class="sidebar-section-label">Engagement</div>
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('admin.custom_forms.*') ? 'active' : '' }}"
                                href="{{ route('admin.custom_forms.index') }}">
                                <i class="bi bi-ui-checks-grid"></i> Custom Forms
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('admin.reply_templates.*') ? 'active' : '' }}"
                                href="{{ route('admin.reply_templates.index') }}">
                                <i class="bi bi-file-earmark-text-fill"></i> Templates
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link collapsed" href="#newsletterSubmenu" data-bs-toggle="collapse"
                                aria-expanded="{{ request()->routeIs('admin.newsletter.*') ? 'true' : 'false' }}">
                                <i class="bi bi-newspaper"></i> Newsletter
                                <i class="bi bi-chevron-down ms-auto" style="font-size: 0.75rem;"></i>
                            </a>
                            <div class="collapse {{ request()->routeIs('admin.newsletter.*') ? 'show' : '' }}"
                                id="newsletterSubmenu">
                                <ul class="nav flex-column submenu">
                                    <li class="nav-item">
                                        <a class="nav-link {{ request()->routeIs('admin.newsletter.index') ? 'active' : '' }}"
                                            href="{{ route('admin.newsletter.index') }}">
                                            <i class="bi bi-people"></i> Subscribers
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link {{ request()->routeIs('admin.newsletter.campaigns.*') ? 'active' : '' }}"
                                            href="{{ route('admin.newsletter.campaigns.index') }}">
                                            <i class="bi bi-megaphone-fill"></i> Campaigns
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </li>
                    </ul>

                    <div class="sidebar-section-label">Administration</div>
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('admin.employees.*') ? 'active' : '' }}"
                                href="{{ route('admin.employees.index') }}">
                                <i class="bi bi-person-badge-fill"></i> Staff &amp; Employees
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link collapsed" href="#settingsSubmenu" data-bs-toggle="collapse"
                                aria-expanded="{{ request()->routeIs('admin.settings.*') || request()->routeIs('admin.email-templates.*') ? 'true' : 'false' }}">
                                <i class="bi bi-gear-fill"></i> Settings
                                <i class="bi bi-chevron-down ms-auto" style="font-size: 0.75rem;"></i>
                            </a>
                            <div class="collapse {{ request()->routeIs('admin.settings.*') || request()->routeIs('admin.email-templates.*') ? 'show' : '' }}"
                                id="settingsSubmenu">
                                <ul class="nav flex-column submenu">
                                    <li class="nav-item">
                                        <a class="nav-link {{ request()->routeIs('admin.settings.email') ? 'active' : '' }}"
                                            href="{{ route('admin.settings.email') }}">
                                            <i class="bi bi-envelope-gear"></i> Email Delivery
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link {{ request()->routeIs('admin.email-templates.*') ? 'active' : '' }}"
                                            href="{{ route('admin.email-templates.index') }}">
                                            <i class="bi bi-envelope-paper"></i> Automated Emails
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </li>
                    </ul>
                    @endif

                    <hr class="sidebar-divider">

                    <ul class="nav flex-column pb-4">
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('admin.profile.*') ? 'active' : '' }}"
                                href="{{ route('admin.profile.edit') }}">
                                <i class="bi bi-person-gear"></i> Profile Settings
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('home') }}" target="_blank">
                                <i class="bi bi-box-arrow-up-right"></i> View Website
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('logout') }}"
                                onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                                style="color: rgba(239,68,68,0.7);">
                                <i class="bi bi-box-arrow-right"></i> Logout
                            </a>
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                @csrf
                            </form>
                        </li>
                    </ul>
                </div>
            </nav>

            <!-- Main Content -->
            <main class="col-md-9 ms-sm-auto col-lg-10 admin-main">
                {{-- Mobile Toggle --}}
                <div class="admin-topbar d-md-none">
                    <button class="btn btn-sm btn-dark me-auto" onclick="toggleSidebar()">
                        <i class="bi bi-list"></i> Menu
                    </button>
                    <span class="text-muted small">{{ auth()->user()->name ?? 'Admin' }}</span>
                </div>

                <div class="py-4 px-md-4">
                    @yield('content')
                </div>
            </main>
        </div>
    </div>

    <!-- Bootstrap 5 JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous">
    </script>

    <script>
        // SweetAlert2 Toast Configuration
        const Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true,
            didOpen: (toast) => {
                toast.addEventListener('mouseenter', Swal.stopTimer)
                toast.addEventListener('mouseleave', Swal.resumeTimer)
            }
        });

        // Display Session Messages
        @if (session('success'))
            Toast.fire({
                icon: 'success',
                title: "{{ session('success') }}"
            });
        @endif

        @if (session('error'))
            Toast.fire({
                icon: 'error',
                title: "{{ session('error') }}"
            });
        @endif

        @if (session('warning'))
            Toast.fire({
                icon: 'warning',
                title: "{{ session('warning') }}"
            });
        @endif

        @if (session('info'))
            Toast.fire({
                icon: 'info',
                title: "{{ session('info') }}"
            });
        @endif

        // Global Delete Confirmation
        document.addEventListener('DOMContentLoaded', function() {
            // Find all forms with 'onsubmit' containing 'confirm'
            const forms = document.querySelectorAll('form[onsubmit*="confirm"]');

            forms.forEach(form => {
                // Remove the inline onsubmit attribute
                const confirmMessage = form.getAttribute('onsubmit').match(/'([^']+)'/)[1];
                form.removeAttribute('onsubmit');

                form.addEventListener('submit', function(e) {
                    e.preventDefault();

                    Swal.fire({
                        title: 'Are you sure?',
                        text: confirmMessage || "You won't be able to revert this!",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#d33',
                        cancelButtonColor: '#3085d6',
                        confirmButtonText: 'Yes, delete it!'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            this.submit();
                        }
                    });
                });
            });
        });

        // Mobile Sidebar Toggle
        function toggleSidebar() {
            document.getElementById('adminSidebar').classList.toggle('show');
            document.getElementById('sidebarBackdrop').classList.toggle('show');
        }
    </script>

    @stack('scripts')
</body>

</html>
