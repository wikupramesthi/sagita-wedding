<?php

namespace Tests\Feature;

use App\Models\EmailSetting;
use App\Models\User;
use App\Models\WebsiteIdentity;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Route as RouteFacade;
use Illuminate\Support\Facades\Schema;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class EmailSettingModuleTest extends TestCase
{
    use RefreshDatabase;

    private User $user;

    protected function setUp(): void
    {
        parent::setUp();

        $role = Role::findOrCreate('admin', 'web');

        foreach ([
            'email-setting.index',
            'email-setting.update',
            'email-setting.test',
            'website-identity.index',
            'website-identity.update',
        ] as $name) {
            $role->givePermissionTo(Permission::firstOrCreate(['name' => $name, 'guard_name' => 'web']));
        }

        $this->user = User::factory()->create(['email_verified_at' => now()]);
        $this->user->assignRole($role);
    }

    public function test_module_schema_routes_and_menu_are_registered(): void
    {
        $this->assertTrue(Schema::hasTable('email_settings'));
        $this->assertTrue(RouteFacade::has('email-setting.index'));
        $this->assertTrue(RouteFacade::has('email-setting.update'));
        $this->assertTrue(RouteFacade::has('email-setting.test'));

        $this->assertDatabaseHas('menu_groups', ['name' => 'Pengaturan Sistem']);
        $this->assertDatabaseHas('menu_items', [
            'route' => 'email-setting.index',
            'name' => 'Pengaturan Email',
            'permission_name' => 'email-setting.index',
        ]);
        $this->assertDatabaseHas('routes', [
            'route' => 'email-setting.index',
            'permission_name' => 'email-setting.index',
        ]);
    }

    public function test_admin_can_open_and_update_email_settings(): void
    {
        $this->actingAs($this->user)
            ->get(route('email-setting.index'))
            ->assertOk()
            ->assertSee('Pengaturan Email')
            ->assertSee('Koneksi SMTP');

        $this->actingAs($this->user)
            ->put(route('email-setting.update'), [
                'mail_driver' => 'smtp',
                'mail_host' => 'smtp.example.com',
                'mail_port' => 587,
                'mail_encryption' => 'tls',
                'mail_username' => 'noreply@example.com',
                'mail_password' => 'rahasia-plain',
                'mail_timeout' => 45,
                'from_address' => 'noreply@example.com',
                'from_name' => 'Sagita Wedding',
                'is_active' => 1,
            ])
            ->assertRedirect(route('email-setting.index'))
            ->assertSessionHas('success');

        $this->assertDatabaseHas('email_settings', [
            'mail_host' => 'smtp.example.com',
            'mail_port' => 587,
            'mail_encryption' => 'tls',
            'mail_password' => 'rahasia-plain',
            'from_name' => 'Sagita Wedding',
            'is_active' => 1,
        ]);

        $this->assertSame('smtp', config('mail.default'));
        $this->assertSame('smtp.example.com', config('mail.mailers.smtp.host'));
        $this->assertSame('noreply@example.com', config('mail.from.address'));
    }

    public function test_blank_password_keeps_existing_password(): void
    {
        $setting = EmailSetting::singleton();
        $setting->update(['mail_password' => 'password-lama']);

        $this->actingAs($this->user)
            ->put(route('email-setting.update'), [
                'mail_driver' => 'smtp',
                'mail_host' => 'smtp.example.com',
                'mail_port' => 587,
                'mail_encryption' => '',
                'mail_username' => 'noreply@example.com',
                'mail_password' => '',
                'mail_timeout' => 60,
                'from_address' => 'noreply@example.com',
                'from_name' => 'Sagita Wedding',
                'is_active' => 1,
            ])
            ->assertRedirect(route('email-setting.index'));

        $this->assertSame('password-lama', $setting->fresh()->mail_password);
    }

    public function test_test_email_endpoint_accepts_recipient(): void
    {
        $this->actingAs($this->user)
            ->put(route('email-setting.update'), [
                'mail_driver' => 'array',
                'mail_timeout' => 60,
                'from_address' => 'noreply@example.com',
                'from_name' => 'Sagita Wedding',
                'is_active' => 1,
            ])
            ->assertRedirect(route('email-setting.index'));

        $this->actingAs($this->user)
            ->post(route('email-setting.test'), ['recipient' => 'qa@example.com'])
            ->assertRedirect()
            ->assertSessionHas('success');
    }

    public function test_website_identity_changes_sync_default_sender(): void
    {
        $identity = WebsiteIdentity::singleton();
        $identity->update([
            'site_name' => 'Sagita Lama',
            'email' => 'lama@example.com',
        ]);
        EmailSetting::syncFromWebsiteIdentity($identity);

        $this->actingAs($this->user)
            ->put(route('website-identity.update'), [
                'site_name' => 'Sagita Baru',
                'site_title' => 'Sagita Baru',
                'email' => 'baru@example.com',
            ])
            ->assertRedirect(route('website-identity.index'));

        $this->assertDatabaseHas('email_settings', [
            'from_address' => 'baru@example.com',
            'from_name' => 'Sagita Baru',
        ]);
    }
}
