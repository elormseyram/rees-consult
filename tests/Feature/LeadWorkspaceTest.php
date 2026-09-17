<?php

namespace Tests\Feature;

use App\Models\CallRecording;
use App\Models\Lead;
use App\Models\LeadNote;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

/**
 * Lead notes + call recordings on the admin lead detail page.
 */
class LeadWorkspaceTest extends TestCase
{
    use RefreshDatabase;

    /** Reused within a test, so repeated calls must not collide on the unique email. */
    private function admin(): User
    {
        return User::firstOrCreate(
            ['email' => 'admin@example.com'],
            ['name' => 'Admin User', 'password' => 'secret123', 'role' => 'admin', 'is_active' => true]
        );
    }

    private function employee(): User
    {
        return User::firstOrCreate(
            ['email' => 'rep@example.com'],
            ['name' => 'Sales Rep', 'password' => 'secret123', 'role' => 'employee', 'is_active' => true]
        );
    }

    private function otherEmployee(): User
    {
        return User::firstOrCreate(
            ['email' => 'other@example.com'],
            ['name' => 'Other Rep', 'password' => 'secret123', 'role' => 'employee', 'is_active' => true]
        );
    }

    private function lead(): Lead
    {
        return Lead::create([
            'goal' => 'study_abroad', 'first_name' => 'Ada', 'last_name' => 'Okoro',
            'email' => 'ada@example.com', 'phone' => '+2348001112222', 'status' => 'new',
        ]);
    }

    private function audio(string $name = 'call.webm'): UploadedFile
    {
        return UploadedFile::fake()->createWithContent($name, str_repeat('x', 4096));
    }

    // ---------------------------------------------------------------- notes

    public function test_staff_can_add_a_note_to_a_lead(): void
    {
        $user = $this->employee();
        $lead = $this->lead();

        $this->actingAs($user)
            ->post(route('admin.leads.notes.store', $lead), ['body' => 'Discussed IELTS timeline.'])
            ->assertRedirect();

        $this->assertDatabaseHas('lead_notes', [
            'lead_id' => $lead->id,
            'user_id' => $user->id,
            'author_name' => 'Sales Rep',
            'body' => 'Discussed IELTS timeline.',
        ]);
    }

    public function test_note_body_is_required(): void
    {
        $this->actingAs($this->employee())
            ->post(route('admin.leads.notes.store', $this->lead()), ['body' => ''])
            ->assertSessionHasErrors('body');
    }

    public function test_notes_appear_on_the_lead_page(): void
    {
        $lead = $this->lead();
        $lead->noteEntries()->create(['author_name' => 'Sales Rep', 'body' => 'Wants a September start.']);

        $this->actingAs($this->admin())
            ->get(route('admin.leads.show', $lead))
            ->assertOk()
            ->assertSee('Wants a September start.')
            ->assertSee('Sales Rep');
    }

    public function test_employee_cannot_delete_another_persons_note(): void
    {
        $author = $this->employee();
        $other  = $this->otherEmployee();
        $lead = $this->lead();
        $note = $lead->noteEntries()->create(['user_id' => $author->id, 'author_name' => 'Sales Rep', 'body' => 'Private']);

        $this->actingAs($other)
            ->delete(route('admin.leads.notes.destroy', [$lead, $note]))
            ->assertForbidden();

        $this->assertDatabaseHas('lead_notes', ['id' => $note->id]);
    }

    public function test_admin_can_delete_any_note(): void
    {
        $lead = $this->lead();
        $note = $lead->noteEntries()->create([
            'user_id' => $this->employee()->id, 'author_name' => 'Sales Rep', 'body' => 'Anything',
        ]);

        $this->actingAs($this->admin())
            ->delete(route('admin.leads.notes.destroy', [$lead, $note]))
            ->assertRedirect();

        $this->assertDatabaseMissing('lead_notes', ['id' => $note->id]);
    }

    public function test_notes_relation_does_not_shadow_the_legacy_notes_column(): void
    {
        $lead = $this->lead();
        $lead->update(['notes' => 'legacy single note']);
        $lead->noteEntries()->create(['author_name' => 'Rep', 'body' => 'timeline entry']);

        $fresh = $lead->fresh();
        $this->assertSame('legacy single note', $fresh->notes);
        $this->assertCount(1, $fresh->noteEntries);
    }

    // ----------------------------------------------------------- recordings

    public function test_staff_can_upload_a_call_recording(): void
    {
        Storage::fake('local');
        $user = $this->employee();
        $lead = $this->lead();

        $this->actingAs($user)
            ->post(route('admin.leads.recordings.store', $lead), [
                'audio'    => $this->audio(),
                'duration' => 247,
                'consent'  => '1',
                'summary'  => 'Agreed to start in September',
            ])
            ->assertOk()
            ->assertJsonStructure(['message', 'id']);

        $recording = CallRecording::first();
        $this->assertNotNull($recording);
        $this->assertSame($lead->id, $recording->lead_id);
        $this->assertSame($user->id, $recording->user_id);
        $this->assertSame(247, $recording->duration_seconds);
        $this->assertTrue($recording->consent_confirmed);
        $this->assertNotNull($recording->consent_confirmed_at);
        $this->assertSame('Agreed to start in September', $recording->summary);

        // Stored on the private disk, never the public one.
        Storage::disk('local')->assertExists($recording->path);
        $this->assertStringStartsWith("call-recordings/{$lead->id}/", $recording->path);
    }

    public function test_recording_is_rejected_without_consent(): void
    {
        Storage::fake('local');

        $this->actingAs($this->employee())
            ->post(route('admin.leads.recordings.store', $this->lead()), [
                'audio' => $this->audio(), 'duration' => 30, 'consent' => '0',
            ])
            ->assertSessionHasErrors('consent');

        $this->assertSame(0, CallRecording::count());
    }

    public function test_recording_requires_an_audio_file(): void
    {
        $this->actingAs($this->employee())
            ->post(route('admin.leads.recordings.store', $this->lead()), ['consent' => '1', 'duration' => 5])
            ->assertSessionHasErrors('audio');
    }

    public function test_recording_can_be_streamed_by_staff(): void
    {
        Storage::fake('local');
        $lead = $this->lead();

        $this->actingAs($this->employee())->post(route('admin.leads.recordings.store', $lead), [
            'audio' => $this->audio(), 'duration' => 10, 'consent' => '1',
        ]);

        $recording = CallRecording::first();

        $this->actingAs($this->employee())
            ->get(route('admin.leads.recordings.stream', [$lead, $recording]))
            ->assertOk();
    }

    public function test_recordings_are_not_reachable_without_logging_in(): void
    {
        Storage::fake('local');
        $lead = $this->lead();

        // Built directly rather than via an authenticated POST — actingAs()
        // persists for the rest of the test and would defeat the guest check.
        Storage::disk('local')->put("call-recordings/{$lead->id}/x.webm", 'audio');
        $recording = $lead->recordings()->create([
            'user_id' => $this->employee()->id, 'recorded_by' => 'Sales Rep',
            'disk' => 'local', 'path' => "call-recordings/{$lead->id}/x.webm",
            'mime_type' => 'audio/webm', 'duration_seconds' => 10, 'size_bytes' => 5,
            'consent_confirmed' => true, 'consent_confirmed_at' => now(),
        ]);

        // Guest is bounced to login rather than served the audio.
        $this->get(route('admin.leads.recordings.stream', [$lead, $recording]))
            ->assertRedirect(route('login'));

        $this->get(route('admin.leads.recordings.download', [$lead, $recording]))
            ->assertRedirect(route('login'));

        $this->get(route('admin.leads.show', $lead))->assertRedirect(route('login'));
    }

    public function test_recording_cannot_be_read_across_leads(): void
    {
        Storage::fake('local');
        $leadA = $this->lead();
        $leadB = Lead::create([
            'goal' => 'test_prep', 'first_name' => 'Bola', 'last_name' => 'Ade',
            'email' => 'bola@example.com', 'phone' => '+2348009998888',
        ]);

        $this->actingAs($this->employee())->post(route('admin.leads.recordings.store', $leadA), [
            'audio' => $this->audio(), 'duration' => 10, 'consent' => '1',
        ]);

        $recording = CallRecording::first();

        // Recording belongs to lead A — requesting it under lead B must 404.
        $this->actingAs($this->employee())
            ->get(route('admin.leads.recordings.stream', [$leadB, $recording]))
            ->assertNotFound();
    }

    public function test_employee_cannot_delete_another_persons_recording(): void
    {
        Storage::fake('local');
        $lead = $this->lead();

        $this->actingAs($this->employee())->post(route('admin.leads.recordings.store', $lead), [
            'audio' => $this->audio(), 'duration' => 10, 'consent' => '1',
        ]);

        $recording = CallRecording::first();

        $this->actingAs($this->otherEmployee())
            ->delete(route('admin.leads.recordings.destroy', [$lead, $recording]))
            ->assertForbidden();

        $this->assertDatabaseHas('call_recordings', ['id' => $recording->id]);
    }

    public function test_deleting_a_recording_removes_the_audio_file(): void
    {
        Storage::fake('local');
        $lead = $this->lead();

        $this->actingAs($this->employee())->post(route('admin.leads.recordings.store', $lead), [
            'audio' => $this->audio(), 'duration' => 10, 'consent' => '1',
        ]);

        $recording = CallRecording::first();
        $path = $recording->path;
        Storage::disk('local')->assertExists($path);

        $this->actingAs($this->admin())
            ->delete(route('admin.leads.recordings.destroy', [$lead, $recording]))
            ->assertRedirect();

        Storage::disk('local')->assertMissing($path);
        $this->assertSame(0, CallRecording::count());
    }

    public function test_deleting_a_lead_cascades_to_notes_and_recordings(): void
    {
        Storage::fake('local');
        $lead = $this->lead();
        $lead->noteEntries()->create(['author_name' => 'Rep', 'body' => 'note']);

        $this->actingAs($this->employee())->post(route('admin.leads.recordings.store', $lead), [
            'audio' => $this->audio(), 'duration' => 10, 'consent' => '1',
        ]);

        $this->actingAs($this->admin())->delete(route('admin.leads.destroy', $lead));

        $this->assertSame(0, LeadNote::count());
        $this->assertSame(0, CallRecording::count());
    }

    public function test_lead_page_renders_the_recorder_and_consent_gate(): void
    {
        $this->actingAs($this->employee())
            ->get(route('admin.leads.show', $this->lead()))
            ->assertOk()
            ->assertSee('recorder-panel')
            ->assertSee('consentModal')
            ->assertSee('MediaRecorder', false);
    }

    // ------------------------------------------------- animated rating badge

    public function test_hot_lead_shows_the_animated_flame_and_row_accent(): void
    {
        $this->lead()->update(['rating' => 'HOT']);

        $this->actingAs($this->employee())
            ->get(route('admin.leads.index'))
            ->assertOk()
            ->assertSee('rb-flame-back', false)
            ->assertSee('rb-flame-front', false)
            ->assertSee('class="lead-hot"', false)
            ->assertSee('HOT', false);
    }

    public function test_cold_lead_shows_the_animated_frost_and_row_accent(): void
    {
        $this->lead()->update(['rating' => 'COLD']);

        $this->actingAs($this->employee())
            ->get(route('admin.leads.index'))
            ->assertOk()
            ->assertSee('bi-snow rb-ice', false)
            ->assertSee('class="lead-cold"', false)
            ->assertSee('COLD', false);
    }

    public function test_warm_lead_is_deliberately_not_animated(): void
    {
        $this->lead()->update(['rating' => 'WARM']);

        $html = $this->actingAs($this->employee())
            ->get(route('admin.leads.index'))->assertOk()->getContent();

        // The stat chips always render one badge of each kind; the table row
        // itself must carry neither the hot nor the cold accent.
        $this->assertStringNotContainsString('class="lead-hot"', $html);
        $this->assertStringNotContainsString('class="lead-cold"', $html);
        $this->assertStringContainsString('rb-warm', $html);
    }

    /** Motion must never be the only carrier of meaning. */
    public function test_rating_badges_respect_reduced_motion_and_keep_their_label(): void
    {
        $this->lead()->update(['rating' => 'HOT']);

        $html = $this->actingAs($this->employee())
            ->get(route('admin.leads.index'))->assertOk()->getContent();

        $this->assertStringContainsString('prefers-reduced-motion: reduce', $html);
        $this->assertStringContainsString('aria-hidden="true"', $html);
        $this->assertMatchesRegularExpression('/>\s*HOT\s*</', $html, 'the rating word must be readable text');
    }

    /** The shared stylesheet ships once per page, not once per row. */
    public function test_badge_styles_are_emitted_only_once_for_many_rows(): void
    {
        for ($i = 0; $i < 12; $i++) {
            Lead::create([
                'goal' => 'test_prep', 'first_name' => "Lead{$i}", 'last_name' => 'X',
                'email' => "lead{$i}@example.com", 'phone' => '+2348000000000',
                'rating' => $i % 2 ? 'HOT' : 'COLD',
            ]);
        }

        $html = $this->actingAs($this->employee())
            ->get(route('admin.leads.index'))->assertOk()->getContent();

        $this->assertSame(1, substr_count($html, '.rating-badge {'));
    }

    public function test_detail_page_uses_the_large_badge_variant(): void
    {
        $lead = $this->lead();
        $lead->update(['rating' => 'HOT']);

        $this->actingAs($this->employee())
            ->get(route('admin.leads.show', $lead))
            ->assertOk()
            ->assertSee('rb-lg', false)
            ->assertSee('rb-flame-front', false);
    }

    public function test_unrated_lead_falls_back_to_a_neutral_badge(): void
    {
        $this->lead()->update(['rating' => 'NEW']);

        $this->actingAs($this->employee())
            ->get(route('admin.leads.index'))
            ->assertOk()
            ->assertSee('rb-none', false);
    }
}
