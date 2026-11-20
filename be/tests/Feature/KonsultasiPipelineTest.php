<?php

namespace Tests\Feature;

use App\Models\Consultation;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
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

        Permission::firstOrCreate(['name' => 'konsultasi.index']);
        $role = Role::firstOrCreate(['name' => 'super-admin']);
        $role->givePermissionTo('konsultasi.index');

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
                'handled_by' => $this->user->id,
            ])
            ->assertRedirect()
            ->assertSessionHas('success');

        $this->assertSame('New', $consultation->fresh()->lead_status);
        $this->assertSame($this->user->id, $consultation->fresh()->handled_by);
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
        ]);
    }
}
