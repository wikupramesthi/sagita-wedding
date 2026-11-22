<?php

namespace Database\Seeders;

use App\Models\Article;
use App\Models\Banner;
use App\Models\Category;
use App\Models\Event;
use App\Models\Package\Package;
use App\Models\Package\PackageFeature;
use App\Models\Package\PackageOption;
use App\Models\Page;
use App\Models\Testimonial;
use App\Models\VendorsCategory;
use App\Models\Venue;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ContentSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('package_addons')->delete();
        DB::table('package_promos')->delete();
        DB::table('package_wishlists')->delete();

        DB::table('package_features')->whereNotIn('uuid', ['01a09615-73fb-702f-8c0a-b5012141236e', '01a09615-7401-7073-add9-f090d187e2f8', '01a09615-7402-71bd-a8b8-9e5f3acd8ad7', '01a09615-7402-71bd-a8b8-9e5f3ae8a3fb', '01a09615-7409-70e7-9271-8596c85a0a7d', '01a098de-ec82-701f-bb24-c2dbbe0c59b9', '01a098de-ec83-7166-a29d-85d9cd8131b5', '01a098de-ec83-7166-a29d-85d9cdfbb9df', '01a098de-ec84-71fb-82be-6092568d7513', '01a098de-ec86-70ea-a38d-1c994c52404a', '01a098de-ec87-70ea-ad3c-c41d16e6f542', '01a098e0-8799-703c-a314-911481118e4e', '01a098e0-879f-715a-8e83-0ae3ed067c6f', '01a098e0-87a0-705c-94e4-64e7348fd306', '01a098e0-87a1-7275-b6b4-5e5b4873a277', '01a098e0-87a2-72dc-8631-635b1abff726', '01a098e0-87a3-72d4-b233-8ba8254b8bb6', '01a09965-fbf3-7255-bb66-859b3a4e1bff', '01a09965-fbf9-7032-b9db-53e383f6b6c9', '01a09965-fbfa-72c8-9335-c298673b2bec', '01a09965-fbfa-72c8-9335-c298680c415e', '01a09965-fbfb-72aa-8e43-d33cdbde0650', '01a09966-2967-7153-8bac-e05d58fabab9', '01a09966-296d-7188-bf8c-90a9e73adf27', '01a09966-296d-7188-bf8c-90a9e8268917', '01a09966-296e-71e6-a0f5-c78ca826aad2', '01a09966-296e-71e6-a0f5-c78ca89e5199'])->delete();
        DB::table('package_options')->whereNotIn('uuid', ['01a09615-73f9-72ee-85e5-f2be4ef4c502', '01a098de-ec50-73bf-b928-c37f31d37568', '01a098de-ec7d-7352-9076-67a2defe3fde', '01a098de-ec7f-7275-a7f8-be27d7caa4ff', '01a098e0-8795-71b4-84a4-de9c8a0b517e', '01a098e0-8797-7309-954d-6caac6a8f4ee', '01a098e0-8798-707b-b260-c1379231361e', '01a09965-fbf0-7307-ba83-dfa1382cf270', '01a09966-2965-7201-ab57-9f085e23bbc7'])->delete();
        DB::table('packages')->whereNotIn('uuid', ['01a095f2-d286-7241-b566-8027529b8196', '01a09615-73d8-7345-a20f-2d5eeb6b1d1a', '01a098c6-e509-7154-869f-8d336d44ebc4', '01a098d2-a02c-72ee-a8eb-b9dc6786761d', '01a098e0-878e-7296-b1c0-a550bf0327b0'])->delete();
        DB::table('banner')->whereNotIn('uuid', ['55ef87f4-ea5c-481a-aecf-d1b660417340', '6c72aaea-fcde-4aec-911c-4c288cf975ee'])->delete();
        DB::table('categories')->whereNotIn('uuid', ['2162d145-9ef3-4e2f-8c55-81971a015bc5', '34103609-8116-4baf-bd17-557fc6989e8e', '72561291-cdcb-484b-a556-0400fbd53c3d', 'a58f5ebc-c8ec-48da-b6f9-a80b05ca40a0', 'bb006a6e-36bc-48cb-a84f-2a562489bb54'])->delete();
        DB::table('vendors_categories')->whereNotIn('uuid', ['11e863ef-4739-4bcf-b7d1-d599b336bb09', '2146b32e-85f3-4df1-b8c6-4718bd786114', '28b37aad-a252-4bbf-9284-d25c63047b19', '3cddd283-5b50-493a-9cab-cb04eef88946', '4240ba9b-225f-4544-a558-cc4d30ddefa2', '50b72628-5cd5-4f8d-937a-a41852efc90d', '6b28240c-d7d9-4b09-850b-6dcbb3875c18', 'ae3ca862-ed29-4025-bab9-4caff951820e'])->delete();
        DB::table('testimonials')->whereNotIn('uuid', ['267d9987-ea8e-422a-a32d-a26e7e9cea0f', '8a7bbb78-eb33-4f1b-941c-cbdcd2b9c5a3', 'dcfd5ae2-e3a0-4c4b-a3c4-937af0a086d7'])->delete();
        DB::table('pages')->whereNotIn('uuid', ['5380a3e5-adfa-437c-a0fa-bc2193d55cb2', '9e55815c-a4dd-4df2-8cb5-27f7de7249fd'])->delete();
        DB::table('articles')->whereNotIn('uuid', ['63829ea9-8a32-4f82-94e1-7299120f822c'])->delete();
        DB::table('venues')->whereNotIn('uuid', ['363f49dc-b34f-4ac2-ad18-95b140c6335e'])->delete();
        DB::table('events')->whereNotIn('uuid', ['02471b9c-9346-409c-b9b0-5331d91456ed', '3191142d-9185-421c-be1d-791c843f6161', '64e369a4-ed0f-4009-996e-362f879a681e'])->delete();

        DB::table('users')->updateOrInsert(
            ['uuid' => '00000000-0000-4000-8000-000000000001'],
            [
                'name' => 'System Content Author',
                'avatar' => 'default.png',
                'email' => 'content-author@example.invalid',
                'password' => bcrypt(Str::random(64)),
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );

        Model::unguarded(function () {
            $data = [
                'banner' => [
                    0 => [
                        'uuid' => '55ef87f4-ea5c-481a-aecf-d1b660417340',
                        'tipe' => 'foto',
                        'video_url' => null,
                        'nama' => 'Banner 1',
                        'deskripsi' => null,
                        'link' => null,
                        'gambar' => 'banners/m3oToKM6YyYpTu0jXmWB0Ugn6ApnTvykmnEbkReD.jpg',
                        'posisi' => 'slider',
                        'status' => 'active',
                    ],
                    1 => [
                        'uuid' => '6c72aaea-fcde-4aec-911c-4c288cf975ee',
                        'tipe' => 'foto',
                        'video_url' => null,
                        'nama' => 'Banner 2',
                        'deskripsi' => null,
                        'link' => null,
                        'gambar' => 'banners/KeY0gZZHOXpGhKqkSZHfaKlY8DujUZZtqWoNNFxg.jpg',
                        'posisi' => 'slider',
                        'status' => 'active',
                    ],
                ],
                'categories' => [
                    0 => [
                        'uuid' => '2162d145-9ef3-4e2f-8c55-81971a015bc5',
                        'name' => 'Tips Pernikahan',
                        'slug' => 'tips-pernikahan',
                        'description' => 'Panduan dan tips mempersiapkan pernikahan.',
                        'icon' => null,
                    ],
                    1 => [
                        'uuid' => '34103609-8116-4baf-bd17-557fc6989e8e',
                        'name' => 'Inspirasi Pernikahan',
                        'slug' => 'inspirasi-pernikahan',
                        'description' => 'Ide tema, konsep, dan dekorasi wedding.',
                        'icon' => null,
                    ],
                    2 => [
                        'uuid' => '72561291-cdcb-484b-a556-0400fbd53c3d',
                        'name' => 'Wedding Planning',
                        'slug' => 'wedding-planning',
                        'description' => 'Anggaran, checklist, timeline, dan rundown.',
                        'icon' => null,
                    ],
                    3 => [
                        'uuid' => 'a58f5ebc-c8ec-48da-b6f9-a80b05ca40a0',
                        'name' => 'Tren Pernikahan',
                        'slug' => 'tren-pernikahan',
                        'description' => 'Tren makeup, busana, dekorasi, dan konsep terbaru.',
                        'icon' => null,
                    ],
                    4 => [
                        'uuid' => 'bb006a6e-36bc-48cb-a84f-2a562489bb54',
                        'name' => 'Berita',
                        'slug' => 'berita',
                        'description' => 'Kegiatan, promo, dan informasi terbaru Sagita Wedding Planner.',
                        'icon' => null,
                    ],
                ],
                'packages' => [
                    0 => [
                        'uuid' => '01a095f2-d286-7241-b566-8027529b8196',
                        'name' => 'Paket Rencana Pernikahan',
                        'slug' => 'paket-rencana-pernikahan',
                        'description' => 'Paket awal untuk merencanakan pernikahan Anda dengan konsultasi, pilihan vendor, dan layanan sesuai kebutuhan acara.',
                        'guest_capacity' => null,
                        'event_duration_hours' => null,
                        'venue_note' => null,
                        'cover_url' => null,
                        'is_popular' => 1,
                        'is_active' => 'active',
                        'is_published' => 1,
                        'published_at' => null,
                        'lead_time_days' => 0,
                        'price' => '0.00',
                    ],
                    1 => [
                        'uuid' => '01a09615-73d8-7345-a20f-2d5eeb6b1d1a',
                        'name' => 'Paket Pernikahan Lengkap',
                        'slug' => 'paket-pernikahan-lengkap',
                        'description' => 'Paket lengkap dengan layanan pernikahan yang dapat disesuaikan untuk mendukung setiap tahapan acara Anda.',
                        'guest_capacity' => null,
                        'event_duration_hours' => null,
                        'venue_note' => null,
                        'cover_url' => null,
                        'is_popular' => 0,
                        'is_active' => 'active',
                        'is_published' => 1,
                        'published_at' => null,
                        'lead_time_days' => 0,
                        'price' => '0.00',
                    ],
                    2 => [
                        'uuid' => '01a098c6-e509-7154-869f-8d336d44ebc4',
                        'name' => 'Paket Pernikahan Berdua',
                        'slug' => 'paket-pernikahan-berdua',
                        'description' => 'Paket fleksibel untuk merencanakan pernikahan bersama pasangan dan mempersiapkan acara sesuai kebutuhan.',
                        'guest_capacity' => null,
                        'event_duration_hours' => null,
                        'venue_note' => null,
                        'cover_url' => null,
                        'is_popular' => 0,
                        'is_active' => 'active',
                        'is_published' => 1,
                        'published_at' => null,
                        'lead_time_days' => 0,
                        'price' => '0.00',
                    ],
                    3 => [
                        'uuid' => '01a098d2-a02c-72ee-a8eb-b9dc6786761d',
                        'name' => 'Konsultasi Pernikahan Personal',
                        'slug' => 'konsultasi-pernikahan-personal',
                        'description' => 'Pendampingan personal untuk merancang konsep, anggaran, vendor, dan rangkaian acara pernikahan.',
                        'guest_capacity' => null,
                        'event_duration_hours' => null,
                        'venue_note' => null,
                        'cover_url' => null,
                        'is_popular' => 0,
                        'is_active' => 'active',
                        'is_published' => 1,
                        'published_at' => null,
                        'lead_time_days' => 0,
                        'price' => '0.00',
                    ],
                    4 => [
                        'uuid' => '01a098e0-878e-7296-b1c0-a550bf0327b0',
                        'name' => 'Konsultasi Pernikahan Pasangan',
                        'slug' => 'konsultasi-pernikahan-pasangan',
                        'description' => 'Pendampingan bersama pasangan untuk menyusun konsep, anggaran, vendor, dan detail acara pernikahan.',
                        'guest_capacity' => null,
                        'event_duration_hours' => null,
                        'venue_note' => null,
                        'cover_url' => null,
                        'is_popular' => 0,
                        'is_active' => 'active',
                        'is_published' => 1,
                        'published_at' => null,
                        'lead_time_days' => 0,
                        'price' => '0.00',
                    ],
                ],
                'package_options' => [
                    0 => [
                        'uuid' => '01a09615-73f9-72ee-85e5-f2be4ef4c502',
                        'package_uuid' => '01a09615-73d8-7345-a20f-2d5eeb6b1d1a',
                        'name' => 'Seluruh Layanan',
                        'quota' => 50,
                        'price' => 3000000,
                        'discount_price' => 2400000,
                        'duration' => 1,
                        'duration_unit' => 'month',
                        'sort_order' => 0,
                        'is_active' => 1,
                    ],
                    1 => [
                        'uuid' => '01a098de-ec50-73bf-b928-c37f31d37568',
                        'package_uuid' => '01a098d2-a02c-72ee-a8eb-b9dc6786761d',
                        'name' => 'Satu Kali Konsultasi',
                        'quota' => 1,
                        'price' => 1000000,
                        'discount_price' => null,
                        'duration' => 1,
                        'duration_unit' => 'week',
                        'sort_order' => 0,
                        'is_active' => 1,
                    ],
                    2 => [
                        'uuid' => '01a098de-ec7d-7352-9076-67a2defe3fde',
                        'package_uuid' => '01a098d2-a02c-72ee-a8eb-b9dc6786761d',
                        'name' => '5 Kali Konsultasi',
                        'quota' => 1,
                        'price' => 4500000,
                        'discount_price' => null,
                        'duration' => 5,
                        'duration_unit' => 'week',
                        'sort_order' => 1,
                        'is_active' => 1,
                    ],
                    3 => [
                        'uuid' => '01a098de-ec7f-7275-a7f8-be27d7caa4ff',
                        'package_uuid' => '01a098d2-a02c-72ee-a8eb-b9dc6786761d',
                        'name' => '10 Kali Konsultasi',
                        'quota' => 1,
                        'price' => 8100000,
                        'discount_price' => null,
                        'duration' => 10,
                        'duration_unit' => 'week',
                        'sort_order' => 2,
                        'is_active' => 1,
                    ],
                    4 => [
                        'uuid' => '01a098e0-8795-71b4-84a4-de9c8a0b517e',
                        'package_uuid' => '01a098e0-878e-7296-b1c0-a550bf0327b0',
                        'name' => 'Satu Kali Konsultasi',
                        'quota' => 2,
                        'price' => 1200000,
                        'discount_price' => null,
                        'duration' => 1,
                        'duration_unit' => 'week',
                        'sort_order' => 0,
                        'is_active' => 1,
                    ],
                    5 => [
                        'uuid' => '01a098e0-8797-7309-954d-6caac6a8f4ee',
                        'package_uuid' => '01a098e0-878e-7296-b1c0-a550bf0327b0',
                        'name' => '5 Kali Konsultasi',
                        'quota' => 2,
                        'price' => 5400000,
                        'discount_price' => null,
                        'duration' => 5,
                        'duration_unit' => 'week',
                        'sort_order' => 1,
                        'is_active' => 1,
                    ],
                    6 => [
                        'uuid' => '01a098e0-8798-707b-b260-c1379231361e',
                        'package_uuid' => '01a098e0-878e-7296-b1c0-a550bf0327b0',
                        'name' => '10 Kali Konsultasi',
                        'quota' => 2,
                        'price' => 9720000,
                        'discount_price' => null,
                        'duration' => 10,
                        'duration_unit' => 'week',
                        'sort_order' => 2,
                        'is_active' => 1,
                    ],
                    7 => [
                        'uuid' => '01a09965-fbf0-7307-ba83-dfa1382cf270',
                        'package_uuid' => '01a098c6-e509-7154-869f-8d336d44ebc4',
                        'name' => '25 Sesi Layanan',
                        'quota' => 50,
                        'price' => 6999000,
                        'discount_price' => 5599000,
                        'duration' => 1,
                        'duration_unit' => 'month',
                        'sort_order' => 0,
                        'is_active' => 1,
                    ],
                    8 => [
                        'uuid' => '01a09966-2965-7201-ab57-9f085e23bbc7',
                        'package_uuid' => '01a095f2-d286-7241-b566-8027529b8196',
                        'name' => 'Pengalaman Pertama',
                        'quota' => 50,
                        'price' => 210000,
                        'discount_price' => null,
                        'duration' => 1,
                        'duration_unit' => 'week',
                        'sort_order' => 0,
                        'is_active' => 1,
                    ],
                ],
                'package_features' => [
                    0 => [
                        'uuid' => '01a09615-73fb-702f-8c0a-b5012141236e',
                        'package_uuid' => '01a09615-73d8-7345-a20f-2d5eeb6b1d1a',
                        'feature' => 'Unlimited Class Access',
                        'sort_order' => 0,
                    ],
                    1 => [
                        'uuid' => '01a09615-7401-7073-add9-f090d187e2f8',
                        'package_uuid' => '01a09615-73d8-7345-a20f-2d5eeb6b1d1a',
                        'feature' => 'Yoga Mat Provided',
                        'sort_order' => 1,
                    ],
                    2 => [
                        'uuid' => '01a09615-7402-71bd-a8b8-9e5f3acd8ad7',
                        'package_uuid' => '01a09615-73d8-7345-a20f-2d5eeb6b1d1a',
                        'feature' => 'Locker Access',
                        'sort_order' => 2,
                    ],
                    3 => [
                        'uuid' => '01a09615-7402-71bd-a8b8-9e5f3ae8a3fb',
                        'package_uuid' => '01a09615-73d8-7345-a20f-2d5eeb6b1d1a',
                        'feature' => 'Shower Facilities',
                        'sort_order' => 3,
                    ],
                    4 => [
                        'uuid' => '01a09615-7409-70e7-9271-8596c85a0a7d',
                        'package_uuid' => '01a09615-73d8-7345-a20f-2d5eeb6b1d1a',
                        'feature' => 'Access to All Studio Classes',
                        'sort_order' => 4,
                    ],
                    5 => [
                        'uuid' => '01a098de-ec82-701f-bb24-c2dbbe0c59b9',
                        'package_uuid' => '01a098d2-a02c-72ee-a8eb-b9dc6786761d',
                        'feature' => 'Private 1-on-1 Session',
                        'sort_order' => 0,
                    ],
                    6 => [
                        'uuid' => '01a098de-ec83-7166-a29d-85d9cd8131b5',
                        'package_uuid' => '01a098d2-a02c-72ee-a8eb-b9dc6786761d',
                        'feature' => 'Personalized Yoga Practice',
                        'sort_order' => 1,
                    ],
                    7 => [
                        'uuid' => '01a098de-ec83-7166-a29d-85d9cdfbb9df',
                        'package_uuid' => '01a098d2-a02c-72ee-a8eb-b9dc6786761d',
                        'feature' => 'Dedicated Instructor',
                        'sort_order' => 2,
                    ],
                    8 => [
                        'uuid' => '01a098de-ec84-71fb-82be-6092568d7513',
                        'package_uuid' => '01a098d2-a02c-72ee-a8eb-b9dc6786761d',
                        'feature' => 'Individual Guidance & Corrections',
                        'sort_order' => 3,
                    ],
                    9 => [
                        'uuid' => '01a098de-ec86-70ea-a38d-1c994c52404a',
                        'package_uuid' => '01a098d2-a02c-72ee-a8eb-b9dc6786761d',
                        'feature' => 'Practice Tailored to Your Goals',
                        'sort_order' => 4,
                    ],
                    10 => [
                        'uuid' => '01a098de-ec87-70ea-ad3c-c41d16e6f542',
                        'package_uuid' => '01a098d2-a02c-72ee-a8eb-b9dc6786761d',
                        'feature' => 'Flexible Session Focus',
                        'sort_order' => 5,
                    ],
                    11 => [
                        'uuid' => '01a098e0-8799-703c-a314-911481118e4e',
                        'package_uuid' => '01a098e0-878e-7296-b1c0-a550bf0327b0',
                        'feature' => 'Private Session for Two',
                        'sort_order' => 0,
                    ],
                    12 => [
                        'uuid' => '01a098e0-879f-715a-8e83-0ae3ed067c6f',
                        'package_uuid' => '01a098e0-878e-7296-b1c0-a550bf0327b0',
                        'feature' => 'Personalized Yoga Practice',
                        'sort_order' => 1,
                    ],
                    13 => [
                        'uuid' => '01a098e0-87a0-705c-94e4-64e7348fd306',
                        'package_uuid' => '01a098e0-878e-7296-b1c0-a550bf0327b0',
                        'feature' => 'Dedicated Instructor',
                        'sort_order' => 2,
                    ],
                    14 => [
                        'uuid' => '01a098e0-87a1-7275-b6b4-5e5b4873a277',
                        'package_uuid' => '01a098e0-878e-7296-b1c0-a550bf0327b0',
                        'feature' => 'Individual Guidance & Corrections',
                        'sort_order' => 3,
                    ],
                    15 => [
                        'uuid' => '01a098e0-87a2-72dc-8631-635b1abff726',
                        'package_uuid' => '01a098e0-878e-7296-b1c0-a550bf0327b0',
                        'feature' => 'Partner-Based Practice',
                        'sort_order' => 4,
                    ],
                    16 => [
                        'uuid' => '01a098e0-87a3-72d4-b233-8ba8254b8bb6',
                        'package_uuid' => '01a098e0-878e-7296-b1c0-a550bf0327b0',
                        'feature' => 'Practice Tailored to Your Goals',
                        'sort_order' => 5,
                    ],
                    17 => [
                        'uuid' => '01a09965-fbf3-7255-bb66-859b3a4e1bff',
                        'package_uuid' => '01a098c6-e509-7154-869f-8d336d44ebc4',
                        'feature' => 'Share Your Class Quota',
                        'sort_order' => 0,
                    ],
                    18 => [
                        'uuid' => '01a09965-fbf9-7032-b9db-53e383f6b6c9',
                        'package_uuid' => '01a098c6-e509-7154-869f-8d336d44ebc4',
                        'feature' => 'Flexible Class Access',
                        'sort_order' => 1,
                    ],
                    19 => [
                        'uuid' => '01a09965-fbfa-72c8-9335-c298673b2bec',
                        'package_uuid' => '01a098c6-e509-7154-869f-8d336d44ebc4',
                        'feature' => 'Locker Access',
                        'sort_order' => 2,
                    ],
                    20 => [
                        'uuid' => '01a09965-fbfa-72c8-9335-c298680c415e',
                        'package_uuid' => '01a098c6-e509-7154-869f-8d336d44ebc4',
                        'feature' => 'Shower Facilities',
                        'sort_order' => 3,
                    ],
                    21 => [
                        'uuid' => '01a09965-fbfb-72aa-8e43-d33cdbde0650',
                        'package_uuid' => '01a098c6-e509-7154-869f-8d336d44ebc4',
                        'feature' => 'Access to Studio Classes',
                        'sort_order' => 4,
                    ],
                    22 => [
                        'uuid' => '01a09966-2967-7153-8bac-e05d58fabab9',
                        'package_uuid' => '01a095f2-d286-7241-b566-8027529b8196',
                        'feature' => 'First Studio Session',
                        'sort_order' => 0,
                    ],
                    23 => [
                        'uuid' => '01a09966-296d-7188-bf8c-90a9e73adf27',
                        'package_uuid' => '01a095f2-d286-7241-b566-8027529b8196',
                        'feature' => 'Yoga Mat Provided',
                        'sort_order' => 1,
                    ],
                    24 => [
                        'uuid' => '01a09966-296d-7188-bf8c-90a9e8268917',
                        'package_uuid' => '01a095f2-d286-7241-b566-8027529b8196',
                        'feature' => 'Locker Access',
                        'sort_order' => 2,
                    ],
                    25 => [
                        'uuid' => '01a09966-296e-71e6-a0f5-c78ca826aad2',
                        'package_uuid' => '01a095f2-d286-7241-b566-8027529b8196',
                        'feature' => 'Shower Facilities',
                        'sort_order' => 3,
                    ],
                    26 => [
                        'uuid' => '01a09966-296e-71e6-a0f5-c78ca89e5199',
                        'package_uuid' => '01a095f2-d286-7241-b566-8027529b8196',
                        'feature' => 'Guidance from Our Instructor',
                        'sort_order' => 4,
                    ],
                ],
                'vendors_categories' => [
                    0 => [
                        'uuid' => '11e863ef-4739-4bcf-b7d1-d599b336bb09',
                        'name' => 'Wedding Planner',
                        'slug' => 'wedding-planner',
                        'description' => 'Layanan profesional untuk merencanakan, mengatur, dan mengoordinasikan seluruh kebutuhan acara pernikahan.',
                        'is_active' => 'active',
                    ],
                    1 => [
                        'uuid' => '2146b32e-85f3-4df1-b8c6-4718bd786114',
                        'name' => 'Dekorasi',
                        'slug' => 'dekorasi',
                        'description' => 'Penyedia layanan dekorasi pernikahan untuk menciptakan suasana acara yang indah, elegan, dan berkesan.',
                        'is_active' => 'active',
                    ],
                    2 => [
                        'uuid' => '28b37aad-a252-4bbf-9284-d25c63047b19',
                        'name' => 'Katering',
                        'slug' => 'katering',
                        'description' => 'Penyedia makanan dan minuman untuk memenuhi kebutuhan konsumsi tamu undangan selama acara pernikahan.',
                        'is_active' => 'active',
                    ],
                    3 => [
                        'uuid' => '3cddd283-5b50-493a-9cab-cb04eef88946',
                        'name' => 'Busana & MUA',
                        'slug' => 'busana-mua',
                        'description' => 'Penyedia busana pengantin dan jasa tata rias untuk menciptakan penampilan terbaik di hari istimewa.',
                        'is_active' => 'active',
                    ],
                    4 => [
                        'uuid' => '4240ba9b-225f-4544-a558-cc4d30ddefa2',
                        'name' => 'Dokumentasi',
                        'slug' => 'dokumentasi',
                        'description' => 'Penyedia layanan fotografi dan videografi untuk mengabadikan setiap momen berharga dalam pernikahan.',
                        'is_active' => 'active',
                    ],
                    5 => [
                        'uuid' => '50b72628-5cd5-4f8d-937a-a41852efc90d',
                        'name' => 'Entertainment',
                        'slug' => 'entertainment',
                        'description' => 'Penyedia hiburan dan pengisi acara untuk menciptakan suasana pernikahan yang meriah dan berkesan.',
                        'is_active' => 'active',
                    ],
                    6 => [
                        'uuid' => '6b28240c-d7d9-4b09-850b-6dcbb3875c18',
                        'name' => 'Venue',
                        'slug' => 'venue',
                        'description' => 'Penyedia lokasi atau tempat penyelenggaraan acara pernikahan sesuai konsep dan kebutuhan pasangan.',
                        'is_active' => 'active',
                    ],
                    7 => [
                        'uuid' => 'ae3ca862-ed29-4025-bab9-4caff951820e',
                        'name' => 'Souvenir & Undangan',
                        'slug' => 'souvenir-undangan',
                        'description' => 'Penyedia undangan dan souvenir pernikahan sebagai pelengkap serta kenang-kenangan bagi para tamu.',
                        'is_active' => 'active',
                    ],
                ],
                'testimonials' => [
                    0 => [
                        'uuid' => '267d9987-ea8e-422a-a32d-a26e7e9cea0f',
                        'nama' => 'Fhadillah Cherly Yunanda',
                        'jabatan' => 'Member Since 2024',
                        'isi_testimoni' => 'Best yoga place in jkt so far! Very clean, very nice lighting with natural sunlight, and the most important thing - so engaging instructor! Will be back offf course!!!',
                        'foto' => 'testimoni/Z8y84wRqXBbfhDInPMIZd9Oz6eV85vTxX1yRsc67.webp',
                        'urutan' => 2,
                        'is_active' => 'active',
                    ],
                    1 => [
                        'uuid' => '8a7bbb78-eb33-4f1b-941c-cbdcd2b9c5a3',
                        'nama' => 'Jessica Gloria Mogi',
                        'jabatan' => 'Member Since 2024',
                        'isi_testimoni' => 'The studio was beautiful, plenty of natural sunlight, it was not stuffy at all. They have a minimalist shower with some skincare products and medium-sized lockers. It\'s a great experience and I\'m coming back tomorrow :)',
                        'foto' => 'testimoni/XXcIhUampyp7fbAiIgv0U6qu7VQb8S56ISrPlTlH.webp',
                        'urutan' => 1,
                        'is_active' => 'active',
                    ],
                    2 => [
                        'uuid' => 'dcfd5ae2-e3a0-4c4b-a3c4-937af0a086d7',
                        'nama' => 'Melda Auditia',
                        'jabatan' => 'Member Since 2025',
                        'isi_testimoni' => 'Amazing experience. Great instructor. Space is quiet, beautiful & clean, with nice shower room for yoga before work. Would definitely come back again???????????????????',
                        'foto' => 'testimoni/Z8y84wRqXBbfhDInPMIZd9Oz6eV85vTxX1yRsc67.webp',
                        'urutan' => 3,
                        'is_active' => 'active',
                    ],
                ],
                'pages' => [
                    0 => [
                        'uuid' => '5380a3e5-adfa-437c-a0fa-bc2193d55cb2',
                        'title' => 'Privacy Policy',
                        'slug' => 'privacy-policy',
                        'excerpt' => 'By accessing and using the YogaRoots website and services, you acknowledge that you have read, understood, and agreed to this Privacy Policy.',
                        'content' => '<h2><strong style="color: rgb(71, 85, 105); font-size: 16px; letter-spacing: 0.1px;">Last Updated:</strong><span style="color: rgb(71, 85, 105); font-size: 16px; font-weight: 400; letter-spacing: 0.1px;"> August 30, 2026</span></h2>
            
            <p>
              Welcome to <strong>YogaRoots</strong>.
            </p>
            
            <p>
              We value your trust and are committed to protecting the privacy and security of your personal information. This Privacy Policy explains how YogaRoots collects, uses, stores, and protects your information when you visit our website, register for an account, contact us, or use our services.
            </p>
            
            <p>
              By accessing and using the YogaRoots website and services, you acknowledge that you have read, understood, and agreed to this Privacy Policy.
            </p>
            
            <h3>1. Information We Collect</h3>
            
            <p>
              We may collect information that you voluntarily provide when using the YogaRoots website or services, including:
            </p>
            
            <ul>
              <li>Full name</li>
              <li>Email address</li>
              <li>Phone number</li>
              <li>Information submitted through contact forms</li>
              <li>Class registration or booking information</li>
              <li>Account information if you use our login features</li>
              <li>Other information you voluntarily provide to us</li>
            </ul>
            
            <p>
              We may also automatically collect certain technical information when you access our website, such as your device type, browser type, IP address, and information about your interactions with and use of the website.
            </p>
            
            <h3>2. How We Use Your Information</h3>
            
            <p>
              The information we collect may be used to:
            </p>
            
            <ul>
              <li>Process class registrations and bookings</li>
              <li>Manage your account and membership</li>
              <li>Respond to your questions, requests, or inquiries</li>
              <li>Provide information about YogaRoots classes, schedules, programs, and services</li>
              <li>Send important information related to the services you use</li>
              <li>Improve our website, services, and overall user experience</li>
              <li>Maintain website security and prevent misuse or unauthorized activity</li>
              <li>Comply with applicable legal and regulatory requirements</li>
            </ul>
            
            <p>
              We strive to use your personal information only for purposes that are relevant and necessary to provide and improve YogaRoots services.
            </p>
            
            <h3>3. Protection of Your Information</h3>
            
            <p>
              YogaRoots takes reasonable administrative, technical, and organizational measures to protect your personal information from unauthorized access, use, alteration, disclosure, or loss.
            </p>
            
            <p>
              However, no storage system or transmission of information over the internet can be guaranteed to be completely secure. Therefore, while we make reasonable efforts to protect your information, we cannot guarantee absolute security.
            </p>
            
            <h3>4. Third-Party Services</h3>
            
            <p>
              To operate and improve our services, YogaRoots may work with trusted third-party service providers, including technology providers, payment processors, authentication services, analytics providers, communication platforms, and other service partners.
            </p>
            
            <p>
              Information may be shared with these third parties only to the extent reasonably necessary to provide, maintain, or improve the relevant services.
            </p>
            
            <p>
              Third-party providers may have their own privacy policies and terms of use. We encourage you to review the privacy policies of any third-party services you choose to use.
            </p>
            
            <h3>5. Cookies</h3>
            
            <p>
              The YogaRoots website may use cookies and similar technologies to help the website function properly, remember your preferences, understand how visitors use our website, and improve your overall experience.
            </p>
            
            <p>
              You can manage or disable cookies through your browser settings. Please note that disabling certain cookies may affect the functionality or availability of some features on our website.
            </p>
            
            <h3>6. Information You Provide to Us</h3>
            
            <p>
              When you contact YogaRoots through our contact forms, email, WhatsApp, or other communication channels, the information you provide may be used to respond to your inquiry and provide the assistance or information you need.
            </p>
            
            <p>
              We do not sell or rent your personal information to third parties.
            </p>
            
            <h3>7. Data Retention</h3>
            
            <p>
              We may retain your personal information for as long as necessary to provide our services, fulfill the purposes for which the information was collected, complete transactions, comply with legal obligations, or address legitimate administrative and operational needs.
            </p>
            
            <p>
              When your information is no longer required, we may delete or anonymize it in accordance with our internal policies and applicable laws and regulations.
            </p>
            
            <h3>8. Your Rights</h3>
            
            <p>
              You may contact YogaRoots if you wish to:
            </p>
            
            <ul>
              <li>Update your personal information</li>
              <li>Correct inaccurate or incomplete information</li>
              <li>Ask about the personal information we hold about you</li>
              <li>Request the deletion of certain information, where permitted and where such deletion does not conflict with applicable legal or service requirements</li>
              <li>Ask how your personal information is collected, used, or processed</li>
            </ul>
            
            <p>
              We will review and respond to requests in accordance with applicable laws, our policies, and the circumstances of each request.
            </p>
            
            <h3>9. Children\'s Privacy</h3>
            
            <p>
              YogaRoots respects the privacy of children and does not knowingly intend to collect personal information from children without appropriate involvement or consent from a parent or legal guardian.
            </p>
            
            <p>
              If you believe that a child has provided personal information to us without appropriate consent, please contact us so that we can review the matter and take appropriate action.
            </p>
            
            <h3>10. Changes to This Privacy Policy</h3>
            
            <p>
              YogaRoots may update this Privacy Policy from time to time to reflect changes in our services, technology, business practices, or applicable laws and regulations.
            </p>
            
            <p>
              Any changes will be published on this page, and the "Last Updated" date will be revised accordingly.
            </p>
            
            <p>
              We encourage you to review this page periodically to stay informed about how we collect, use, and protect your personal information.
            </p>
            
            <h3>11. Contact Us</h3>
            
            <p>
              If you have any questions about this Privacy Policy or how YogaRoots handles your personal information, please contact us through the following channels:
            </p>
            
            <p>
              <strong>YogaRoots</strong>
            </p>
            
            <p>
              <strong>Email:</strong>&nbsp;
            </p>
            
            <p>
              <strong>Phone / WhatsApp:</strong> +62 813 2122 1270
            </p>
            
            <p>
              <strong>Address:</strong> Roots Prasasta Building, Jl. Kawi Raya No.37, RT.6/RW.2, Guntur, Setiabudi, South Jakarta City, Jakarta 12980
            </p>
            
            <p>
              We will do our best to assist you and provide the information you need regarding your privacy and the handling of your personal information.
            </p>',
                        'featured_image' => 'pages/IdsWYQovYBJBga5egnOOcsI2aLHXwwcpfFwmBILa.png',
                        'is_published' => 1,
                        'published_at' => '2026-08-31 00:00:00',
                        'user_uuid' => null,
                        'has_sidebar' => 0,
                    ],
                    1 => [
                        'uuid' => '9e55815c-a4dd-4df2-8cb5-27f7de7249fd',
                        'title' => 'Terms & Conditions',
                        'slug' => 'terms-conditions',
                        'excerpt' => 'By accessing or using the YogaRoots website and services, you acknowledge that you have read, understood, and agreed to these Terms & Conditions.',
                        'content' => '`<strong style="letter-spacing: 0.1px;">Last Updated:</strong><span style="letter-spacing: 0.1px;"> August 30, 2026</span><p>
              Welcome to <strong>YogaRoots</strong>.
            </p>
            
            <p>
              These Terms &amp; Conditions explain the rules and guidelines for using the YogaRoots website, purchasing memberships, booking classes, making payments, and using our services.
            </p>
            
            <p>
              By accessing or using the YogaRoots website and services, you acknowledge that you have read, understood, and agreed to these Terms &amp; Conditions.
            </p>
            
            <h3>1. Website Use</h3>
            
            <p>
              You agree to use the YogaRoots website responsibly and only for lawful purposes.
            </p>
            
            <p>
              You must not use the website to:
            </p>
            
            <ul>
              <li>Provide false, misleading, or inaccurate information</li>
              <li>Attempt to gain unauthorized access to accounts or systems</li>
              <li>Interfere with or disrupt the operation of the website</li>
              <li>Use the website for fraudulent, abusive, or unlawful activities</li>
              <li>Copy, reproduce, or misuse YogaRoots content without permission</li>
            </ul>
            
            <p>
              YogaRoots reserves the right to restrict or suspend access to the website or services if we reasonably believe that these Terms &amp; Conditions have been violated.
            </p>
            
            <h3>2. Account Registration</h3>
            
            <p>
              Certain YogaRoots services may require you to create an account.
            </p>
            
            <p>
              When creating an account, you agree to provide accurate and up-to-date information and to keep your account information secure.
            </p>
            
            <ul>
              <li>You are responsible for maintaining the confidentiality of your login credentials.</li>
              <li>You are responsible for activities performed through your account.</li>
              <li>You must notify YogaRoots if you believe your account has been accessed without authorization.</li>
              <li>You must not create an account using another person\'s identity or information without permission.</li>
            </ul>
            
            <h3>3. Membership</h3>
            
            <p>
              YogaRoots offers different membership packages and options designed to suit different practice needs.
            </p>
            
            <p>
              Each membership option may have different pricing, class quota, duration, benefits, and conditions.
            </p>
            
            <ul>
              <li>Memberships are personal and may only be used by the registered member.</li>
              <li>Memberships cannot be transferred, resold, or shared with another person.</li>
              <li>A membership becomes active after the payment has been successfully completed and confirmed.</li>
              <li>Membership validity follows the duration stated in the selected membership option.</li>
              <li>Unused class quota may expire when the membership period ends.</li>
              <li>Unlimited memberships may provide unlimited class access during the applicable membership period, subject to class availability and booking requirements.</li>
            </ul>
            
            <p>
              YogaRoots reserves the right to update membership packages, pricing, benefits, availability, or terms from time to time.
            </p>
            
            <h3>4. Membership Options</h3>
            
            <p>
              A membership package may contain one or more membership options. Each option may have its own price, duration, and class quota.
            </p>
            
            <p>
              When purchasing a membership, you are responsible for reviewing the selected option before completing your payment.
            </p>
            
            <p>
              The membership option selected at checkout determines the applicable price, duration, quota, and benefits associated with your membership.
            </p>
            
            <h3>5. Class Booking</h3>
            
            <p>
              Class availability is limited and subject to the capacity of each scheduled class.
            </p>
            
            <p>
              Members must complete the booking process through the available YogaRoots booking system before attending a class.
            </p>
            
            <ul>
              <li>A booking is considered confirmed once the booking process has been successfully completed.</li>
              <li>Class availability may change at any time based on capacity.</li>
              <li>Members are encouraged to arrive at least 10 minutes before the scheduled class.</li>
              <li>Late arrival may result in the member being unable to join the class if entering the studio may disturb the ongoing practice.</li>
              <li>Members must follow the instructions provided by the instructor and YogaRoots staff.</li>
            </ul>
            
            <h3>6. Class Cancellation &amp; No-Show</h3>
            
            <p>
              We understand that plans can change. However, we ask members to cancel their bookings as early as possible so that another member may have the opportunity to attend.
            </p>
            
            <ul>
              <li>Class cancellations must be made through the available YogaRoots booking system.</li>
              <li>Late cancellations may result in the applicable class quota being deducted.</li>
              <li>No-shows may result in the applicable class quota being deducted.</li>
              <li>Repeated cancellations or no-shows may affect future booking privileges.</li>
            </ul>
            
            <p>
              Specific cancellation periods or policies may apply to certain classes, workshops, events, or special programs.
            </p>
            
            <h3>7. Payments</h3>
            
            <p>
              All prices displayed on the YogaRoots website are stated in Indonesian Rupiah (IDR), unless otherwise specified.
            </p>
            
            <ul>
              <li>A membership or service is considered purchased only after payment has been successfully completed.</li>
              <li>Prices, discounts, and promotional offers may change from time to time.</li>
              <li>Promotional prices may only be available for a specific period or under specific conditions.</li>
              <li>The final amount payable will be displayed during the checkout process before payment is completed.</li>
              <li>Payment transactions may be processed through third-party payment providers.</li>
            </ul>
            
            <p>
              By proceeding with a payment, you agree to the applicable payment terms and conditions provided by YogaRoots and the relevant payment provider.
            </p>
            
            <h3>8. Refunds &amp; Cancellations</h3>
            
            <p>
              Refund eligibility may depend on the type of purchase, payment status, membership status, booking status, and applicable YogaRoots policies.
            </p>
            
            <p>
              Membership purchases and completed payments may not be refundable unless otherwise stated or required under applicable laws and regulations.
            </p>
            
            <p>
              If you believe that a payment was made incorrectly or you experience a payment-related issue, please contact YogaRoots as soon as possible so that we can review the transaction.
            </p>
            
            <h3>9. Studio Rules</h3>
            
            <p>
              YogaRoots is a shared space where everyone should feel comfortable, safe, and respected.
            </p>
            
            <p>
              Members are expected to:
            </p>
            
            <ul>
              <li>Respect instructors, staff, and fellow members.</li>
              <li>Keep phones on silent during classes.</li>
              <li>Maintain cleanliness and cleanliness of shared studio areas.</li>
              <li>Return studio equipment after use.</li>
              <li>Follow instructor and staff instructions.</li>
              <li>Avoid behavior that may disturb or make other members uncomfortable.</li>
              <li>Take reasonable care of YogaRoots facilities and equipment.</li>
            </ul>
            
            <p>
              YogaRoots may take reasonable action if a member\'s behavior negatively affects the safety, comfort, or experience of other members.
            </p>
            
            <h3>10. Health &amp; Safety</h3>
            
            <p>
              Yoga is a physical activity and may involve physical risks. Every member is responsible for practicing according to their own physical ability and condition.
            </p>
            
            <ul>
              <li>Members should inform the instructor about relevant physical limitations, injuries, or conditions before participating.</li>
              <li>Members should not force movements beyond their comfortable limits.</li>
              <li>If you experience pain, dizziness, discomfort, or other concerning symptoms, stop practicing and inform the instructor immediately.</li>
              <li>Members are responsible for determining whether they are physically able to participate in a class.</li>
            </ul>
            
            <p>
              YogaRoots and its instructors are not a substitute for professional medical advice, diagnosis, or treatment.
            </p>
            
            <p>
              If you have concerns about whether yoga or a particular class is suitable for you, we recommend consulting a qualified healthcare professional before participating.
            </p>
            
            <h3>11. Personal Belongings</h3>
            
            <p>
              Members are responsible for their personal belongings while visiting YogaRoots.
            </p>
            
            <p>
              We recommend keeping valuable items secure and bringing only essential belongings into the studio.
            </p>
            
            <p>
              YogaRoots is not responsible for loss, theft, or damage to personal belongings except where responsibility cannot legally be excluded.
            </p>
            
            <h3>12. Class Schedules &amp; Instructors</h3>
            
            <p>
              YogaRoots makes reasonable efforts to maintain the published class schedule. However, schedules and instructors may occasionally change due to operational requirements, instructor availability, maintenance, special events, or other circumstances.
            </p>
            
            <p>
              YogaRoots reserves the right to modify, reschedule, replace, or cancel a class when necessary.
            </p>
            
            <p>
              Where reasonably possible, YogaRoots will provide notice of significant schedule changes to affected members.
            </p>
            
            <h3>13. Promotions &amp; Special Offers</h3>
            
            <p>
              YogaRoots may offer promotional packages, discounts, introductory offers, events, workshops, or other special programs from time to time.
            </p>
            
            <p>
              Each promotion may have its own eligibility requirements, validity period, pricing, limitations, and terms.
            </p>
            
            <ul>
              <li>Promotions may only be available for a limited period.</li>
              <li>Promotions may not be combined with other offers unless explicitly stated.</li>
              <li>Promotional benefits cannot be exchanged for cash unless otherwise stated.</li>
              <li>YogaRoots may modify or discontinue a promotion in accordance with its applicable terms.</li>
            </ul>
            
            <h3>14. Intellectual Property</h3>
            
            <p>
              All content available on the YogaRoots website, including logos, photographs, graphics, illustrations, text, videos, designs, and other materials, is owned by or licensed to YogaRoots unless otherwise stated.
            </p>
            
            <p>
              You may access and use the content for personal, non-commercial purposes only.
            </p>
            
            <p>
              You may not reproduce, distribute, modify, publish, sell, or commercially use YogaRoots content without prior written permission.
            </p>
            
            <h3>15. Third-Party Services</h3>
            
            <p>
              YogaRoots may use or provide access to third-party services, including payment providers, communication platforms, analytics services, authentication services, and other technology providers.
            </p>
            
            <p>
              Your use of third-party services may be subject to the terms and policies of the relevant third-party provider.
            </p>
            
            <p>
              YogaRoots is not responsible for the policies, availability, or practices of third-party services that are outside our control.
            </p>
            
            <h3>16. Website Availability</h3>
            
            <p>
              YogaRoots aims to keep the website and services available and functioning properly. However, we cannot guarantee that the website will always be available, uninterrupted, or free from errors.
            </p>
            
            <p>
              Website availability may occasionally be affected by maintenance, technical issues, network problems, security incidents, or circumstances beyond our reasonable control.
            </p>
            
            <h3>17. Limitation of Responsibility</h3>
            
            <p>
              YogaRoots strives to provide a safe, comfortable, and high-quality experience for all members.
            </p>
            
            <p>
              However, participation in yoga and physical activities involves inherent risks. Members are responsible for practicing within their own capabilities and following reasonable instructions provided by instructors and staff.
            </p>
            
            <p>
              Nothing in these Terms &amp; Conditions is intended to exclude or limit any rights, responsibilities, or liabilities that cannot legally be excluded or limited under applicable laws and regulations.
            </p>
            
            <h3>18. Changes to These Terms</h3>
            
            <p>
              YogaRoots may update these Terms &amp; Conditions from time to time to reflect changes in our services, business practices, technology, or applicable laws and regulations.
            </p>
            
            <p>
              Any changes will be published on this page, and the "Last Updated" date will be revised accordingly.
            </p>
            
            <p>
              We encourage you to review this page periodically to stay informed about the terms that apply to your use of YogaRoots services.
            </p>
            
            <h3>19. Contact Us</h3>
            
            <p>
              If you have any questions about these Terms &amp; Conditions, membership, class bookings, payments, or other YogaRoots services, please contact us through the following channels:
            </p>
            
            <p>
              <strong>YogaRoots</strong>
            </p>
            
            <p>
              <strong>Email:</strong>&nbsp;
            </p>
            
            <p>
              <strong>Phone / WhatsApp:</strong> +62 813 2122 1270
            </p>
            
            <p>
              <strong>Address:</strong> Roots Prasasta Building, Jl. Kawi Raya No.37, RT.6/RW.2, Guntur, Setiabudi, South Jakarta City, Jakarta 12980
            </p>
            
            <p>
              We will do our best to assist you and provide the information you need regarding our services, memberships, bookings, and these Terms &amp; Conditions.
            </p>',
                        'featured_image' => 'pages/Qkwjsv6aDAywwYYuAwCuczs5RwcduTLTBUI7lWWI.jpg',
                        'is_published' => 1,
                        'published_at' => '2026-09-13 00:00:00',
                        'user_uuid' => null,
                        'has_sidebar' => 0,
                    ],
                ],
                'articles' => [
                    0 => [
                        'uuid' => '63829ea9-8a32-4f82-94e1-7299120f822c',
                        'user_uuid' => '00000000-0000-4000-8000-000000000001',
                        'category_uuid' => '2162d145-9ef3-4e2f-8c55-81971a015bc5',
                        'title' => 'Pilates vs Yoga: Apa Bedanya dan Mana yang Cocok untuk Anda?',
                        'slug' => 'pilates-vs-yoga-apa-bedanya-dan-mana-yang-cocok-untuk-anda',
                        'excerpt' => 'Pilates dan yoga sama-sama populer sebagai olahraga yang membantu meningkatkan kebugaran tubuh sekaligus memberikan manfaat bagi pikiran. Keduanya juga dapat dilakukan oleh pemula dan tidak selalu membutuhkan peralatan yang rumit.',
                        'content' => '<p><strong>Pilates vs Yoga: Apa Bedanya dan Mana yang Cocok untuk Anda?</strong></p>
            
            <p>Pilates dan yoga sama-sama populer sebagai olahraga yang membantu meningkatkan kebugaran tubuh sekaligus memberikan manfaat bagi pikiran. Keduanya juga dapat dilakukan oleh pemula dan tidak selalu membutuhkan peralatan yang rumit.</p>
            
            <p>Namun, <strong>Pilates dan yoga memiliki beberapa perbedaan</strong>, mulai dari tujuan latihan, teknik gerakan, fokus tubuh, hingga pendekatan terhadap pernapasan. Lalu, mana yang lebih cocok untuk Anda?</p>
            
            <p>Berikut pembahasan lengkap mengenai perbedaan Pilates dan yoga agar Anda dapat menentukan jenis latihan yang sesuai dengan kebutuhan dan tujuan kebugaran Anda.</p>
            
            <p><strong>Apa Itu Yoga?</strong></p>
            
            <p>Yoga adalah latihan yang menggabungkan gerakan tubuh, pengaturan pernapasan, konsentrasi, dan relaksasi. Yoga memiliki berbagai jenis dan tingkat intensitas, mulai dari latihan yang lembut hingga latihan yang cukup dinamis.</p>
            
            <p>Selain membantu menjaga kebugaran tubuh, yoga dapat membantu meningkatkan fleksibilitas, keseimbangan, mobilitas, serta membantu tubuh menjadi lebih rileks.</p>
            
            <p>Beberapa jenis yoga yang populer antara lain:</p>
            
            <ul>
                <li><strong>Hatha Yoga</strong>, dengan gerakan yang relatif terstruktur dan cocok untuk mengenal dasar-dasar yoga.</li>
                <li><strong>Vinyasa Yoga</strong>, yang menghubungkan gerakan dengan ritme pernapasan secara lebih dinamis.</li>
                <li><strong>Yin Yoga</strong>, yang menggunakan posisi tertentu dalam durasi lebih lama untuk melatih fleksibilitas.</li>
                <li><strong>Restorative Yoga</strong>, yang berfokus pada relaksasi dengan gerakan yang lebih lembut.</li>
            </ul>
            
            <p><strong>Apa Itu Pilates?</strong></p>
            
            <p>Pilates merupakan latihan yang berfokus pada kontrol gerakan, stabilitas tubuh, kekuatan otot inti atau <strong>core</strong>, serta postur.</p>
            
            <p>Gerakan Pilates biasanya dilakukan secara terkontrol dan membutuhkan konsentrasi terhadap posisi tubuh serta teknik pernapasan.</p>
            
            <p>Pilates dapat dilakukan menggunakan matras maupun peralatan tertentu. Mat Pilates, misalnya, dapat dilakukan tanpa peralatan besar sehingga relatif mudah dimasukkan ke dalam rutinitas olahraga.</p>
            
            <p>Latihan Pilates banyak menekankan penggunaan otot core sehingga dapat membantu membangun kesadaran terhadap postur dan kontrol tubuh.</p>
            
            <p><strong>Perbedaan Yoga dan Pilates</strong></p>
            
            <p>Meskipun terlihat mirip, yoga dan Pilates memiliki fokus latihan yang berbeda. Berikut beberapa perbedaan utamanya.</p>
            
            <p><strong>1. Fokus Latihan</strong></p>
            
            <p>Yoga memiliki pendekatan yang lebih luas dengan menggabungkan latihan fisik, pernapasan, konsentrasi, relaksasi, dan kesadaran tubuh.</p>
            
            <p>Sementara itu, Pilates lebih menitikberatkan pada kontrol gerakan, stabilitas, kekuatan core, dan postur tubuh.</p>
            
            <p><strong>2. Fleksibilitas</strong></p>
            
            <p>Yoga umumnya sangat erat kaitannya dengan latihan fleksibilitas. Berbagai posisi yoga dapat membantu tubuh bergerak melalui rentang gerak yang lebih luas secara bertahap.</p>
            
            <p>Pilates juga melibatkan gerakan yang dapat membantu mobilitas dan fleksibilitas, tetapi fokus utamanya bukan hanya pada kelenturan tubuh.</p>
            
            <p><strong>3. Kekuatan Core</strong></p>
            
            <p>Pilates sangat dikenal dengan latihan yang melibatkan otot core. Banyak gerakannya membutuhkan stabilisasi bagian tengah tubuh agar gerakan dapat dilakukan dengan tepat.</p>
            
            <p>Yoga juga melatih core, terutama pada berbagai pose yang membutuhkan keseimbangan dan stabilitas. Namun, fokusnya dapat berbeda tergantung jenis yoga yang dilakukan.</p>
            
            <p><strong>4. Pernapasan</strong></p>
            
            <p>Pernapasan merupakan bagian penting dalam yoga. Pengaturan napas digunakan untuk membantu konsentrasi, mengontrol gerakan, dan menciptakan kondisi tubuh yang lebih rileks.</p>
            
            <p>Dalam Pilates, pernapasan juga penting dan digunakan untuk membantu kontrol serta koordinasi gerakan.</p>
            
            <p><strong>5. Relaksasi</strong></p>
            
            <p>Yoga umumnya memberikan perhatian lebih besar terhadap relaksasi, meditasi, dan kesadaran tubuh.</p>
            
            <p>Pilates lebih berorientasi pada latihan fisik dan kontrol gerakan, meskipun latihan yang dilakukan dengan fokus dan teratur juga dapat membantu tubuh terasa lebih rileks setelah berolahraga.</p>
            
            <p><strong>Pilates vs Yoga: Mana yang Lebih Baik?</strong></p>
            
            <p>Tidak ada jawaban bahwa Pilates selalu lebih baik daripada yoga, atau sebaliknya. Pilihan terbaik bergantung pada tujuan, preferensi, dan kebutuhan masing-masing orang.</p>
            
            <p>Jika Anda ingin meningkatkan fleksibilitas, melatih keseimbangan, sekaligus mendapatkan latihan yang menggabungkan gerakan dan relaksasi, <strong>yoga dapat menjadi pilihan yang menarik</strong>.</p>
            
            <p>Jika tujuan utama Anda adalah meningkatkan kekuatan core, kontrol tubuh, dan kesadaran terhadap postur, <strong>Pilates dapat menjadi pilihan yang sesuai</strong>.</p>
            
            <p>Namun, keduanya juga dapat dikombinasikan. Yoga dan Pilates memiliki karakteristik latihan yang berbeda sehingga dapat saling melengkapi dalam rutinitas kebugaran.</p>
            
            <p><strong>Yoga Cocok untuk Siapa?</strong></p>
            
            <p>Yoga dapat menjadi pilihan bagi Anda yang ingin:</p>
            
            <ul>
                <li>Meningkatkan fleksibilitas tubuh.</li>
                <li>Melatih keseimbangan dan mobilitas.</li>
                <li>Meningkatkan kesadaran terhadap tubuh dan pernapasan.</li>
                <li>Melakukan aktivitas fisik dengan intensitas yang dapat disesuaikan.</li>
                <li>Menambahkan latihan relaksasi dalam rutinitas harian.</li>
                <li>Memulai aktivitas olahraga secara bertahap.</li>
            </ul>
            
            <p><strong>Pilates Cocok untuk Siapa?</strong></p>
            
            <p>Pilates dapat menjadi pilihan bagi Anda yang ingin:</p>
            
            <ul>
                <li>Melatih kekuatan otot core.</li>
                <li>Meningkatkan kontrol gerakan.</li>
                <li>Meningkatkan kesadaran terhadap postur tubuh.</li>
                <li>Melatih stabilitas dan koordinasi.</li>
                <li>Melakukan latihan dengan gerakan yang terkontrol.</li>
            </ul>
            
            <p><strong>Apakah Yoga dan Pilates Bisa Dilakukan Bersamaan?</strong></p>
            
            <p>Tentu saja. Yoga dan Pilates dapat menjadi kombinasi latihan yang menarik.</p>
            
            <p>Pilates dapat membantu melatih kekuatan dan stabilitas tubuh, sedangkan yoga dapat memberikan latihan fleksibilitas, keseimbangan, mobilitas, serta relaksasi.</p>
            
            <p>Anda dapat melakukan Pilates pada beberapa hari dalam seminggu dan menambahkan sesi yoga pada hari lainnya. Frekuensi dan intensitas latihan sebaiknya disesuaikan dengan kemampuan tubuh dan rutinitas masing-masing.</p>
            
            <p><strong>Tips Memilih Yoga atau Pilates</strong></p>
            
            <p>Sebelum memilih, coba tentukan tujuan utama Anda.</p>
            
            <p><strong>Pilih yoga jika Anda lebih tertarik pada:</strong></p>
            
            <ul>
                <li>Fleksibilitas.</li>
                <li>Keseimbangan.</li>
                <li>Mobilitas.</li>
                <li>Pernapasan.</li>
                <li>Relaksasi dan mindfulness.</li>
            </ul>
            
            <p><strong>Pertimbangkan Pilates jika Anda lebih tertarik pada:</strong></p>
            
            <ul>
                <li>Kekuatan core.</li>
                <li>Stabilitas tubuh.</li>
                <li>Kontrol gerakan.</li>
                <li>Postur.</li>
                <li>Latihan fisik yang terstruktur.</li>
            </ul>
            
            <p>Jika masih bingung, tidak ada salahnya mencoba keduanya. Pengalaman langsung dapat membantu Anda mengetahui latihan mana yang paling nyaman dan sesuai dengan kebutuhan Anda.</p>
            
            <p><strong>Kesimpulan</strong></p>
            
            <p>Jadi, <strong>apa perbedaan Pilates dan yoga?</strong> Secara sederhana, yoga memiliki pendekatan yang lebih luas dengan menggabungkan gerakan, pernapasan, keseimbangan, fleksibilitas, dan relaksasi. Sementara itu, Pilates lebih berfokus pada kontrol gerakan, stabilitas, kekuatan core, dan postur.</p>
            
            <p>Keduanya sama-sama dapat menjadi bagian dari gaya hidup aktif. Yang terpenting adalah memilih latihan yang sesuai dengan tujuan dan dapat dilakukan secara konsisten.</p>
            
            <p>Jika Anda ingin mencoba yoga, mulailah dari kelas yang sesuai dengan tingkat kemampuan Anda. Dengan instruktur yang tepat, Anda dapat mempelajari teknik dasar dengan lebih nyaman dan membangun kebiasaan latihan secara bertahap.</p>
            
            <p><strong>Siap Mencoba Yoga?</strong></p>
            
            <p>Temukan kelas yoga yang sesuai dengan kebutuhan dan tingkat kemampuan Anda. Mulai perjalanan menuju tubuh yang lebih aktif, fleksibel, dan seimbang bersama kelas yoga yang tepat.</p>',
                        'featured_image' => 'images/35YtLdm25z6qsPFxJ2tNvaIVkmym3IDjreiBUHG3.jpg',
                        'scheduled_at' => '2026-08-27 00:00:00',
                        'views' => 51,
                        'tagging' => 'yoga, tips yogaaa',
                        'status' => 'published',
                        'search_engine' => 'index',
                        'link' => null,
                        'video' => null,
                    ],
                ],
                'venues' => [
                    0 => [
                        'uuid' => '363f49dc-b34f-4ac2-ad18-95b140c6335e',
                        'name' => 'Yoga Roots Studio',
                        'slug' => 'yoga-roots-studio',
                        'excerpt' => null,
                        'description' => 'At Yoga Roots Studio, we see yoga as a journey of connection ?????? between the body, mind, and breath. Through thoughtful guidance, you??????ll deepen your practice, refine your understanding of each posture, and cultivate greater clarity, balance, and inner peace.',
                        'address' => 'Roots Prasasta Building, Jl. Kawi Raya No.37, RT.6/RW.2, Guntur, Setiabudi, South Jakarta City, Jakarta 12980',
                        'phone' => '0813-2122-1270',
                        'email' => 'info@yogaroots.id',
                        'google_maps_url' => null,
                        'image' => 'images/9K1D1onCiMxH8kdWBZ462VpFEApR9KkDIsj9PEUC.jpg',
                        'status' => 'active',
                    ],
                ],
                'events' => [
                    0 => [
                        'uuid' => '02471b9c-9346-409c-b9b0-5331d91456ed',
                        'judul' => 'Growing Together in Nature',
                        'slug' => 'growing-together-in-nature',
                        'deskripsi' => '<p><span style="color: rgb(12, 16, 20); font-family: -apple-system, BlinkMacSystemFont, &quot;Segoe UI&quot;, Roboto, Helvetica, Arial, sans-serif; font-size: 14px; letter-spacing: normal;">A special morning created for mama-to-be.</span><br style="color: rgb(12, 16, 20); font-family: -apple-system, BlinkMacSystemFont, &quot;Segoe UI&quot;, Roboto, Helvetica, Arial, sans-serif; font-size: 14px; letter-spacing: normal;"><span style="color: rgb(12, 16, 20); font-family: -apple-system, BlinkMacSystemFont, &quot;Segoe UI&quot;, Roboto, Helvetica, Arial, sans-serif; font-size: 14px; letter-spacing: normal;">Move gently, breathe deeply, connect with your little one, and enjoy a peaceful morning in nature. ?????????</span><br style="color: rgb(12, 16, 20); font-family: -apple-system, BlinkMacSystemFont, &quot;Segoe UI&quot;, Roboto, Helvetica, Arial, sans-serif; font-size: 14px; letter-spacing: normal;"><br style="color: rgb(12, 16, 20); font-family: -apple-system, BlinkMacSystemFont, &quot;Segoe UI&quot;, Roboto, Helvetica, Arial, sans-serif; font-size: 14px; letter-spacing: normal;"><span style="color: rgb(12, 16, 20); font-family: -apple-system, BlinkMacSystemFont, &quot;Segoe UI&quot;, Roboto, Helvetica, Arial, sans-serif; font-size: 14px; letter-spacing: normal;">????????? Sunday, 13 September 2026</span><br style="color: rgb(12, 16, 20); font-family: -apple-system, BlinkMacSystemFont, &quot;Segoe UI&quot;, Roboto, Helvetica, Arial, sans-serif; font-size: 14px; letter-spacing: normal;"><span style="color: rgb(12, 16, 20); font-family: -apple-system, BlinkMacSystemFont, &quot;Segoe UI&quot;, Roboto, Helvetica, Arial, sans-serif; font-size: 14px; letter-spacing: normal;">??????? 07.30??????10.00 AM</span><br style="color: rgb(12, 16, 20); font-family: -apple-system, BlinkMacSystemFont, &quot;Segoe UI&quot;, Roboto, Helvetica, Arial, sans-serif; font-size: 14px; letter-spacing: normal;"><span style="color: rgb(12, 16, 20); font-family: -apple-system, BlinkMacSystemFont, &quot;Segoe UI&quot;, Roboto, Helvetica, Arial, sans-serif; font-size: 14px; letter-spacing: normal;">????????? Kebun Raya Bogor ?????? Taman Melchior</span><br style="color: rgb(12, 16, 20); font-family: -apple-system, BlinkMacSystemFont, &quot;Segoe UI&quot;, Roboto, Helvetica, Arial, sans-serif; font-size: 14px; letter-spacing: normal;"><span style="color: rgb(12, 16, 20); font-family: -apple-system, BlinkMacSystemFont, &quot;Segoe UI&quot;, Roboto, Helvetica, Arial, sans-serif; font-size: 14px; letter-spacing: normal;">????????? 150K / pax</span><br style="color: rgb(12, 16, 20); font-family: -apple-system, BlinkMacSystemFont, &quot;Segoe UI&quot;, Roboto, Helvetica, Arial, sans-serif; font-size: 14px; letter-spacing: normal;"><br style="color: rgb(12, 16, 20); font-family: -apple-system, BlinkMacSystemFont, &quot;Segoe UI&quot;, Roboto, Helvetica, Arial, sans-serif; font-size: 14px; letter-spacing: normal;"><span style="color: rgb(12, 16, 20); font-family: -apple-system, BlinkMacSystemFont, &quot;Segoe UI&quot;, Roboto, Helvetica, Arial, sans-serif; font-size: 14px; letter-spacing: normal;">Includes Prenatal Yoga, Pregnancy Talk, Mini MCU, Garden Picnic, Goodies &amp; Giveaways! ??????</span><br style="color: rgb(12, 16, 20); font-family: -apple-system, BlinkMacSystemFont, &quot;Segoe UI&quot;, Roboto, Helvetica, Arial, sans-serif; font-size: 14px; letter-spacing: normal;"><br style="color: rgb(12, 16, 20); font-family: -apple-system, BlinkMacSystemFont, &quot;Segoe UI&quot;, Roboto, Helvetica, Arial, sans-serif; font-size: 14px; letter-spacing: normal;"><span style="color: rgb(12, 16, 20); font-family: -apple-system, BlinkMacSystemFont, &quot;Segoe UI&quot;, Roboto, Helvetica, Arial, sans-serif; font-size: 14px; letter-spacing: normal;">????????????????? Register Now:</span><br style="color: rgb(12, 16, 20); font-family: -apple-system, BlinkMacSystemFont, &quot;Segoe UI&quot;, Roboto, Helvetica, Arial, sans-serif; font-size: 14px; letter-spacing: normal;"><span style="color: rgb(12, 16, 20); font-family: -apple-system, BlinkMacSystemFont, &quot;Segoe UI&quot;, Roboto, Helvetica, Arial, sans-serif; font-size: 14px; letter-spacing: normal;">https://bit.ly/PrenatalGentleYogaatKebunRaya</span><br style="color: rgb(12, 16, 20); font-family: -apple-system, BlinkMacSystemFont, &quot;Segoe UI&quot;, Roboto, Helvetica, Arial, sans-serif; font-size: 14px; letter-spacing: normal;"><br style="color: rgb(12, 16, 20); font-family: -apple-system, BlinkMacSystemFont, &quot;Segoe UI&quot;, Roboto, Helvetica, Arial, sans-serif; font-size: 14px; letter-spacing: normal;"><span style="color: rgb(12, 16, 20); font-family: -apple-system, BlinkMacSystemFont, &quot;Segoe UI&quot;, Roboto, Helvetica, Arial, sans-serif; font-size: 14px; letter-spacing: normal;">Come grow, breathe &amp; connect with us. ??????????</span></p>',
                        'gambar' => 'images/EzgKQVJf6PLJzak8pTn6uQpjOpWRVGTCtIRr6vBv.jpg',
                        'tanggal' => '2026-09-13',
                        'waktu_mulai' => '07:30:00',
                        'waktu_selesai' => '10:00:00',
                        'lokasi' => 'Kebun Raya Bogor ?????? Taman Melchior',
                        'kapasitas' => 35,
                        'status' => 'published',
                        'created_at' => '2026-09-01 01:39:08',
                        'updated_at' => '2026-09-01 10:02:52',
                    ],
                    1 => [
                        'uuid' => '3191142d-9185-421c-be1d-791c843f6161',
                        'judul' => 'Yoga Night at the Museum is calling',
                        'slug' => 'yoga-night-at-the-museum-is-calling',
                        'deskripsi' => '<p><div class="html-div x14z9mp x1lziwak xexx8yu xyri2b x18d9i69 x1c1uobl x9f619 xjbqb8w x78zum5 x15mokao x1ga7v0g x16uus16 xbiv7yw x1xmf6yo x12nagc x1n2onr6 x1plvlek xryxfnj x1c4vz4f x2lah0s xdt5ytf xqjyukv x1qjc9v5 x1oa3qoh x1nhvcw1" style="margin-inline: 0px; border-start-start-radius: 0px; border-end-end-radius: 0px; padding-inline: 0px; flex-grow: 0; border-start-end-radius: 0px; position: relative; place-content: stretch flex-start; align-self: auto; align-items: stretch; flex-shrink: 0; display: flex; box-sizing: border-box; border-end-start-radius: 0px; flex-direction: column; background-color: rgb(255, 255, 255); margin-bottom: 4px; padding-bottom: 0px; overflow: visible; margin-top: 8px; padding-top: 0px; color: rgb(12, 16, 20); font-family: -apple-system, BlinkMacSystemFont, &quot;Segoe UI&quot;, Roboto, Helvetica, Arial, sans-serif; font-size: 14px; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400; letter-spacing: normal; orphans: 2; text-align: start; text-indent: 0px; text-transform: none; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; white-space: normal; text-decoration-thickness: initial; text-decoration-style: initial; text-decoration-color: initial;"></div></p><div class="xt0psk2" style="display: inline; color: rgb(12, 16, 20); font-family: -apple-system, BlinkMacSystemFont, &quot;Segoe UI&quot;, Roboto, Helvetica, Arial, sans-serif; font-size: 14px; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400; letter-spacing: normal; orphans: 2; text-align: start; text-indent: 0px; text-transform: none; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; white-space: normal; background-color: rgb(255, 255, 255); text-decoration-thickness: initial; text-decoration-style: initial; text-decoration-color: initial;"><h1 class="_ap3a _aaco _aacu _aacx _aad7 _aade" dir="auto" style="color: rgb(12, 16, 20); font-size: 14px; font-weight: 400; margin: 0px !important; padding: 0px; font-family: -apple-system, BlinkMacSystemFont, &quot;Segoe UI&quot;, Roboto, Helvetica, Arial, sans-serif; line-height: 18px; display: inline !important;">Rasakan sensasi ketenangan malam dan keseimbangan jiwa dalam perayaan Anniversary Yoga Roots yang ke 3 tahun??????????<br><br>????????? Jumat, 18 September 2026<br>??????? 18.30-19.30 WIB<br>????????? Taman Arca, Museum Nasional Indonesia<br>????????????????? IDR 150K ?????? sudah termasuk tiket masuk museum<br><br>Jangan lupa bawa yoga mat &amp; botol minum pribadi.<br><br>Yuk, luangkan waktu untuk move, breathe, and reconnect di malam yang spesial?????????</h1></div>',
                        'gambar' => 'images/N9LlRBNsALvgxk1xTC1tr4pFbig3r0Jo63MWMrQf.jpg',
                        'tanggal' => '2026-09-18',
                        'waktu_mulai' => '18:30:00',
                        'waktu_selesai' => '19:30:00',
                        'lokasi' => 'Taman Arca, Museum Nasional Indonesia',
                        'kapasitas' => 50,
                        'status' => 'published',
                        'created_at' => '2026-09-11 13:50:04',
                        'updated_at' => '2026-09-11 14:28:46',
                    ],
                    2 => [
                        'uuid' => '64e369a4-ed0f-4009-996e-362f879a681e',
                        'judul' => 'Sunset Serenity',
                        'slug' => 'sunset-serenity',
                        'deskripsi' => '<p><span style="color: rgb(12, 16, 20); font-family: -apple-system, BlinkMacSystemFont, &quot;Segoe UI&quot;, Roboto, Helvetica, Arial, sans-serif; font-size: 14px; letter-spacing: normal;">Take a moment to slow down, breathe deeply, and reconnect with yourself as the sunsets.</span><br style="color: rgb(12, 16, 20); font-family: -apple-system, BlinkMacSystemFont, &quot;Segoe UI&quot;, Roboto, Helvetica, Arial, sans-serif; font-size: 14px; letter-spacing: normal;"><span style="color: rgb(12, 16, 20); font-family: -apple-system, BlinkMacSystemFont, &quot;Segoe UI&quot;, Roboto, Helvetica, Arial, sans-serif; font-size: 14px; letter-spacing: normal;">Join us for a relaxing Sunset Yoga Session with Coach Mega at Balcony Skechers, Senayan Park.</span><br style="color: rgb(12, 16, 20); font-family: -apple-system, BlinkMacSystemFont, &quot;Segoe UI&quot;, Roboto, Helvetica, Arial, sans-serif; font-size: 14px; letter-spacing: normal;"><br style="color: rgb(12, 16, 20); font-family: -apple-system, BlinkMacSystemFont, &quot;Segoe UI&quot;, Roboto, Helvetica, Arial, sans-serif; font-size: 14px; letter-spacing: normal;"><span style="color: rgb(12, 16, 20); font-family: -apple-system, BlinkMacSystemFont, &quot;Segoe UI&quot;, Roboto, Helvetica, Arial, sans-serif; font-size: 14px; letter-spacing: normal;">????????? 20 September 2026</span><br style="color: rgb(12, 16, 20); font-family: -apple-system, BlinkMacSystemFont, &quot;Segoe UI&quot;, Roboto, Helvetica, Arial, sans-serif; font-size: 14px; letter-spacing: normal;"><span style="color: rgb(12, 16, 20); font-family: -apple-system, BlinkMacSystemFont, &quot;Segoe UI&quot;, Roboto, Helvetica, Arial, sans-serif; font-size: 14px; letter-spacing: normal;">????????? Balcony Skechers, Senayan Park</span><br style="color: rgb(12, 16, 20); font-family: -apple-system, BlinkMacSystemFont, &quot;Segoe UI&quot;, Roboto, Helvetica, Arial, sans-serif; font-size: 14px; letter-spacing: normal;"><span style="color: rgb(12, 16, 20); font-family: -apple-system, BlinkMacSystemFont, &quot;Segoe UI&quot;, Roboto, Helvetica, Arial, sans-serif; font-size: 14px; letter-spacing: normal;">????????? 4:30 PM</span><br style="color: rgb(12, 16, 20); font-family: -apple-system, BlinkMacSystemFont, &quot;Segoe UI&quot;, Roboto, Helvetica, Arial, sans-serif; font-size: 14px; letter-spacing: normal;"><span style="color: rgb(12, 16, 20); font-family: -apple-system, BlinkMacSystemFont, &quot;Segoe UI&quot;, Roboto, Helvetica, Arial, sans-serif; font-size: 14px; letter-spacing: normal;">????????????????????????????? Beginner Friendly</span><br style="color: rgb(12, 16, 20); font-family: -apple-system, BlinkMacSystemFont, &quot;Segoe UI&quot;, Roboto, Helvetica, Arial, sans-serif; font-size: 14px; letter-spacing: normal;"><span style="color: rgb(12, 16, 20); font-family: -apple-system, BlinkMacSystemFont, &quot;Segoe UI&quot;, Roboto, Helvetica, Arial, sans-serif; font-size: 14px; letter-spacing: normal;">????????? Only IDR 65,000</span><br style="color: rgb(12, 16, 20); font-family: -apple-system, BlinkMacSystemFont, &quot;Segoe UI&quot;, Roboto, Helvetica, Arial, sans-serif; font-size: 14px; letter-spacing: normal;"><span style="color: rgb(12, 16, 20); font-family: -apple-system, BlinkMacSystemFont, &quot;Segoe UI&quot;, Roboto, Helvetica, Arial, sans-serif; font-size: 14px; letter-spacing: normal;">????????? Includes Goodie Bag</span><br style="color: rgb(12, 16, 20); font-family: -apple-system, BlinkMacSystemFont, &quot;Segoe UI&quot;, Roboto, Helvetica, Arial, sans-serif; font-size: 14px; letter-spacing: normal;"><br style="color: rgb(12, 16, 20); font-family: -apple-system, BlinkMacSystemFont, &quot;Segoe UI&quot;, Roboto, Helvetica, Arial, sans-serif; font-size: 14px; letter-spacing: normal;"><span style="color: rgb(12, 16, 20); font-family: -apple-system, BlinkMacSystemFont, &quot;Segoe UI&quot;, Roboto, Helvetica, Arial, sans-serif; font-size: 14px; letter-spacing: normal;">Come as you are, flow at your own pace, and end the day feeling refreshed.</span><br style="color: rgb(12, 16, 20); font-family: -apple-system, BlinkMacSystemFont, &quot;Segoe UI&quot;, Roboto, Helvetica, Arial, sans-serif; font-size: 14px; letter-spacing: normal;"><span style="color: rgb(12, 16, 20); font-family: -apple-system, BlinkMacSystemFont, &quot;Segoe UI&quot;, Roboto, Helvetica, Arial, sans-serif; font-size: 14px; letter-spacing: normal;">Save your spot and let the sunset be your reset.</span></p>',
                        'gambar' => 'images/VAc3BAY1XfYy0ItLTmUPVwKwJtw8cloQkByt8vBO.jpg',
                        'tanggal' => '2026-09-20',
                        'waktu_mulai' => '16:30:00',
                        'waktu_selesai' => '16:30:00',
                        'lokasi' => 'Balcony Skechers, Senayan Park',
                        'kapasitas' => 50,
                        'status' => 'published',
                        'created_at' => '2026-09-11 14:28:06',
                        'updated_at' => '2026-09-11 14:28:06',
                    ],
                ],
            ];

            foreach ($data['banner'] as $row) {
                Banner::updateOrCreate(['uuid' => $row['uuid']], $row);
            }

            foreach ($data['categories'] as $row) {
                Category::updateOrCreate(['uuid' => $row['uuid']], $row);
            }

            foreach ($data['packages'] as $row) {
                Package::updateOrCreate(['uuid' => $row['uuid']], $row);
            }

            foreach ($data['package_options'] as $row) {
                PackageOption::updateOrCreate(['uuid' => $row['uuid']], $row);
            }

            foreach ($data['package_features'] as $row) {
                PackageFeature::updateOrCreate(['uuid' => $row['uuid']], $row);
            }

            foreach ($data['vendors_categories'] as $row) {
                VendorsCategory::updateOrCreate(['uuid' => $row['uuid']], $row);
            }

            foreach ($data['testimonials'] as $row) {
                Testimonial::updateOrCreate(['uuid' => $row['uuid']], $row);
            }

            foreach ($data['pages'] as $row) {
                Page::updateOrCreate(['uuid' => $row['uuid']], $row);
            }

            foreach ($data['articles'] as $row) {
                Article::updateOrCreate(['uuid' => $row['uuid']], $row);
            }

            foreach ($data['venues'] as $row) {
                Venue::updateOrCreate(['uuid' => $row['uuid']], $row);
            }

            foreach ($data['events'] as $row) {
                Event::updateOrCreate(['uuid' => $row['uuid']], $row);
            }
        });
    }
}
