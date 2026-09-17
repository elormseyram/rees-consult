<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

use App\Models\Award;
use App\Models\User;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\UploadedFile;

class AwardsTest extends TestCase
{
    use RefreshDatabase;

    protected function getAdminUser()
    {
        return User::factory()->create([
            'is_admin'  => true,
            'is_active' => true,
        ]);
    }

    public function test_admin_can_view_awards_list_with_search_and_filter()
    {
        $admin = $this->getAdminUser();

        Award::create([
            'title' => 'Best Study Prep 2024',
            'is_active' => true,
            'order' => 1,
        ]);

        Award::create([
            'title' => 'Outstanding Agency 2025',
            'is_active' => false,
            'order' => 2,
        ]);

        // Access index page
        $response = $this->actingAs($admin)->get(route('admin.awards.index'));
        $response->assertStatus(200);
        $response->assertSee('Best Study Prep 2024');
        $response->assertSee('Outstanding Agency 2025');

        // Test Search Filter
        $response = $this->actingAs($admin)->get(route('admin.awards.index', ['search' => 'Prep']));
        $response->assertStatus(200);
        $response->assertSee('Best Study Prep 2024');
        $response->assertDontSee('Outstanding Agency 2025');

        // Test Status Filter
        $response = $this->actingAs($admin)->get(route('admin.awards.index', ['status' => 'inactive']));
        $response->assertStatus(200);
        $response->assertDontSee('Best Study Prep 2024');
        $response->assertSee('Outstanding Agency 2025');
    }

    public function test_admin_can_bulk_upload_award_images()
    {
        $admin = $this->getAdminUser();
        Storage::fake('public');

        $file1 = UploadedFile::fake()->image('ielts-trophy-2026.png');
        $file2 = UploadedFile::fake()->image('british-council-award.jpg');

        $response = $this->actingAs($admin)->post(route('admin.awards.bulk-upload'), [
            'files' => [$file1, $file2],
        ]);

        $response->assertRedirect(route('admin.awards.index'));
        $response->assertSessionHas('success');

        // Verify the database has the new records with titles formatted from filenames
        $this->assertDatabaseHas('awards', [
            'title' => 'Ielts Trophy 2026',
            'is_active' => true,
            'order' => 0,
        ]);

        $this->assertDatabaseHas('awards', [
            'title' => 'British Council Award',
            'is_active' => true,
            'order' => 1,
        ]);

        // Verify files stored
        $award1 = Award::where('title', 'Ielts Trophy 2026')->first();
        $award2 = Award::where('title', 'British Council Award')->first();

        Storage::disk('public')->assertExists($award1->photo);
        Storage::disk('public')->assertExists($award2->photo);
    }

    public function test_new_awards_are_inserted_at_the_start_of_the_order()
    {
        $admin = $this->getAdminUser();
        $first = Award::create(['title' => 'First Award', 'order' => 0]);
        $second = Award::create(['title' => 'Second Award', 'order' => 1]);

        $response = $this->actingAs($admin)->post(route('admin.awards.store'), [
            'title' => 'Newest Award',
        ]);

        $response->assertRedirect(route('admin.awards.index'));
        $this->assertDatabaseHas('awards', ['title' => 'Newest Award', 'order' => 0]);
        $this->assertDatabaseHas('awards', ['id' => $first->id, 'order' => 1]);
        $this->assertDatabaseHas('awards', ['id' => $second->id, 'order' => 2]);
    }

    public function test_admin_can_update_an_award_without_deleting_it()
    {
        $admin = $this->getAdminUser();
        $award = Award::create(['title' => 'Original Award', 'order' => 0]);

        $response = $this->actingAs($admin)->put(route('admin.awards.update', $award), [
            'title' => 'Updated Award',
            'is_active' => '1',
        ]);

        $response->assertRedirect(route('admin.awards.index'));
        $this->assertDatabaseHas('awards', [
            'id' => $award->id,
            'title' => 'Updated Award',
        ]);
    }

    public function test_admin_can_delete_an_award()
    {
        $admin = $this->getAdminUser();
        $award = Award::create(['title' => 'Award To Delete', 'order' => 0]);

        $response = $this->actingAs($admin)->delete(route('admin.awards.destroy', $award));

        $response->assertRedirect(route('admin.awards.index'));
        $this->assertDatabaseMissing('awards', ['id' => $award->id]);
    }
}
