<?php

namespace Tests\Feature;

use App\Models\AuditLog;
use App\Models\Package\Package;
use App\Models\Package\PackageOption;
use App\Models\Payment\Order;
use App\Models\Payment\WeddingChecklistItem;
use App\Models\User;
use App\Notifications\WeddingChecklistDueNotification;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Notification;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class WeddingChecklistReminderTest extends TestCase
{
    use RefreshDatabase;

    private User $member;

    private User $admin;

    private User $superAdmin;

    private Package $package;

    private PackageOption $option;

    private Order $order;

    protected function setUp(): void
    {
        parent::setUp();

        Carbon::setTestNow(Carbon::parse('2026-11-22 09:00:00'));

        foreach (['admin', 'super-admin', 'user'] as $role) {
            Role::firstOrCreate(['name' => $role, 'guard_name' => 'web']);
        }

        $this->member = User::factory()->create();
        $this->member->assignRole('user');

        $this->admin = User::factory()->create();
        $this->admin->assignRole('admin');

        $this->superAdmin = User::factory()->create();
        $this->superAdmin->assignRole('super-admin');

        $this->package = Package::create([
            'name' => 'Paket Checklist Test',
            'slug' => 'paket-checklist-'.uniqid(),
            'description' => 'Paket test pengingat checklist.',
            'price' => 10000000,
            'is_active' => 'active',
            'is_popular' => false,
        ]);
        $this->option = PackageOption::create([
            'package_uuid' => $this->package->uuid,
            'name' => 'Paket Utama',
            'price' => 10000000,
            'duration' => 1,
            'duration_unit' => 'month',
            'quota' => 1,
        ]);
        $this->order = Order::create([
            'user_uuid' => $this->member->uuid,
            'order_number' => 'ORD-CHECKLIST-'.strtoupper(uniqid()),
            'type' => 'package',
            'package_uuid' => $this->package->uuid,
            'package_option_uuid' => $this->option->uuid,
            'event_date' => today()->addMonths(6)->toDateString(),
            'amount' => 2000000,
            'payment_scheme' => 'dp30',
            'total_amount' => 10000000,
            'amount_paid' => 2000000,
            'remaining_amount' => 8000000,
            'status' => 'partially_paid',
            'expired_at' => now()->addHours(24),
            'addons' => [],
        ]);
    }

    protected function tearDown(): void
    {
        Carbon::setTestNow();

        parent::tearDown();
    }

    public function test_due_checklist_notifies_member_once_and_marks_reminder(): void
    {
        $item = WeddingChecklistItem::create([
            'order_uuid' => $this->order->uuid,
            'title' => 'Konfirmasi venue',
            'due_at' => today()->addDays(7)->toDateString(),
        ]);
        Notification::fake();

        $this->assertSame(0, Artisan::call('checklists:remind-due'));

        Notification::assertSentTo(
            $this->member,
            WeddingChecklistDueNotification::class,
            function (WeddingChecklistDueNotification $notification): bool {
                $data = $notification->toArray($this->member);

                return array_keys($data) === ['judul_kegiatan', 'message', 'path', 'icon']
                    && $data['path'] === route('orders.show', $this->order->uuid, false)
                    && str_contains($data['message'], 'Konfirmasi venue');
            }
        );
        Notification::assertSentToTimes($this->member, WeddingChecklistDueNotification::class, 1);
        Notification::assertNotSentTo($this->admin, WeddingChecklistDueNotification::class);
        Notification::assertNotSentTo($this->superAdmin, WeddingChecklistDueNotification::class);

        $this->assertNotNull($item->fresh()->reminder_sent_at);
        $this->assertNull($item->fresh()->escalation_sent_at);
        $this->assertSame(1, AuditLog::where('event', 'checklist_reminded')
            ->where('auditable_id', $item->uuid)
            ->count());

        $this->assertSame(0, Artisan::call('checklists:remind-due'));

        Notification::assertSentToTimes($this->member, WeddingChecklistDueNotification::class, 1);
        $this->assertSame(1, AuditLog::where('event', 'checklist_reminded')
            ->where('auditable_id', $item->uuid)
            ->count());
    }

    public function test_overdue_checklist_escalates_to_admins_and_marks_escalation(): void
    {
        $item = WeddingChecklistItem::create([
            'order_uuid' => $this->order->uuid,
            'title' => 'Finalisasi undangan',
            'due_at' => today()->subDay()->toDateString(),
        ]);
        Notification::fake();

        $this->assertSame(0, Artisan::call('checklists:remind-due'));

        Notification::assertSentToTimes($this->admin, WeddingChecklistDueNotification::class, 1);
        Notification::assertSentToTimes($this->superAdmin, WeddingChecklistDueNotification::class, 1);
        Notification::assertSentTo(
            $this->admin,
            WeddingChecklistDueNotification::class,
            function (WeddingChecklistDueNotification $notification): bool {
                $data = $notification->toArray($this->admin);

                return str_contains($data['judul_kegiatan'], 'Eskalasi')
                    && str_contains(strtolower($data['message']), 'admin')
                    && str_contains($data['message'], 'Finalisasi undangan');
            }
        );
        Notification::assertNotSentTo($this->member, WeddingChecklistDueNotification::class);

        $this->assertNotNull($item->fresh()->escalation_sent_at);
        $this->assertNull($item->fresh()->reminder_sent_at);
        $this->assertSame(1, AuditLog::where('event', 'checklist_escalated')
            ->where('auditable_id', $item->uuid)
            ->count());

        $this->assertSame(0, Artisan::call('checklists:remind-due'));

        Notification::assertSentToTimes($this->admin, WeddingChecklistDueNotification::class, 1);
        Notification::assertSentToTimes($this->superAdmin, WeddingChecklistDueNotification::class, 1);
    }

    public function test_overdue_checklist_escalates_to_assigned_admin_only(): void
    {
        $this->order->update(['assigned_admin_uuid' => $this->admin->uuid]);

        WeddingChecklistItem::create([
            'order_uuid' => $this->order->uuid,
            'title' => 'Konfirmasi katering',
            'due_at' => today()->subDays(2)->toDateString(),
        ]);
        Notification::fake();

        $this->assertSame(0, Artisan::call('checklists:remind-due'));

        Notification::assertSentToTimes($this->admin, WeddingChecklistDueNotification::class, 1);
        Notification::assertNotSentTo($this->superAdmin, WeddingChecklistDueNotification::class);
        Notification::assertNotSentTo($this->member, WeddingChecklistDueNotification::class);
    }

    public function test_completed_and_future_checklists_are_not_notified(): void
    {
        $completed = WeddingChecklistItem::create([
            'order_uuid' => $this->order->uuid,
            'title' => 'Sudah selesai',
            'due_at' => today()->toDateString(),
            'status' => 'completed',
        ]);
        $future = WeddingChecklistItem::create([
            'order_uuid' => $this->order->uuid,
            'title' => 'Terlalu jauh',
            'due_at' => today()->addDays(8)->toDateString(),
        ]);
        Notification::fake();

        $this->assertSame(0, Artisan::call('checklists:remind-due'));

        Notification::assertNothingSent();

        $this->assertNull($completed->fresh()->reminder_sent_at);
        $this->assertNull($completed->fresh()->escalation_sent_at);
        $this->assertNull($future->fresh()->reminder_sent_at);
        $this->assertNull($future->fresh()->escalation_sent_at);
        $this->assertSame(0, AuditLog::whereIn('event', ['checklist_reminded', 'checklist_escalated'])->count());
    }
}
