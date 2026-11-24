<?php

namespace Tests\Feature;

use App\Models\Package\CustomPackageOffer;
use App\Models\Package\CustomPackageOfferRequest;
use App\Models\Payment\Order;
use App\Models\Payment\OrderDocument;
use App\Models\Payment\Payment;
use App\Models\User;
use App\Models\UserPackage;
use App\Notifications\CustomPackageOfferNotification;
use App\Notifications\CustomPackageRequestNotification;
use Database\Seeders\RouteSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Notification;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class CustomPackageOfferFeatureTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;

    private User $member;

    private User $otherMember;

    protected function setUp(): void
    {
        parent::setUp();

        foreach (['user', 'admin', 'super-admin'] as $roleName) {
            Role::findOrCreate($roleName, 'web');
        }

        $this->seed(RouteSeeder::class);
        Cache::flush();

        $adminRole = Role::findByName('admin', 'web');
        foreach (['package.index', 'package.store', 'package.update', 'package.member', 'orders.index', 'orders.show', 'orders.approve'] as $permission) {
            $adminRole->givePermissionTo(Permission::firstOrCreate([
                'name' => $permission,
                'guard_name' => 'web',
            ]));
        }

        $userRole = Role::findByName('user', 'web');
        foreach (['package.member', 'orders.show'] as $permission) {
            $userRole->givePermissionTo(Permission::firstOrCreate([
                'name' => $permission,
                'guard_name' => 'web',
            ]));
        }

        $this->admin = User::factory()->create(['email_verified_at' => now()]);
        $this->admin->assignRole($adminRole);

        $this->member = User::factory()->create(['email_verified_at' => now()]);
        $this->member->assignRole($userRole);

        $this->otherMember = User::factory()->create(['email_verified_at' => now()]);
        $this->otherMember->assignRole($userRole);
    }

    public function test_private_offer_notifies_only_its_recipient_and_can_continue_to_checkout(): void
    {
        Notification::fake();

        $this->actingAs($this->admin)
            ->get(route('package-offers.create'))
            ->assertOk()
            ->assertSee('Buat Penawaran Khusus')
            ->assertSee('Pelanggan baru menerima penawaran');

        $this->actingAs($this->admin)
            ->post(route('package-offers.store'), $this->offerPayload())
            ->assertRedirect();

        $offer = CustomPackageOffer::with('items')->firstOrFail();
        $this->assertSame('draft', $offer->status);
        $this->assertNull($offer->sent_at);
        Notification::assertNotSentTo($this->member, CustomPackageOfferNotification::class);
        $this->actingAs($this->admin)
            ->get(route('package-offers.show', $offer->uuid))
            ->assertOk()
            ->assertSee('Draf — hanya terlihat admin');
        $this->actingAs($this->member)
            ->get(route('package-offers.show', $offer->uuid))
            ->assertNotFound();

        $this->actingAs($this->admin)
            ->post(route('package-offers.send', $offer->uuid))
            ->assertRedirect();
        $offer->refresh();
        $this->assertEqualsWithDelta(7 * 24 * 60 * 60, $offer->sent_at->diffInSeconds($offer->expires_at), 2);
        Notification::assertSentTo($this->member, CustomPackageOfferNotification::class);
        Notification::assertNotSentTo($this->otherMember, CustomPackageOfferNotification::class);

        $this->actingAs($this->otherMember)
            ->get(route('package-offers.show', $offer->uuid))
            ->assertNotFound();

        $this->actingAs($this->member)
            ->get(route('package-offers.show', $offer->uuid))
            ->assertOk()
            ->assertSee($offer->title)
            ->assertDontSee('Catatan internal');

        $this->actingAs($this->member)
            ->post(route('package-offers.respond', $offer->uuid), ['action' => 'accept'])
            ->assertRedirect(route('package-offers.checkout', $offer->uuid));

        $response = $this->actingAs($this->member)
            ->post(route('package-offers.checkout.store', $offer->uuid))
            ->assertRedirect();

        $order = Order::firstOrFail();
        $response->assertRedirect(route('orders.show', $order->uuid));
        $this->assertSame($offer->uuid, $order->custom_package_offer_uuid);
        $this->assertNull($order->package_option_uuid);
        $this->assertSame('pending', $order->status);
        $this->assertSame(300000, (int) $order->amount);
        $this->assertDatabaseHas('event_date_reservations', [
            'event_date' => $offer->event_date->toDateString(),
            'order_uuid' => $order->uuid,
            'status' => 'held',
        ]);

        $this->actingAs($this->member)
            ->get(route('orders.show', $order->uuid))
            ->assertOk()
            ->assertSee($offer->title)
            ->assertSee('Rincian layanan')
            ->assertSee('Layanan wedding organizer');

        $this->actingAs($this->admin)
            ->get(route('orders.index'))
            ->assertOk()
            ->assertSee($offer->title)
            ->assertSee('Penawaran khusus');
    }

    public function test_guest_is_sent_to_login_instead_of_receiving_a_not_found_page(): void
    {
        $this->get(route('package-offers.mine'))
            ->assertRedirect(route('login'));
        $this->get(route('package-offer-requests.mine'))
            ->assertRedirect(route('login'));
    }

    public function test_admin_is_redirected_to_the_request_queue_from_the_member_request_list(): void
    {
        $this->actingAs($this->admin)
            ->get(route('package-offer-requests.mine'))
            ->assertRedirect(route('package-offer-requests.index'));
    }

    public function test_member_package_catalog_shows_only_their_current_sent_offer(): void
    {
        $this->actingAs($this->admin)
            ->post(route('package-offers.store'), $this->offerPayload())
            ->assertRedirect();

        $firstVersion = CustomPackageOffer::firstOrFail();
        $this->actingAs($this->admin)
            ->post(route('package-offers.send', $firstVersion->uuid))
            ->assertRedirect();

        $this->actingAs($this->member)
            ->post(route('package-offers.respond', $firstVersion->uuid), [
                'action' => 'request_revision',
                'revision_note' => 'Mohon sesuaikan pilihan layanan.',
            ])
            ->assertRedirect();

        $this->actingAs($this->admin)
            ->post(route('package-offers.revise', $firstVersion->uuid), $this->offerPayload([
                'title' => 'Penawaran Revisi Eksklusif',
            ]))
            ->assertRedirect();

        $draftVersion = CustomPackageOffer::where('thread_uuid', $firstVersion->thread_uuid)
            ->where('version', 2)
            ->firstOrFail();

        $this->actingAs($this->member)
            ->get(route('packages.member'))
            ->assertOk()
            ->assertSee($firstVersion->title)
            ->assertDontSee($draftVersion->title)
            ->assertSee('Lihat status revisi');

        $this->actingAs($this->admin)
            ->post(route('package-offers.send', $draftVersion->uuid))
            ->assertRedirect();

        $this->actingAs($this->admin)
            ->post(route('package-offers.store'), $this->offerPayload([
                'user_uuid' => $this->otherMember->uuid,
                'title' => 'Penawaran Rahasia Pelanggan Lain',
            ]))
            ->assertRedirect();

        $otherOffer = CustomPackageOffer::where('user_uuid', $this->otherMember->uuid)->firstOrFail();
        $this->actingAs($this->admin)
            ->post(route('package-offers.send', $otherOffer->uuid))
            ->assertRedirect();

        $this->actingAs($this->member)
            ->get(route('packages.member'))
            ->assertOk()
            ->assertSee($draftVersion->title)
            ->assertDontSee('<h3 class="h5 fw-bold mb-1">'.$firstVersion->title.'</h3>', false)
            ->assertDontSee('Penawaran Rahasia Pelanggan Lain');

        $this->actingAs($this->member)
            ->get(route('packages.member', ['view' => 'mobile']))
            ->assertOk()
            ->assertSee($draftVersion->title)
            ->assertDontSee('<h3 class="h6 fw-semibold mb-1">'.$firstVersion->title.'</h3>', false)
            ->assertDontSee('Penawaran Rahasia Pelanggan Lain');
    }

    public function test_revision_creates_a_new_version_and_notifies_the_same_recipient(): void
    {
        Notification::fake();

        $this->actingAs($this->admin)
            ->post(route('package-offers.store'), $this->offerPayload())
            ->assertRedirect();

        $firstVersion = CustomPackageOffer::firstOrFail();
        $this->actingAs($this->admin)
            ->post(route('package-offers.send', $firstVersion->uuid))
            ->assertRedirect();
        $firstVersion->refresh();
        $this->actingAs($this->member)
            ->post(route('package-offers.respond', $firstVersion->uuid), [
                'action' => 'request_revision',
                'revision_note' => 'Mohon tambahkan dokumentasi acara.',
            ])
            ->assertRedirect();

        $this->actingAs($this->admin)
            ->post(route('package-offers.revise', $firstVersion->uuid), $this->offerPayload([
                'user_uuid' => $this->member->uuid,
                'title' => 'Paket Acara Privat Revisi',
                'items' => [[
                    'name' => 'Dokumentasi acara',
                    'description' => 'Foto dan video acara.',
                    'quantity' => 1,
                    'unit_price' => 1000000,
                ]],
            ]))
            ->assertRedirect();

        $draftVersion = CustomPackageOffer::where('thread_uuid', $firstVersion->thread_uuid)
            ->where('version', 2)
            ->firstOrFail();
        $this->assertSame('draft', $draftVersion->status);
        $this->assertDatabaseHas('custom_package_offers', [
            'uuid' => $firstVersion->uuid,
            'status' => 'revision_requested',
        ]);
        $this->actingAs($this->admin)
            ->post(route('package-offers.send', $draftVersion->uuid))
            ->assertRedirect();

        $this->assertDatabaseHas('custom_package_offers', [
            'uuid' => $firstVersion->uuid,
            'status' => 'superseded',
        ]);
        $this->assertDatabaseHas('custom_package_offers', [
            'thread_uuid' => $firstVersion->thread_uuid,
            'version' => 2,
            'user_uuid' => $this->member->uuid,
            'title' => 'Paket Acara Privat Revisi',
            'status' => 'sent',
        ]);

        Notification::assertSentTo($this->member, CustomPackageOfferNotification::class);
        Notification::assertNotSentTo($this->otherMember, CustomPackageOfferNotification::class);

        $this->actingAs($this->member)
            ->get(route('package-offers.show', $draftVersion->uuid))
            ->assertOk()
            ->assertSee('Perbandingan versi penawaran')
            ->assertSee('Paket Acara Privat')
            ->assertSee('Paket Acara Privat Revisi')
            ->assertSee('Dokumentasi acara');
    }

    public function test_custom_dp_verification_confirms_the_date_without_activating_a_membership(): void
    {
        $this->actingAs($this->admin)
            ->post(route('package-offers.store'), $this->offerPayload())
            ->assertRedirect();

        $offer = CustomPackageOffer::firstOrFail();
        $this->actingAs($this->admin)
            ->post(route('package-offers.send', $offer->uuid))
            ->assertRedirect();
        $offer->refresh();
        $this->actingAs($this->member)
            ->post(route('package-offers.respond', $offer->uuid), ['action' => 'accept'])
            ->assertRedirect();
        $this->actingAs($this->member)
            ->post(route('package-offers.checkout.store', $offer->uuid))
            ->assertRedirect();

        $order = Order::firstOrFail();
        Payment::create([
            'order_uuid' => $order->uuid,
            'kind' => 'dp',
            'payment_gateway' => 'manual_transfer',
            'payment_type' => 'Transfer Manual DP',
            'amount' => $order->amount,
            'gross_amount' => $order->amount,
            'status' => 'pending',
            'verification_status' => 'pending',
        ]);

        $this->actingAs($this->admin)
            ->post(route('orders.approve', $order->uuid))
            ->assertRedirect();

        $order->refresh();
        $this->assertSame('partially_paid', $order->status);
        $this->assertDatabaseHas('event_date_reservations', [
            'order_uuid' => $order->uuid,
            'status' => 'confirmed',
        ]);
        $this->assertSame(0, UserPackage::where('order_uuid', $order->uuid)->count());

        $invoice = OrderDocument::where('order_uuid', $order->uuid)
            ->where('type', 'invoice')
            ->firstOrFail();
        $this->assertSame($offer->title, data_get($invoice->snapshot, 'package_name'));
        $this->assertSame($offer->title, data_get($invoice->snapshot, 'custom_package_offer.title'));
    }

    public function test_draft_is_not_sent_if_its_event_date_becomes_unavailable(): void
    {
        Notification::fake();

        $payload = $this->offerPayload([
            'event_date' => now()->addMonths(6)->toDateString(),
        ]);
        $this->actingAs($this->admin)
            ->post(route('package-offers.store'), $payload)
            ->assertRedirect();
        $staleDraft = CustomPackageOffer::firstOrFail();

        $this->actingAs($this->admin)
            ->post(route('package-offers.store'), $payload)
            ->assertRedirect();
        $liveOffer = CustomPackageOffer::where('uuid', '!=', $staleDraft->uuid)->firstOrFail();
        $this->actingAs($this->admin)
            ->post(route('package-offers.send', $liveOffer->uuid))
            ->assertRedirect();
        $this->actingAs($this->member)
            ->post(route('package-offers.respond', $liveOffer->uuid), ['action' => 'accept'])
            ->assertRedirect();
        $this->actingAs($this->member)
            ->post(route('package-offers.checkout.store', $liveOffer->uuid))
            ->assertRedirect();

        $this->actingAs($this->admin)
            ->post(route('package-offers.send', $staleDraft->uuid))
            ->assertSessionHasErrors('event_date');

        $this->assertDatabaseHas('custom_package_offers', [
            'uuid' => $staleDraft->uuid,
            'status' => 'draft',
        ]);
        $staleDraftNotifications = Notification::sent($this->member, CustomPackageOfferNotification::class)
            ->filter(fn (CustomPackageOfferNotification $notification) => $notification->offerUuid === $staleDraft->uuid);
        $this->assertCount(0, $staleDraftNotifications);
    }

    public function test_draft_remains_private_and_can_be_edited_before_sending(): void
    {
        Notification::fake();

        $this->actingAs($this->admin)
            ->post(route('package-offers.store'), $this->offerPayload())
            ->assertRedirect();

        $offer = CustomPackageOffer::firstOrFail();
        $this->actingAs($this->member)
            ->get(route('package-offers.mine'))
            ->assertOk()
            ->assertDontSee($offer->title);
        $this->actingAs($this->admin)
            ->get(route('package-offers.edit', $offer->uuid))
            ->assertOk()
            ->assertSee('Ubah Draf Penawaran');
        $this->actingAs($this->admin)
            ->put(route('package-offers.update', $offer->uuid), $this->offerPayload([
                'title' => 'Draf Paket Diperbarui',
            ]))
            ->assertRedirect(route('package-offers.show', $offer->uuid));

        $this->assertDatabaseHas('custom_package_offers', [
            'uuid' => $offer->uuid,
            'title' => 'Draf Paket Diperbarui',
            'status' => 'draft',
        ]);
        $this->actingAs($this->admin)
            ->post(route('package-offers.discard', $offer->uuid))
            ->assertRedirect(route('package-offers.index'));
        $this->assertDatabaseMissing('custom_package_offers', ['uuid' => $offer->uuid]);
        Notification::assertNotSentTo($this->member, CustomPackageOfferNotification::class);
    }

    public function test_customer_package_request_is_private_and_can_be_converted_to_an_offer(): void
    {
        Notification::fake();

        $payload = [
            'event_date' => now()->addMonths(8)->toDateString(),
            'guest_count' => 150,
            'venue_note' => 'Bandung',
            'budget_min' => 20000000,
            'budget_max' => 35000000,
            'request_details' => 'Kami membutuhkan dekorasi, dokumentasi, dan koordinator acara dengan konsep taman.',
        ];
        $this->actingAs($this->member)
            ->post(route('package-offer-requests.store'), $payload)
            ->assertRedirect();

        $packageRequest = CustomPackageOfferRequest::firstOrFail();
        Notification::assertSentTo($this->admin, CustomPackageRequestNotification::class);
        Notification::assertNotSentTo($this->otherMember, CustomPackageRequestNotification::class);
        $this->actingAs($this->otherMember)
            ->get(route('package-offer-requests.show', $packageRequest->uuid))
            ->assertNotFound();

        $this->actingAs($this->admin)
            ->get(route('package-offer-requests.index'))
            ->assertOk()
            ->assertSee($this->member->name);
        $this->actingAs($this->admin)
            ->post(route('package-offer-requests.status', $packageRequest->uuid), [
                'status' => 'in_review',
                'admin_note' => 'Catatan internal yang tidak ditampilkan ke pelanggan.',
            ])
            ->assertRedirect();
        $this->actingAs($this->admin)
            ->post(route('package-offer-requests.convert', $packageRequest->uuid))
            ->assertRedirect(route('package-offers.create', ['request_uuid' => $packageRequest->uuid]));

        $this->actingAs($this->admin)
            ->post(route('package-offers.store'), $this->offerPayload([
                'source_request_uuid' => $packageRequest->uuid,
                'title' => 'Paket Acara dari Permintaan',
            ]))
            ->assertRedirect();
        $offer = CustomPackageOffer::where('source_request_uuid', $packageRequest->uuid)->firstOrFail();
        $this->actingAs($this->admin)
            ->post(route('package-offers.send', $offer->uuid))
            ->assertRedirect();

        $this->assertDatabaseHas('custom_package_offer_requests', [
            'uuid' => $packageRequest->uuid,
            'status' => 'quoted',
        ]);
        $this->actingAs($this->member)
            ->get(route('package-offer-requests.show', $packageRequest->uuid))
            ->assertOk()
            ->assertSee('Penawaran tersedia')
            ->assertSee('Paket Acara dari Permintaan')
            ->assertDontSee('Catatan internal yang tidak ditampilkan');
    }

    public function test_expiry_reminder_is_sent_once_during_the_day_before_window(): void
    {
        Notification::fake();

        $this->actingAs($this->admin)
            ->post(route('package-offers.store'), $this->offerPayload())
            ->assertRedirect();
        $offer = CustomPackageOffer::firstOrFail();
        $this->actingAs($this->admin)
            ->post(route('package-offers.send', $offer->uuid))
            ->assertRedirect();
        $offer->update(['expires_at' => now()->addHours(24)]);

        $this->artisan('package-offers:remind-expiry')->assertExitCode(0);
        $reminderTime = $offer->fresh()->expiry_reminded_at;
        $this->assertNotNull($reminderTime);
        $this->artisan('package-offers:remind-expiry')->assertExitCode(0);

        $expiryReminders = Notification::sent($this->member, CustomPackageOfferNotification::class)
            ->filter(fn (CustomPackageOfferNotification $notification) => $notification->title === 'Penawaran akan segera berakhir');
        $this->assertCount(1, $expiryReminders);
        $this->assertSame($reminderTime->toDateTimeString(), $offer->fresh()->expiry_reminded_at->toDateTimeString());
    }

    public function test_offer_pdf_is_available_only_to_admin_and_its_recipient(): void
    {
        Notification::fake();

        $this->actingAs($this->admin)
            ->post(route('package-offers.store'), $this->offerPayload())
            ->assertRedirect();
        $offer = CustomPackageOffer::firstOrFail();
        $this->actingAs($this->admin)
            ->post(route('package-offers.send', $offer->uuid))
            ->assertRedirect();

        $this->actingAs($this->admin)
            ->get(route('package-offers.pdf', $offer->uuid))
            ->assertOk()
            ->assertHeader('content-type', 'application/pdf');
        $this->actingAs($this->member)
            ->get(route('package-offers.pdf', $offer->uuid))
            ->assertOk();
        $this->actingAs($this->otherMember)
            ->get(route('package-offers.pdf', $offer->uuid))
            ->assertNotFound();
    }

    public function test_offer_analytics_tracks_checkout_and_verified_payments(): void
    {
        $this->actingAs($this->admin)
            ->post(route('package-offers.store'), $this->offerPayload())
            ->assertRedirect();
        $offer = CustomPackageOffer::firstOrFail();

        $this->actingAs($this->admin)
            ->post(route('package-offers.send', $offer->uuid))
            ->assertRedirect();
        $this->actingAs($this->member)
            ->post(route('package-offers.respond', $offer->uuid), ['action' => 'accept'])
            ->assertRedirect();
        $this->actingAs($this->member)
            ->post(route('package-offers.checkout.store', $offer->uuid))
            ->assertRedirect();

        $order = Order::firstOrFail();
        $order->update([
            'status' => 'partially_paid',
            'amount_paid' => 150000,
            'remaining_amount' => 850000,
        ]);

        $this->actingAs($this->admin)
            ->get(route('package-offers.analytics'))
            ->assertOk()
            ->assertSee('Pesanan dibuat')
            ->assertSee('Pesanan dengan pembayaran')
            ->assertSee('Penawaran menjadi pesanan')
            ->assertSee('Pembayaran yang sudah diterima')
            ->assertSee('Rp 150.000');
    }

    private function offerPayload(array $overrides = []): array
    {
        return array_replace_recursive([
            'user_uuid' => $this->member->uuid,
            'title' => 'Paket Acara Privat',
            'description' => 'Penawaran yang disusun sesuai kebutuhan pelanggan.',
            'event_date' => now()->addMonths(6)->toDateString(),
            'guest_count' => 100,
            'venue_note' => 'Jakarta',
            'customer_note' => 'Kami siap membantu acara Anda.',
            'admin_note' => 'Catatan internal admin.',
            'items' => [[
                'name' => 'Layanan wedding organizer',
                'description' => 'Pendampingan pada hari acara.',
                'quantity' => 1,
                'unit_price' => 1000000,
            ]],
            'discount_amount' => 0,
            'deposit_percentage' => 30,
            'payment_terms_note' => 'Sisa pembayaran dilunasi sebelum acara.',
        ], $overrides);
    }
}
