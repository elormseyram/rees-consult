<?php

namespace Tests\Feature;

use App\Models\Service;
use App\Models\ServiceSignup;
use App\Models\SchoolApplication;
use App\Models\JobApplication;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ServicesLeadPipelineTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Seed default tests/jobs for the form testing
        Service::create([
            'title' => 'IELTS Tuition & Booking',
            'slug' => 'ielts-tuition-booking',
            'category' => 'standardized_test',
            'description' => 'Premium IELTS preparation classes.',
            'price' => 150.00,
            'processing_fee' => 50.00,
            'status' => 'active',
        ]);

        Service::create([
            'title' => 'Clinical Nursing Placement',
            'slug' => 'clinical-nursing-placement',
            'category' => 'job_abroad',
            'description' => 'Placement in UK hospitals.',
            'price' => 1200.00,
            'processing_fee' => 300.00,
            'country' => 'United Kingdom',
            'status' => 'active',
        ]);
    }

    /** @test */
    public function public_visitor_can_view_services_page()
    {
        $response = $this->get(route('services.index'));
        $response->assertStatus(200);
        $response->assertSee('IELTS Tuition & Booking');
        $response->assertSee('Clinical Nursing Placement');
    }

    /** @test */
    public function public_visitor_can_submit_test_prep_signup()
    {
        $service = Service::where('category', 'standardized_test')->first();

        $response = $this->post(route('services.signup'), [
            'service_id' => $service->id,
            'first_name' => 'John',
            'last_name' => 'Doe',
            'email' => 'john@example.com',
            'phone' => '+233241234567',
            'preferred_class_type' => 'group_online',
            'notes' => 'I want to score band 8.0.',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('service_signups', [
            'first_name' => 'John',
            'email' => 'john@example.com',
            'preferred_class_type' => 'group_online',
        ]);
    }

    /** @test */
    public function public_visitor_can_submit_school_application_with_uploads()
    {
        Storage::fake('public');

        $resume = UploadedFile::fake()->create('cv.pdf', 500);
        $transcript = UploadedFile::fake()->create('academic_transcript.pdf', 1000);

        $response = $this->post(route('services.school-apply'), [
            'first_name' => 'Jane',
            'last_name' => 'Smith',
            'email' => 'jane@example.com',
            'phone' => '+233240000000',
            'course_of_interest' => 'Master of Computer Science',
            'level_of_education' => 'postgraduate',
            'target_countries' => ['Canada', 'Germany'],
            'highest_qualification' => 'Bachelor of Science',
            'has_passport' => '1',
            'budget' => '$10,000 - $20,000',
            'resume' => $resume,
            'transcript' => $transcript,
            'notes' => 'Looking for fall admission.',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('school_applications', [
            'first_name' => 'Jane',
            'email' => 'jane@example.com',
            'course_of_interest' => 'Master of Computer Science',
            'has_passport' => true,
        ]);

        $application = SchoolApplication::first();
        $this->assertNotNull($application->resume_path);
        $this->assertNotNull($application->transcript_path);
        Storage::disk('public')->assertExists($application->resume_path);
        Storage::disk('public')->assertExists($application->transcript_path);
    }

    /** @test */
    public function public_visitor_can_submit_job_application_with_uploads()
    {
        Storage::fake('public');

        $service = Service::where('category', 'job_abroad')->first();
        $resume = UploadedFile::fake()->create('nursing_cv.pdf', 600);

        $response = $this->post(route('services.job-apply'), [
            'service_id' => $service->id,
            'first_name' => 'Alice',
            'last_name' => 'Johnson',
            'email' => 'alice@example.com',
            'phone' => '+233242222222',
            'experience_years' => 5,
            'current_occupation' => 'Registered Nurse',
            'highest_education' => 'Bachelor of Nursing',
            'resume' => $resume,
            'notes' => '5 years ICU nursing experience.',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('job_applications', [
            'first_name' => 'Alice',
            'email' => 'alice@example.com',
            'experience_years' => 5,
        ]);

        $application = JobApplication::first();
        $this->assertNotNull($application->resume_path);
        Storage::disk('public')->assertExists($application->resume_path);
    }

    /** @test */
    public function admin_can_view_leads_and_update_statuses()
    {
        $admin = new User([
            'name' => 'Admin User',
            'email' => 'admin@reesconsult.com',
            'password' => bcrypt('password'),
            'is_active' => true,
        ]);
        $admin->is_admin = true;
        $admin->save();

        $signup = ServiceSignup::create([
            'service_id' => Service::first()->id,
            'first_name' => 'Tester',
            'last_name' => 'One',
            'email' => 'testone@example.com',
            'phone' => '12345',
            'preferred_class_type' => 'group_online',
            'status' => 'pending',
        ]);

        // Attempting to visit dashboard without auth
        $this->get(route('admin.service-signups.index'))->assertRedirect(route('login'));

        // Act as admin
        $response = $this->actingAs($admin)
            ->get(route('admin.service-signups.index'));
            
        $response->assertStatus(200);
        $response->assertSee('Tester One');

        // Test status update action
        $statusResponse = $this->actingAs($admin)
            ->post(route('admin.service-signups.updateStatus', $signup->id), [
                'status' => 'contacted',
                'notes' => 'Spoke to them on phone.',
            ]);

        $statusResponse->assertRedirect();
        $this->assertDatabaseHas('service_signups', [
            'id' => $signup->id,
            'status' => 'contacted',
            'notes' => 'Spoke to them on phone.',
        ]);
    }

    /** @test */
    public function admin_can_create_service_with_extended_fields()
    {
        $admin = new User([
            'name' => 'Admin User',
            'email' => 'admin_create@reesconsult.com',
            'password' => bcrypt('password'),
            'is_active' => true,
        ]);
        $admin->is_admin = true;
        $admin->save();

        $response = $this->actingAs($admin)->post(route('admin.services.store'), [
            'title' => 'New Special Exam Prep',
            'subtitle' => 'Get ready with experts',
            'category' => 'standardized_test',
            'description' => 'Detailed description of the new service.',
            'price' => 200.00,
            'processing_fee' => 0.00,
            'icon' => 'bi-laptop',
            'order' => 10,
            'color' => '#E83E8C',
            'duration' => '10 Weeks',
            'class_size' => 'Max 15 Students',
            'features' => [
                'Feature 1',
                '', // empty, should be filtered
                'Feature 2',
            ],
            'why_choose_program' => [
                'Reason 1',
                'Reason 2',
                ' ', // whitespace, should be filtered
            ],
        ]);

        $response->assertRedirect(route('admin.services.index'));
        
        $this->assertDatabaseHas('services', [
            'title' => 'New Special Exam Prep',
            'color' => '#E83E8C',
            'duration' => '10 Weeks',
            'class_size' => 'Max 15 Students',
        ]);

        $service = Service::where('title', 'New Special Exam Prep')->first();
        $this->assertEquals(['Feature 1', 'Feature 2'], $service->features);
        $this->assertEquals(['Reason 1', 'Reason 2'], $service->why_choose_program);
    }

    /** @test */
    public function admin_can_update_service_with_extended_fields()
    {
        $admin = new User([
            'name' => 'Admin User',
            'email' => 'admin_update@reesconsult.com',
            'password' => bcrypt('password'),
            'is_active' => true,
        ]);
        $admin->is_admin = true;
        $admin->save();

        $service = Service::create([
            'title' => 'Test Service to Update',
            'slug' => 'test-service-to-update',
            'category' => 'job_abroad',
            'description' => 'Original description.',
            'price' => 500.00,
            'processing_fee' => 100.00,
            'is_active' => true,
        ]);

        $response = $this->actingAs($admin)->put(route('admin.services.update', $service), [
            'title' => 'Updated Service Title',
            'subtitle' => 'New subtitle',
            'category' => 'job_abroad',
            'description' => 'Updated description.',
            'price' => 600.00,
            'processing_fee' => 150.00,
            'icon' => 'bi-briefcase',
            'order' => 2,
            'color' => '#0D6EFD',
            'duration' => '6 Months',
            'class_size' => 'N/A',
            'features' => ['New Feature Row'],
            'why_choose_program' => ['New Reason Row'],
        ]);

        $response->assertRedirect(route('admin.services.index'));

        $this->assertDatabaseHas('services', [
            'id' => $service->id,
            'title' => 'Updated Service Title',
            'color' => '#0D6EFD',
            'duration' => '6 Months',
            'class_size' => 'N/A',
        ]);

        $service->refresh();
        $this->assertEquals(['New Feature Row'], $service->features);
        $this->assertEquals(['New Reason Row'], $service->why_choose_program);
    }
}
