<?php

namespace Tests\Feature;

use App\Models\Testimonial;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TestimonialsTest extends TestCase
{
    use RefreshDatabase;

    public function test_testimonials_page_loads_successfully(): void
    {
        // Seed at least one testimonial
        Testimonial::create([
            'name' => 'John Doe',
            'role' => 'Software Engineer',
            'message' => 'Excellent service provided by Rees Consult!',
            'rating' => 5,
            'is_active' => true,
            'order' => 1,
        ]);

        $response = $this->get('/testimonials');

        $response->assertStatus(200);
        $response->assertSee('John Doe');
        $response->assertSee('Software Engineer');
        $response->assertSee('Excellent service provided by Rees Consult!');
    }

    public function test_testimonials_page_filters_written_testimonials(): void
    {
        // Active written testimonial
        Testimonial::create([
            'name' => 'Alice Written',
            'role' => 'Student',
            'message' => 'Alice written testimonial message',
            'rating' => 5,
            'is_active' => true,
            'order' => 1,
        ]);

        // Active video testimonial
        Testimonial::create([
            'name' => 'Bob Video',
            'role' => 'Student',
            'message' => 'Bob video testimonial message',
            'youtube_video_url' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ',
            'rating' => 5,
            'is_active' => true,
            'order' => 2,
        ]);

        // Request written only
        $response = $this->get('/testimonials?type=written');
        $response->assertStatus(200);
        $response->assertSee('Alice Written');
        $response->assertDontSee('Bob Video');

        // Request video only
        $response = $this->get('/testimonials?type=video');
        $response->assertStatus(200);
        $response->assertSee('Bob Video');
        $response->assertDontSee('Alice Written');
    }

    public function test_admin_can_toggle_testimonial_status(): void
    {
        $admin = new User([
            'name' => 'Admin User',
            'email' => 'admin@reesconsult.com',
            'password' => bcrypt('password'),
            'is_active' => true,
        ]);
        $admin->is_admin = true;
        $admin->save();

        $testimonial = Testimonial::create([
            'name' => 'John Doe',
            'role' => 'Student',
            'message' => 'Review Message',
            'rating' => 5,
            'is_active' => false,
        ]);

        $response = $this->actingAs($admin)
            ->patch(route('admin.testimonials.toggle', $testimonial));

        $response->assertRedirect(route('admin.testimonials.index'));
        $this->assertTrue($testimonial->fresh()->is_active);

        // Toggle back to false
        $this->actingAs($admin)
            ->patch(route('admin.testimonials.toggle', $testimonial));
        $this->assertFalse($testimonial->fresh()->is_active);
    }

    public function test_testimonial_message_can_be_null_or_empty(): void
    {
        $testimonial = Testimonial::create([
            'name' => 'Video Reviewer',
            'role' => 'Student',
            'message' => null,
            'youtube_video_url' => 'https://www.youtube.com/watch?v=xyz123',
            'rating' => 5,
            'is_active' => true,
        ]);

        $this->assertDatabaseHas('testimonials', [
            'id' => $testimonial->id,
            'message' => null,
        ]);

        // Verify we can update other fields leaving message null
        $admin = new User([
            'name' => 'Admin User',
            'email' => 'admin@reesconsult.com',
            'password' => bcrypt('password'),
            'is_active' => true,
        ]);
        $admin->is_admin = true;
        $admin->save();

        $response = $this->actingAs($admin)
            ->put(route('admin.testimonials.update', $testimonial), [
                'name' => 'Updated Video Reviewer',
                'role' => 'Graduate Student',
                'rating' => 4,
                'message' => null,
            ]);

        $response->assertRedirect(route('admin.testimonials.index'));
        $this->assertDatabaseHas('testimonials', [
            'id' => $testimonial->id,
            'name' => 'Updated Video Reviewer',
            'role' => 'Graduate Student',
            'message' => null,
        ]);
    }

    public function test_admin_can_reorder_testimonials(): void
    {
        $admin = new User([
            'name' => 'Admin User',
            'email' => 'admin@reesconsult.com',
            'password' => bcrypt('password'),
            'is_active' => true,
        ]);
        $admin->is_admin = true;
        $admin->save();

        $t1 = Testimonial::create([
            'name' => 'Testimonial 1',
            'role' => 'Student',
            'rating' => 5,
            'order' => 0,
        ]);

        $t2 = Testimonial::create([
            'name' => 'Testimonial 2',
            'role' => 'Student',
            'rating' => 5,
            'order' => 1,
        ]);

        $response = $this->actingAs($admin)
            ->postJson(route('admin.testimonials.reorder'), [
                'order' => [
                    ['id' => $t1->id, 'order' => 1],
                    ['id' => $t2->id, 'order' => 0],
                ]
            ]);

        $response->assertStatus(200);
        $response->assertJson(['success' => true]);

        $this->assertEquals(1, $t1->fresh()->order);
        $this->assertEquals(0, $t2->fresh()->order);
    }
}

