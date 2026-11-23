<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sita_synonyms', function (Blueprint $table) {
            $table->uuid('uuid')->primary();
            $table->string('locale', 2);
            $table->string('term', 80);
            $table->string('canonical', 80);
            $table->string('topic', 20)->nullable();
            $table->boolean('is_active')->default(true);
            $table->integer('sort_order')->default(0);
            $table->timestamps();

            $table->unique(['locale', 'term']);
        });

        $this->seedSynonyms();
    }

    public function down(): void
    {
        Schema::dropIfExists('sita_synonyms');
    }

    private function seedSynonyms(): void
    {
        $now = now();
        $sortOrder = 0;
        $synonyms = [
            'id' => [
                ['brapa', 'berapa', null],
                ['brp', 'berapa', null],
                ['catring', 'katering', 'vendors'],
                ['cost', 'biaya', 'packages'],
                ['costs', 'biaya', 'packages'],
                ['dp', 'pembayaran', 'packages'],
                ['fotografer', 'fotografi', 'vendors'],
                ['hrg', 'harga', 'packages'],
                ['hrga', 'harga', 'packages'],
                ['katering', 'katering', 'vendors'],
                ['catering', 'katering', 'vendors'],
                ['dekorasi', 'dekorasi', 'vendors'],
                ['dekor', 'dekorasi', 'vendors'],
                ['decorator', 'dekorasi', 'vendors'],
                ['gedung', 'gedung', 'venues'],
                ['venue', 'gedung', 'venues'],
                ['venu', 'gedung', 'venues'],
                ['venuez', 'gedung', 'venues'],
                ['venues', 'gedung', 'venues'],
                ['place', 'gedung', 'venues'],
                ['places', 'gedung', 'venues'],
                ['lokasi', 'gedung', 'venues'],
                ['tempat', 'gedung', 'venues'],
                ['makeup', 'rias', 'vendors'],
                ['mua', 'rias', 'vendors'],
                ['rias', 'rias', 'vendors'],
                ['pack', 'paket', 'packages'],
                ['package', 'paket', 'packages'],
                ['packages', 'paket', 'packages'],
                ['paket', 'paket', 'packages'],
                ['pket', 'paket', 'packages'],
                ['pkg', 'paket', 'packages'],
                ['pkt', 'paket', 'packages'],
                ['photographer', 'fotografi', 'vendors'],
                ['photography', 'fotografi', 'vendors'],
                ['fotografi', 'fotografi', 'vendors'],
                ['price', 'harga', 'packages'],
                ['prices', 'harga', 'packages'],
                ['pricing', 'harga', 'packages'],
                ['harga', 'harga', 'packages'],
                ['thnkz', 'terima kasih', null],
                ['tq', 'terima kasih', null],
                ['ty', 'terima kasih', null],
                ['vendors', 'vendor', 'vendors'],
                ['vendor', 'vendor', 'vendors'],
            ],
            'en' => [
                ['brapa', 'how much', null],
                ['brp', 'how much', null],
                ['catring', 'catering', 'vendors'],
                ['katering', 'catering', 'vendors'],
                ['catering', 'catering', 'vendors'],
                ['cost', 'price', 'packages'],
                ['costs', 'price', 'packages'],
                ['dp', 'payment', 'packages'],
                ['pembayaran', 'payment', 'packages'],
                ['decorator', 'decoration', 'vendors'],
                ['dekor', 'decoration', 'vendors'],
                ['dekorasi', 'decoration', 'vendors'],
                ['decoration', 'decoration', 'vendors'],
                ['fotografer', 'photography', 'vendors'],
                ['fotografi', 'photography', 'vendors'],
                ['photographer', 'photography', 'vendors'],
                ['photography', 'photography', 'vendors'],
                ['gedung', 'venue', 'venues'],
                ['lokasi', 'venue', 'venues'],
                ['tempat', 'venue', 'venues'],
                ['place', 'venue', 'venues'],
                ['places', 'venue', 'venues'],
                ['venue', 'venue', 'venues'],
                ['venu', 'venue', 'venues'],
                ['venuez', 'venue', 'venues'],
                ['venues', 'venue', 'venues'],
                ['makeup', 'makeup', 'vendors'],
                ['mua', 'makeup', 'vendors'],
                ['rias', 'makeup', 'vendors'],
                ['hrg', 'price', 'packages'],
                ['hrga', 'price', 'packages'],
                ['harga', 'price', 'packages'],
                ['pack', 'package', 'packages'],
                ['package', 'package', 'packages'],
                ['packages', 'package', 'packages'],
                ['paket', 'package', 'packages'],
                ['pket', 'package', 'packages'],
                ['pkg', 'package', 'packages'],
                ['pkt', 'package', 'packages'],
                ['price', 'price', 'packages'],
                ['prices', 'price', 'packages'],
                ['pricing', 'price', 'packages'],
                ['thnkz', 'thanks', null],
                ['tq', 'thanks', null],
                ['ty', 'thanks', null],
                ['vendor', 'vendor', 'vendors'],
                ['vendors', 'vendor', 'vendors'],
            ],
        ];

        foreach ($synonyms as $locale => $terms) {
            foreach ($terms as [$term, $canonical, $topic]) {
                DB::table('sita_synonyms')->insertOrIgnore([
                    'uuid' => (string) Str::uuid(),
                    'locale' => $locale,
                    'term' => $term,
                    'canonical' => $canonical,
                    'topic' => $topic,
                    'is_active' => true,
                    'sort_order' => $sortOrder++,
                    'created_at' => $now,
                    'updated_at' => $now,
                ]);
            }
        }
    }
};
