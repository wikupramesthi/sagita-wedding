<?php

namespace Tests\Feature;

use App\Exports\ConsultationExport;
use App\Models\Consultation;
use App\Models\KonsultasiSavedFilter;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class KonsultasiPipelineTest extends TestCase
{
    use RefreshDatabase;

    private User $user;

    protected function setUp(): void
    {
        parent::setUp();

        $permissions = [
            'konsultasi.index',
            'konsultasi.bulk',
            'konsultasi.delete',
            'konsultasi.export',
            'konsultasi.import',
            'konsultasi.merge',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        $role = Role::firstOrCreate(['name' => 'super-admin']);
        $role->givePermissionTo($permissions);

        $this->user = User::factory()->create([
            'email_verified_at' => now(),
        ]);
        $this->user->assignRole($role);
    }

    public function test_all_konsultasi_pages_and_actions_work(): void
    {
        Notification::fake();

        $primary = $this->makeConsultation('Primary Lead', 'primary@example.test', '081111111111', 'New');
        $duplicate = $this->makeConsultation('Duplicate Lead', 'primary@example.test', '081111111111', 'Contacted');
        $overdue = $this->makeConsultation('Overdue Lead', 'overdue@example.test', '082222222222', 'Contacted');
        $overdue->update([
            'follow_up_at' => now()->subDay(),
            'reminder_sent_at' => null,
        ]);

        foreach ([
            route('konsultasi.index'),
            route('konsultasi.index', ['lead' => 'New']),
            route('konsultasi.kanban'),
            route('konsultasi.calendar', ['year' => now()->year, 'month' => now()->month]),
            route('konsultasi.duplicates'),
            route('konsultasi.pdf-preview'),
            route('konsultasi.pdf-stream'),
            route('konsultasi.pdf-download'),
            route('konsultasi.show', $primary),
            route('konsultasi.edit', $primary),
            route('konsultasi.create'),
        ] as $url) {
            $this->actingAs($this->user)->get($url)->assertOk();
        }

        $this->actingAs($this->user)
            ->get(route('konsultasi.export', ['source' => 'website']))
            ->assertOk();

        $this->actingAs($this->user)
            ->post(route('konsultasi.bulk'), [
                'ids' => [$primary->uuid],
                'action' => 'lead',
                'lead_status' => 'Interested',
            ])
            ->assertRedirect();

        $this->assertSame('Interested', $primary->fresh()->lead_status);

        $this->actingAs($this->user)
            ->put(route('konsultasi.update-lead', $primary), ['lead_status' => 'Negotiation'])
            ->assertRedirect();

        $this->assertSame('Negotiation', $primary->fresh()->lead_status);

        $this->assertDatabaseHas('audit_logs', [
            'auditable_type' => Consultation::class,
            'auditable_id' => $primary->uuid,
            'event' => 'updated',
            'user_uuid' => $this->user->uuid,
            'new_values->lead_status' => 'Negotiation',
        ]);

        $this->actingAs($this->user)
            ->get(route('konsultasi.show', $primary))
            ->assertOk()
            ->assertSee('Riwayat Perubahan')
            ->assertSee('Riwayat Lead')
            ->assertSee('Perjalanan calon pelanggan')
            ->assertSee('Langkah berikutnya')
            ->assertSee('Status Calon Pelanggan');

        $this->actingAs($this->user)
            ->post(route('konsultasi.remind', $overdue))
            ->assertRedirect();

        $this->assertNotNull($overdue->fresh()->reminder_sent_at);

        $this->actingAs($this->user)
            ->post(route('konsultasi.merge'), [
                'primary_uuid' => $primary->uuid,
                'duplicate_uuid' => $duplicate->uuid,
            ])
            ->assertRedirect(route('konsultasi.duplicates'));

        $this->assertNotNull($duplicate->fresh()->merged_into_uuid);
        $this->assertNull(Consultation::find($duplicate->uuid));

        $this->actingAs($this->user)
            ->post(route('konsultasi.merge'), [
                'primary_uuid' => $primary->uuid,
                'duplicate_uuid' => $duplicate->uuid,
            ])
            ->assertRedirect()
            ->assertSessionHas('error');

        $this->actingAs($this->user)
            ->post(route('konsultasi.store'), [
                'full_name' => 'Blocked Duplicate',
                'whatsapp' => '081111111111',
                'email' => 'primary@example.test',
                'consultation_status' => 'Pending',
                'lead_status' => 'New',
                'source' => 'website',
            ])
            ->assertSessionHas('error');

        $this->assertDatabaseCount('consultations', 3);

        $this->artisan('consultations:remind-followups')->assertSuccessful();
    }

    public function test_update_lead_allows_pic_only_update(): void
    {
        $consultation = $this->makeConsultation('PIC Only', 'pic@example.test', '081333333333', 'New');

        $this->actingAs($this->user)
            ->put(route('konsultasi.update-lead', $consultation), [
                'handled_by' => $this->user->fresh()->id,
            ])
            ->assertRedirect()
            ->assertSessionHas('success');

        $this->assertSame('New', $consultation->fresh()->lead_status);
        $this->assertSame($this->user->fresh()->id, $consultation->fresh()->handled_by);
    }

    public function test_failed_reminder_does_not_mark_as_sent(): void
    {
        $consultation = $this->makeConsultation('Reminder Gagal', 'reminder@example.test', '081444444444', 'New');
        $consultation->update(['follow_up_at' => now()->subDay()]);

        Notification::shouldReceive('send')
            ->once()
            ->andThrow(new \RuntimeException('mail down'));

        $this->artisan('consultations:remind-followups')->assertSuccessful();

        $this->assertNull($consultation->fresh()->reminder_sent_at);
    }

    public function test_export_escapes_formula_like_values(): void
    {
        $this->assertSame("'=SUM(A1)", ConsultationExport::escape('=SUM(A1)'));
        $this->assertSame("'+cmd", ConsultationExport::escape('+cmd'));
        $this->assertSame('Rani & Dimas', ConsultationExport::escape('Rani & Dimas'));
    }

    public function test_sla_state_and_filter_work(): void
    {
        $breach = $this->makeConsultation('SLA Late', 'sla-late@example.test', '081611111111', 'New');
        $warning = $this->makeConsultation('SLA Soon', 'sla-soon@example.test', '081622222222', 'New');
        $safe = $this->makeConsultation('SLA Safe', 'sla-safe@example.test', '081633333333', 'New');

        Consultation::whereKey($breach->uuid)->update(['created_at' => now()->subHours(25)]);
        Consultation::whereKey($warning->uuid)->update(['created_at' => now()->subHours(12)]);

        $this->assertSame('breach', $breach->fresh()->sla_state);
        $this->assertSame('warning', $warning->fresh()->sla_state);
        $this->assertSame('safe', $safe->fresh()->sla_state);

        $this->actingAs($this->user)
            ->get(route('konsultasi.index', ['sla' => 'breach']))
            ->assertOk()
            ->assertSee('SLA Late')
            ->assertDontSee('SLA Safe');
    }

    public function test_store_auto_assigns_least_busy_admin(): void
    {
        $other = User::factory()->create(['email_verified_at' => now()]);
        $other->assignRole('super-admin');

        $busyUserId = $this->user->fresh()->id;
        $otherUserId = $other->fresh()->id;

        $busyOne = $this->makeConsultation('Busy One', 'busy-one@example.test', '081711111111', 'New');
        $busyOne->update(['handled_by' => $busyUserId]);
        $busyTwo = $this->makeConsultation('Busy Two', 'busy-two@example.test', '081722222222', 'New');
        $busyTwo->update(['handled_by' => $busyUserId]);

        $this->actingAs($this->user)
            ->post(route('konsultasi.store'), [
                'full_name' => 'Auto Assigned',
                'whatsapp' => '081899999999',
                'email' => 'auto-assigned@example.test',
                'consultation_status' => 'Pending',
                'lead_status' => 'New',
                'source' => 'website',
            ])
            ->assertRedirect();

        $created = Consultation::where('email', 'auto-assigned@example.test')->firstOrFail();

        $this->assertSame($otherUserId, $created->handled_by);
    }

    public function test_import_preview_and_commit(): void
    {
        $csv = "Nama Calon Pelanggan,WhatsApp,Email,Informasi dari mana,Status Calon Pelanggan\n"
            ."Imported Lead,081555555555,imported@example.test,Google,Baru\n";

        $file = UploadedFile::fake()->createWithContent('leads.csv', $csv)->mimeType('text/csv');

        $this->actingAs($this->user)
            ->post(route('konsultasi.import.preview'), ['file' => $file])
            ->assertRedirect(route('konsultasi.import'));

        $this->actingAs($this->user)
            ->get(route('konsultasi.import'))
            ->assertOk()
            ->assertSee('Imported Lead')
            ->assertSee('Siap');

        $token = session('konsultasi_import_token');

        $this->actingAs($this->user)
            ->post(route('konsultasi.import.commit'), ['token' => $token])
            ->assertRedirect();

        $this->assertDatabaseHas('consultations', [
            'full_name' => 'Imported Lead',
            'email' => 'imported@example.test',
            'source' => 'google',
            'lead_status' => 'New',
        ]);
    }

    public function test_whatsapp_click_is_logged_and_ics_can_be_downloaded(): void
    {
        $consultation = $this->makeConsultation('WA Lead', 'wa@example.test', '082011111111', 'New');

        $this->actingAs($this->user)
            ->get(route('konsultasi.whatsapp', $consultation))
            ->assertRedirect();

        $this->assertDatabaseHas('audit_logs', [
            'auditable_type' => Consultation::class,
            'auditable_id' => $consultation->uuid,
            'event' => 'whatsapp_opened',
        ]);

        $this->assertStringContainsString('calendar.google.com', $consultation->google_calendar_url);

        $this->actingAs($this->user)
            ->get(route('konsultasi.calendar-ics', $consultation))
            ->assertOk()
            ->assertSee('BEGIN:VCALENDAR')
            ->assertSee('Konsultasi Sagita Wedding');
    }

    public function test_kanban_reorder_changes_sort_order(): void
    {
        $first = $this->makeConsultation('Kanban First', 'kanban-first@example.test', '081911111111', 'New');
        $second = $this->makeConsultation('Kanban Second', 'kanban-second@example.test', '081922222222', 'New');

        $this->actingAs($this->user)
            ->get(route('konsultasi.kanban'))
            ->assertOk()
            ->assertSee('WIP maksimal 20');

        $this->actingAs($this->user)
            ->post(route('konsultasi.reorder', $second), ['direction' => 'up'])
            ->assertRedirect();

        $this->assertLessThan($first->fresh()->sort_order, $second->fresh()->sort_order);
        $this->assertSame(
            [0, 1],
            Consultation::where('lead_status', 'New')->orderBy('sort_order')->pluck('sort_order')->all()
        );
    }

    public function test_wip_limit_blocks_single_and_bulk_moves_into_full_column(): void
    {
        for ($i = 1; $i <= 20; $i++) {
            $this->makeConsultation('WIP New '.$i, 'wip-new-'.$i.'@example.test', '0899000'.str_pad((string) $i, 4, '0', STR_PAD_LEFT), 'New');
        }

        $mover = $this->makeConsultation('WIP Mover', 'wip-mover@example.test', '089900099991', 'Contacted');

        $this->actingAs($this->user)
            ->put(route('konsultasi.update-lead', $mover), ['lead_status' => 'New'])
            ->assertRedirect()
            ->assertSessionHasErrors('lead_status');

        $this->assertSame('Contacted', $mover->fresh()->lead_status);

        for ($i = 1; $i <= 14; $i++) {
            $this->makeConsultation('WIP Interest '.$i, 'wip-interest-'.$i.'@example.test', '0898000'.str_pad((string) $i, 4, '0', STR_PAD_LEFT), 'Interested');
        }

        $first = $this->makeConsultation('WIP Bulk One', 'wip-bulk-one@example.test', '089700000001', 'New');
        $second = $this->makeConsultation('WIP Bulk Two', 'wip-bulk-two@example.test', '089700000002', 'New');

        $this->actingAs($this->user)
            ->post(route('konsultasi.bulk'), [
                'ids' => [$first->uuid, $second->uuid],
                'action' => 'lead',
                'lead_status' => 'Interested',
            ])
            ->assertRedirect()
            ->assertSessionHasErrors('lead_status');

        $this->assertSame('New', $first->fresh()->lead_status);
        $this->assertSame('New', $second->fresh()->lead_status);
    }

    public function test_soft_deleted_lead_releases_dedupe_keys(): void
    {
        $deleted = $this->makeConsultation('Deleted Lead', 'reuse@example.test', '081399999999', 'Lost');
        $deleted->delete();

        $trashed = Consultation::withTrashed()->findOrFail($deleted->uuid);
        $this->assertNull($trashed->email_dedupe_key);
        $this->assertNull($trashed->whatsapp_dedupe_key);

        $this->actingAs($this->user)
            ->post(route('konsultasi.store'), [
                'full_name' => 'Reused Lead',
                'whatsapp' => '081399999999',
                'email' => 'reuse@example.test',
                'consultation_status' => 'Pending',
                'lead_status' => 'New',
                'source' => 'website',
            ])
            ->assertRedirect()
            ->assertSessionHas('success');

        $this->assertDatabaseHas('consultations', ['full_name' => 'Reused Lead']);
    }

    public function test_saved_filters_can_be_stored_and_applied(): void
    {
        $this->actingAs($this->user)
            ->post(route('konsultasi.saved-filters.store'), [
                'name' => 'Lead Baru',
                'filters' => json_encode(['lead' => 'New']),
            ])
            ->assertRedirect();

        $saved = KonsultasiSavedFilter::firstOrFail();

        $this->assertSame('Lead Baru', $saved->name);
        $this->assertSame('New', $saved->filters['lead']);

        $this->actingAs($this->user)
            ->get(route('konsultasi.saved-filters.apply', $saved))
            ->assertRedirect(route('konsultasi.index', ['lead' => 'New']));

        $this->actingAs($this->user)
            ->delete(route('konsultasi.saved-filters.destroy', $saved))
            ->assertRedirect();

        $this->assertDatabaseMissing('konsultasi_saved_filters', ['id' => $saved->id]);
    }

    public function test_default_saved_filter_is_applied_and_can_be_disabled(): void
    {
        $this->actingAs($this->user)
            ->post(route('konsultasi.saved-filters.store'), [
                'name' => 'Lead Baru Default',
                'filters' => json_encode(['lead' => 'New']),
                'is_default' => 1,
            ])
            ->assertRedirect();

        $saved = KonsultasiSavedFilter::firstOrFail();

        $this->assertTrue($saved->is_default);

        $this->actingAs($this->user)
            ->get(route('konsultasi.index'))
            ->assertRedirect(route('konsultasi.index', ['lead' => 'New']));

        $this->actingAs($this->user)
            ->get(route('konsultasi.index', ['lead' => 'New']))
            ->assertOk()
            ->assertSee('Filter aktif')
            ->assertSee('Lead:');

        $this->actingAs($this->user)
            ->post(route('konsultasi.saved-filters.default', $saved), ['is_default' => 0])
            ->assertRedirect();

        $this->assertFalse($saved->fresh()->is_default);

        $this->actingAs($this->user)
            ->get(route('konsultasi.index'))
            ->assertOk();
    }

    public function test_bulk_actions_can_be_undone(): void
    {
        $lead = $this->makeConsultation('Undo Lead', 'undo@example.test', '081666666666', 'New');
        $deleted = $this->makeConsultation('Undo Delete', 'undo-delete@example.test', '081777777777', 'New');

        $response = $this->actingAs($this->user)->post(route('konsultasi.bulk'), [
            'ids' => [$lead->uuid],
            'action' => 'lead',
            'lead_status' => 'Interested',
        ]);

        $response->assertRedirect()->assertSessionHas('undo_token');
        $token = $response->getSession()->get('undo_token');
        $this->assertSame('Interested', $lead->fresh()->lead_status);

        $this->actingAs($this->user)
            ->post(route('konsultasi.bulk-undo'), ['undo_token' => $token])
            ->assertRedirect();

        $this->assertSame('New', $lead->fresh()->lead_status);

        $response = $this->actingAs($this->user)->post(route('konsultasi.bulk'), [
            'ids' => [$deleted->uuid],
            'action' => 'delete',
        ]);

        $response->assertRedirect()->assertSessionHas('undo_token');
        $token = $response->getSession()->get('undo_token');
        $this->assertSoftDeleted('consultations', ['uuid' => $deleted->uuid]);

        $this->actingAs($this->user)
            ->post(route('konsultasi.bulk-undo'), ['undo_token' => $token])
            ->assertRedirect();

        $this->assertDatabaseHas('consultations', [
            'uuid' => $deleted->uuid,
            'deleted_at' => null,
        ]);
    }

    public function test_granular_permissions_guard_export_and_delete(): void
    {
        $consultation = $this->makeConsultation('Limited', 'limited@example.test', '081555555555', 'New');
        $role = Role::create(['name' => 'konsultasi-viewer']);
        $role->givePermissionTo('konsultasi.index');

        $user = User::factory()->create(['email_verified_at' => now()]);
        $user->assignRole($role);

        $this->actingAs($user)
            ->get(route('konsultasi.export'))
            ->assertNotFound();

        $this->actingAs($user)
            ->delete(route('konsultasi.destroy', $consultation))
            ->assertNotFound();

        $this->assertNull($consultation->fresh()->deleted_at);
    }

    private function makeConsultation(string $name, string $email, string $whatsapp, string $leadStatus): Consultation
    {
        return Consultation::forceCreate([
            'uuid' => (string) Str::uuid(),
            'full_name' => $name,
            'email' => $email,
            'whatsapp' => $whatsapp,
            'source' => 'website',
            'consultation_date' => today()->toDateString(),
            'consultation_time' => '10:00:00',
            'consultation_status' => 'Confirmed',
            'lead_status' => $leadStatus,
            'email_dedupe_key' => null,
            'whatsapp_dedupe_key' => null,
        ]);
    }
}
