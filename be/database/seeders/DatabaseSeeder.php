<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call(CurrentSnapshotSeeder::class);
        $this->call(AdminWeddingOpsSeeder::class);
        $this->call(WeddingPackageCleanupSeeder::class);
        $this->call(WeddingBrandCleanupSeeder::class);
    }
}
