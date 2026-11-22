<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call(PermissionSeeder::class);
        $this->call(RoleSeeder::class);
        $this->call(MenuSeeder::class);
        $this->call(RouteSeeder::class);
        $this->call(SecuritySeeder::class);
        $this->call(MembershipSeeder::class);
        $this->call(BannerMediaSeeder::class);
        $this->call(WebsiteIdentitySeeder::class);
        $this->call(EmailSettingSeeder::class);
        $this->call(ContentSeeder::class);
    }
}
