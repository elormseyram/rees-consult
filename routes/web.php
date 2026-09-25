<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ConsultationController as AdminConsultationController;
use App\Http\Controllers\ConsultationController;
use App\Http\Controllers\HomeController;

// Serve public-disk uploads when the hosting environment cannot preserve a storage symlink.
Route::get('/storage/{path}', function (string $path) {
    $disk = Storage::disk('public');

    abort_unless($disk->exists($path), 404);

    return response()->file($disk->path($path));
})->where('path', '.*')->name('storage.fallback');

// Public Routes
Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('/about', function () {
    $teamMembers = \App\Models\TeamMember::active()->ordered()->get();
    $testimonials = \App\Models\Testimonial::active()->ordered()->take(2)->get();
    return view('about', compact('teamMembers', 'testimonials'));
})->name('about');
Route::get('/events', [App\Http\Controllers\EventController::class, 'index'])->name('events.index');
Route::get('/events/{slug}', [App\Http\Controllers\EventController::class, 'show'])->name('events.show');
Route::get('/services', [App\Http\Controllers\ServiceController::class, 'index'])->name('services.index');
Route::get('/services/{service:slug}', [App\Http\Controllers\ServiceController::class, 'show'])->name('services.show');
Route::get('/contact', [App\Http\Controllers\ContactController::class, 'index'])->name('contact.index');
Route::post('/contact', [App\Http\Controllers\ContactController::class, 'store'])->name('contact.store');
Route::get('/blog', [App\Http\Controllers\BlogController::class, 'index'])->name('blog.index');
Route::get('/blog/{slug}', [App\Http\Controllers\BlogController::class, 'show'])->name('blog.show');
Route::get('/scholarships', [App\Http\Controllers\ScholarshipController::class, 'index'])->name('scholarships.index');
Route::get('/scholarships/{scholarship}', [App\Http\Controllers\ScholarshipController::class, 'show'])->name('scholarships.show');
Route::get('/testimonials', [App\Http\Controllers\TestimonialController::class, 'index'])->name('testimonials.index');
Route::get('/awards', [App\Http\Controllers\AwardController::class, 'index'])->name('awards.index');

// Consultation Booking (Public)
Route::post('/consultations', [ConsultationController::class, 'store'])->name('consultations.store');

// Apply Now — dedicated ads landing page + multi-step qualification form
Route::get('/apply', [App\Http\Controllers\ApplyController::class, 'index'])->name('apply.index');
Route::post('/apply', [App\Http\Controllers\ApplyController::class, 'store'])->name('apply.store');
Route::get('/csrf-token', function () {
    return response()->json(['token' => csrf_token()])
        ->header('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0')
        ->header('Pragma', 'no-cache')
        ->header('X-LiteSpeed-Cache-Control', 'no-cache');
});
Route::post('/newsletter/subscribe', [App\Http\Controllers\NewsletterController::class, 'subscribe'])->name('newsletter.subscribe');

// Lead Captures for Restructured Services
Route::post('/services/signup', [App\Http\Controllers\ServiceSignupController::class, 'store'])->name('services.signup');
Route::post('/services/school-apply', [App\Http\Controllers\SchoolApplicationController::class, 'store'])->name('services.school-apply');
Route::post('/services/job-apply', [App\Http\Controllers\JobApplicationController::class, 'store'])->name('services.job-apply');

// Custom Forms (Public)
Route::get('/forms/{slug}', [App\Http\Controllers\CustomFormResponseController::class, 'show'])->name('custom_forms.show');
Route::post('/forms/{slug}', [App\Http\Controllers\CustomFormResponseController::class, 'store'])->name('custom_forms.store');

// Dynamic XML Sitemap
Route::get('/sitemap.xml', function () {
    $services     = \App\Models\Service::active()->ordered()->get(['slug', 'updated_at']);
    $posts        = \App\Models\Post::whereNotNull('published_at')->where('published_at', '<=', now())->latest()->get(['slug', 'updated_at', 'published_at']);
    $events       = \App\Models\Event::where('is_active', true)->where('end_time', '>=', now())->orderBy('start_time')->get(['slug', 'updated_at']);
    $scholarships = \App\Models\Scholarship::active()->ordered()->get(['id', 'updated_at']);

    $content = view('sitemap', compact('services', 'posts', 'events', 'scholarships'))->render();

    return response($content, 200)->header('Content-Type', 'application/xml');
})->name('sitemap');

Route::get('/robots.txt', function () {
    return response(file_get_contents(public_path('robots.txt')), 200)
        ->header('Content-Type', 'text/plain');
})->name('robots');

// Authentication Routes (already included by Laravel UI - public registration disabled)
Auth::routes(['register' => false]);

// Admin Panel — accessible to all staff (admins + employees)
Route::middleware(['auth', 'staff'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // ---- Operational features (admins + employees) ----

    // Apply Now leads (BANT-qualified)
    Route::get('leads/export', [App\Http\Controllers\Admin\LeadController::class, 'export'])->name('leads.export');
    Route::resource('leads', App\Http\Controllers\Admin\LeadController::class)->only(['index', 'show', 'destroy']);
    Route::patch('leads/{lead}/status', [App\Http\Controllers\Admin\LeadController::class, 'updateStatus'])->name('leads.update-status');

    // Follow-up notes on a lead
    Route::post('leads/{lead}/notes', [App\Http\Controllers\Admin\LeadNoteController::class, 'store'])->name('leads.notes.store');
    Route::delete('leads/{lead}/notes/{note}', [App\Http\Controllers\Admin\LeadNoteController::class, 'destroy'])->name('leads.notes.destroy');

    // Call recordings captured in the browser
    Route::post('leads/{lead}/recordings', [App\Http\Controllers\Admin\CallRecordingController::class, 'store'])->name('leads.recordings.store');
    Route::get('leads/{lead}/recordings/{recording}/stream', [App\Http\Controllers\Admin\CallRecordingController::class, 'stream'])->name('leads.recordings.stream');
    Route::get('leads/{lead}/recordings/{recording}/download', [App\Http\Controllers\Admin\CallRecordingController::class, 'download'])->name('leads.recordings.download');
    Route::patch('leads/{lead}/recordings/{recording}', [App\Http\Controllers\Admin\CallRecordingController::class, 'update'])->name('leads.recordings.update');
    Route::delete('leads/{lead}/recordings/{recording}', [App\Http\Controllers\Admin\CallRecordingController::class, 'destroy'])->name('leads.recordings.destroy');

    // Lead management routes
    Route::resource('service-signups', App\Http\Controllers\Admin\ServiceSignupController::class)->except(['create', 'store', 'edit', 'update']);
    Route::post('service-signups/{serviceSignup}/status', [App\Http\Controllers\Admin\ServiceSignupController::class, 'updateStatus'])->name('service-signups.updateStatus');
    Route::resource('school-applications', App\Http\Controllers\Admin\SchoolApplicationController::class)->except(['create', 'store', 'edit', 'update']);
    Route::post('school-applications/{schoolApplication}/status', [App\Http\Controllers\Admin\SchoolApplicationController::class, 'updateStatus'])->name('school-applications.updateStatus');
    Route::resource('job-applications', App\Http\Controllers\Admin\JobApplicationController::class)->except(['create', 'store', 'edit', 'update']);
    Route::post('job-applications/{jobApplication}/status', [App\Http\Controllers\Admin\JobApplicationController::class, 'updateStatus'])->name('job-applications.updateStatus');

    // Consultations Management
    Route::resource('consultations', App\Http\Controllers\Admin\ConsultationController::class);
    Route::patch('consultations/{consultation}/status', [App\Http\Controllers\Admin\ConsultationController::class, 'updateStatus'])->name('consultations.update-status');

    // Contact Management
    Route::get('contact', [App\Http\Controllers\Admin\ContactController::class, 'index'])->name('contact.index');
    Route::get('contact/{contactMessage}', [App\Http\Controllers\Admin\ContactController::class, 'show'])->name('contact.show');
    Route::post('contact/{contactMessage}/reply', [App\Http\Controllers\Admin\ContactController::class, 'reply'])->name('contact.reply');
    Route::delete('contact/{contactMessage}', [App\Http\Controllers\Admin\ContactController::class, 'destroy'])->name('contact.destroy');

    // Profile Routes (own account)
    Route::prefix('profile')->name('profile.')->group(function () {
        Route::get('/', [App\Http\Controllers\Admin\ProfileController::class, 'edit'])->name('edit');
        Route::put('/', [App\Http\Controllers\Admin\ProfileController::class, 'update'])->name('update');
        Route::put('/password', [App\Http\Controllers\Admin\ProfileController::class, 'updatePassword'])->name('password.update');
    });

    // ---- Administrator-only features ----
    Route::middleware('admin')->group(function () {
        // Staff / employee management
        Route::resource('employees', App\Http\Controllers\Admin\EmployeeController::class)->except(['show']);

        // Settings — email delivery (Resend / SMTP) + automated email templates
        Route::get('settings/email', [App\Http\Controllers\Admin\SettingController::class, 'email'])->name('settings.email');
        Route::put('settings/email', [App\Http\Controllers\Admin\SettingController::class, 'updateEmail'])->name('settings.email.update');
        Route::post('settings/email/test', [App\Http\Controllers\Admin\SettingController::class, 'testEmail'])->name('settings.email.test');

        Route::get('email-templates', [App\Http\Controllers\Admin\EmailTemplateController::class, 'index'])->name('email-templates.index');
        Route::get('email-templates/{emailTemplate}/edit', [App\Http\Controllers\Admin\EmailTemplateController::class, 'edit'])->name('email-templates.edit');
        Route::put('email-templates/{emailTemplate}', [App\Http\Controllers\Admin\EmailTemplateController::class, 'update'])->name('email-templates.update');
        Route::patch('email-templates/{emailTemplate}/toggle', [App\Http\Controllers\Admin\EmailTemplateController::class, 'toggle'])->name('email-templates.toggle');
        Route::post('email-templates/{emailTemplate}/preview', [App\Http\Controllers\Admin\EmailTemplateController::class, 'preview'])->name('email-templates.preview');

        Route::resource('events', App\Http\Controllers\Admin\EventController::class);
        Route::resource('reply_templates', App\Http\Controllers\Admin\ReplyTemplateController::class);
        Route::resource('posts', App\Http\Controllers\Admin\PostController::class);
        Route::resource('services', App\Http\Controllers\Admin\ServiceController::class);
        Route::resource('testimonials', App\Http\Controllers\Admin\TestimonialController::class);
        Route::patch('testimonials/{testimonial}/toggle', [App\Http\Controllers\Admin\TestimonialController::class, 'toggleStatus'])->name('testimonials.toggle');
        Route::post('testimonials/reorder', [App\Http\Controllers\Admin\TestimonialController::class, 'reorder'])->name('testimonials.reorder');
        Route::resource('team', App\Http\Controllers\Admin\TeamMemberController::class);
        Route::resource('scholarships', App\Http\Controllers\Admin\ScholarshipController::class);
        Route::resource('hero-images', App\Http\Controllers\Admin\HeroImageController::class);
        Route::resource('hero-sliders', App\Http\Controllers\Admin\HeroSliderController::class);
        Route::resource('service-offerings', App\Http\Controllers\Admin\ServiceOfferingController::class);
        Route::resource('pricings', App\Http\Controllers\Admin\PricingController::class);
        Route::post('awards/bulk-upload', [App\Http\Controllers\Admin\AwardController::class, 'bulkUpload'])->name('awards.bulk-upload');
        Route::resource('awards', App\Http\Controllers\Admin\AwardController::class);
        Route::patch('awards/{award}/toggle', [App\Http\Controllers\Admin\AwardController::class, 'toggleStatus'])->name('awards.toggle');
        Route::post('awards/reorder', [App\Http\Controllers\Admin\AwardController::class, 'reorder'])->name('awards.reorder');

        // Custom Forms Management
        Route::get('custom_forms/{customForm}/submissions', [App\Http\Controllers\Admin\CustomFormController::class, 'submissions'])->name('custom_forms.submissions');
        Route::get('custom_forms/{customForm}/export', [App\Http\Controllers\Admin\CustomFormController::class, 'export'])->name('custom_forms.export');
        Route::resource('custom_forms', App\Http\Controllers\Admin\CustomFormController::class);

        // Newsletter Management
        Route::get('newsletter', [App\Http\Controllers\Admin\NewsletterController::class, 'index'])->name('newsletter.index');
        Route::patch('newsletter/{subscriber}/toggle', [App\Http\Controllers\Admin\NewsletterController::class, 'toggleStatus'])->name('newsletter.toggle');
        Route::delete('newsletter/{subscriber}', [App\Http\Controllers\Admin\NewsletterController::class, 'destroy'])->name('newsletter.destroy');

        // Newsletter Campaign Management
        Route::get('newsletter/campaigns', [App\Http\Controllers\Admin\NewsletterCampaignController::class, 'index'])->name('newsletter.campaigns.index');
        Route::get('newsletter/campaigns/create', [App\Http\Controllers\Admin\NewsletterCampaignController::class, 'create'])->name('newsletter.campaigns.create');
        Route::post('newsletter/campaigns', [App\Http\Controllers\Admin\NewsletterCampaignController::class, 'store'])->name('newsletter.campaigns.store');
        Route::post('newsletter/campaigns/{newsletter}/send', [App\Http\Controllers\Admin\NewsletterCampaignController::class, 'send'])->name('newsletter.campaigns.send');
        Route::delete('newsletter/campaigns/{newsletter}', [App\Http\Controllers\Admin\NewsletterCampaignController::class, 'destroy'])->name('newsletter.campaigns.destroy');
    });
});
