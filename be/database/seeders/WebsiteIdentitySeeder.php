<?php

namespace Database\Seeders;

use App\Models\ManagementAccess\MenuGroup;
use App\Models\ManagementAccess\MenuItem;
use App\Models\WebsiteIdentity;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class WebsiteIdentitySeeder extends Seeder
{
    public function run(): void
    {
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        WebsiteIdentity::updateOrCreate(
            ['uuid' => '39860859-6a83-4cae-aa27-659334baff1c'],
            [
                'site_name' => 'Sagita Wedding',
                'site_title' => 'Sagita Wedding',
                'tagline' => 'Wedding Planner',
                'short_description' => 'Wedding planning, concept, and celebration services designed with elegance and care.',
                'email' => null,
                'phone' => null,
                'address' => null,
                'facebook_url' => null,
                'instagram_url' => null,
                'youtube_url' => null,
                'tiktok_url' => null,
                'meta_title' => null,
                'meta_description' => null,
                'meta_keywords' => null,
                'google_analytics_id' => null,
                'google_site_verification' => null,
                'logo' => null,
                'favicon' => null,
                'og_image' => null,
            ]
        );

        $menuGroup = MenuGroup::where('name', 'Pengaturan Sistem')->first()
            ?? MenuGroup::firstOrCreate(
                ['permission_name' => 'menu.pengaturan'],
                [
                    'name' => 'Pengaturan Sistem',
                    'icon' => 'bx-cog',
                    'status' => true,
                    'position' => 12,
                ]
            );

        MenuItem::updateOrCreate(
            ['route' => 'website-identity.index', 'menu_group_id' => $menuGroup->id],
            [
                'name' => 'Website Identity',
                'status' => true,
                'permission_name' => 'website-identity.index',
                'position' => 99,
            ]
        );

        foreach (Role::whereIn('name', ['super-admin', 'admin'])->get() as $role) {
            $role->givePermissionTo(['website-identity.index', 'website-identity.update']);
        }

        app()[PermissionRegistrar::class]->forgetCachedPermissions();
    }
}
