# Sagita Wedding FE — Astro 7 + Tailwind CSS v4

Frontend Sagita Wedding berbasis Astro 7 (`output: "server"`, adapter `@astrojs/node`).
Konten dinamis diambil dari backend Laravel melalui `src/lib/services/`.

## Cara jalan

Butuh Node.js >= 22.

```bash
cp .env.example .env
npm install
npm run build:css
npm run dev
```

Astro dev berjalan di `http://localhost:3000` dan mem-proxy `/api/*` ke backend.
Untuk produksi: `npm run build` lalu `npm start` (atau jalankan `dist/server/entry.mjs`
melalui process manager). Set `SITE_URL` ke origin HTTPS kanonis sebelum `npm start`,
karena validasi env produksi menolak `localhost`.

`.env` wajib memuat `BACKEND_URL`, `API_PREFIX`, `API_KEY` (sama dengan
`FRONTEND_API_KEY` di `be/.env`), `SITE_URL`, `GOOGLE_AUTH_URL`, dan
`SESSION_COOKIE_NAME`. `.env.example` memakai placeholder; jangan commit `.env`.

## Struktur

```
fe/
├── astro.config.mjs
├── package.json
├── scripts/check-assets.mjs
├── src/
│   ├── middleware.ts
│   ├── pages/
│   ├── components/
│   ├── layouts/
│   ├── lib/
│   │   ├── browser/
│   │   ├── config/
│   │   ├── data/
│   │   ├── http.ts
│   │   ├── services/
│   │   └── utils/
│   └── styles/
├── tests/
└── public/
```

- `src/pages/` — rute Astro (SSR) dan endpoint `src/pages/api/*`.
- `src/components/` + `src/layouts/` — UI reusable; hindari markup duplikat antar halaman.
- `src/lib/services/` — client backend, satu batch call per halaman, analitik Sita, identitas situs.
- `src/lib/browser/` — module interaksi browser (`main.ts`, `pages/`, `lib/`).
- `src/lib/config/` — env tervalidasi, locale, daftar rute, dan link terpusat.
- `src/lib/data/` — konten statis (site/nav/kontak/translasi) dan seeded data.
- `src/lib/http.ts` — helper `readJson` (maks. 64 KiB), `clientIp`, rate limit, security headers.
- `src/lib/utils/` — validasi, sanitasi HTML CMS, logger, error, tanggal.
- `src/middleware.ts` — nonce CSP, origin check, rate limit global, i18n, minify HTML.
- `scripts/check-assets.mjs` — budget CSS dan JS hasil build (`dist/client`).

## Aturan kerapian

- Tidak ada URL/nomor hardcode di `.astro` atau module browser; pakai `site`, `contactLinks`, atau `env`.
- Blok UI yang dipakai >1 halaman wajib jadi component/layout.
- Semua input (slug, query, body POST) wajib lewat `src/lib/utils/validate.ts`.
- HTML dari CMS wajib lewat `sanitizeRichHtml()` sebelum `set:html`; atribut `style` sengaja tidak diizinkan.
- Identitas Google Analytics dari CMS divalidasi formatnya, di-escape, dan script gtag baru dimuat setelah consent (`sagita_analytics=granted`).
- Inline script wajib menerima nonce dari `Astro.locals.nonce`; middleware tidak lagi menambahkan nonce secara otomatis.
- Query backend wajib lewat `buildQuery(params, whitelist)` di `src/lib/services/apiClient.ts`.
- Halaman publik mengambil data lewat `getPageBatch(page)` — jangan memanggil service per-resource satu per satu.
- Log dan telemetry tidak boleh memuat nomor telepon, email, token, atau teks pertanyaan mentah.
- Test harness `tests/helpers/createApp.ts` tidak menjalankan middleware Astro asli; perilaku nonce/origin/body limit/rute legacy diuji oleh `npm run test:server` pada server produksi yang sebenarnya.

## API internal

- `GET /api/batch/{page}` → passthrough batch; daftar halaman mengikuti `SUPPORTED_BATCH_PAGES` di `src/lib/services/batchService.ts`.
- `GET /api/contact/captcha`, `GET /api/booking/captcha` → soal captcha + `captcha_id`.
- `POST /api/contact` `{nama,email,no_telp,isi,captcha_id,captcha_answer}` → diteruskan ke backend.
- `POST /api/booking` `{nama,email,no_telp,paket,tanggal,isi,captcha_id,captcha_answer}` → dibuat sebagai leads konsultasi via `/api/contact`.
- `POST /api/newsletter` `{email}` → diteruskan ke backend sebagai subscriber.
- `GET /api/auth/me`, `POST /api/login`, `POST /api/logout` → session Laravel via cookie HttpOnly.
- Endpoint lama `/api/classes`, `/api/class-schedules`, `/api/batch/schedules` → `410 Gone`.

Langganan newsletter memakai rate limit 10/menit/IP + 5/jam/email di backend dan honeypot
`website`. Email duplikat dibalas 201 dengan `duplicate: true`.

## Widget Sita

Widget mencocokkan pertanyaan dengan FAQ, paket, venue, dan vendor dari database Sagita
melalui service server-side. Jawaban berbasis aturan + retrieval lokal, bukan model
generatif atau API pihak ketiga.

- NLU lokal di `src/lib/services/sitaNlu.ts`: normalisasi typo/gaul, akhiran Indonesia, fuzzy match, deteksi topik bertingkat, sapaan singkat, `confidence`, intent eksplisit (promo, DP, tanggal, testimoni, alamat), clarify, dan kategori vendor.
- `src/lib/services/sitaSemantic.ts`: TF-IDF/cosine lokal untuk FAQ yang tidak persis sama.
- Kriteria sesi (anggaran, tamu, kota, tanggal, kategori vendor) disimpan di browser dan dikirim sebagai `context`. Riwayat maksimal 20 pesan di `sessionStorage` (`sita-chat-history-v1`) berupa `{sender, text}`; kartu dan data pribadi tidak disimpan. Tombol **Reset** menghapusnya.
- `POST /api/sita/chat` `{message, locale, context?}` → jawaban + `confidence`, `topic`, `suggestions`, opsional `clarify` dan `source`. Limit 15/menit per-IP di samping limit global 20/menit.
- `POST /api/telemetry` menerima allowlist `sita.no_match`, `sita.answer`, `sita.feedback` tanpa teks pertanyaan/data pribadi; limit 40/menit.
- `GET /api/sita/analytics` (admin/super-admin) → agregat 500 event terakhir + 20 event terbaru; juga diteruskan ke Laravel `/api/sita/events`.
- `GET /api/sita/knowledge` → sinonim aktif dari `/api/sita/synonyms` dengan cache FE 60 detik.
- `POST /api/sita/leads` `{name, phone, topic, criteria?, score, message?, website?}` → lead handoff ke Laravel; `website` adalah honeypot.
- Tombol 👍/👎 hanya sekali per jawaban dan memancarkan event `sita:feedback` tanpa mengirim teks.

## Testing

```bash
npm test            # vitest run (unit + integrasi, service dimock)
npm run test:server # build + integration test server Astro asli (middleware/CSP/origin)
npm run test:a11y   # vitest a11y
npm run test:golden # evaluasi query golden Sita
npm run typecheck   # tsc
npm run lint        # biome
npm run build       # lint + typecheck + CSS + Astro build
npm run perf:assets # build lalu cek budget aset
```

Test memakai `vitest`; integrasi HTTP memakai harness `tests/helpers/createApp.ts` dan
memock service agar tidak bergantung pada MySQL.

## Halaman & route

`/`, `/about`, `/venues`, `/vendors`, `/packages`, `/packages/:slug`, `/blog`,
`/blog/:slug`, `/pages/:slug`, `/event`, `/portofolio`, `/gallery`, `/faq`,
`/formulir-konsultasi`.

Rute legacy (`/contact`, `/classes`, `/classes/:slug`, `/schedules`,
`/instructors`, `/art-of-living`) sudah dihapus dan sengaja merender 404.

## SEO

- `/sitemap.xml` dinamis: URL statis (termasuk `/gallery`) + slug packages/blog/pages dari backend, `lastmod` dari `contentUpdatedAt`/`updated_at`, cache 1 jam, fail-open.
- `/robots.txt` dinamis dengan `Sitemap:` absolut mengikuti `SITE_URL`; keduanya memakai `Cache-Control` 1 jam.
- `?lang=id` dipertahankan agar canonical dan `hreflang` bahasa Indonesia dapat dirayapi; bahasa Inggris memakai URL tanpa parameter.
- JSON-LD: `Organization`, `WebSite`, `LocalBusiness`, `WebPage`, `BlogPosting`, dan `BreadcrumbList` hanya pada halaman yang punya breadcrumb terlihat.
- OG image default 1200×630 ada di `public/img/og-default.png`; halaman dengan `heroImage` atau `ogImage` memakai gambar tersebut.
- Google Analytics baru dimuat setelah pengunjung memilih Terima; keputusan disimpan di cookie `sagita_analytics`.
- Aset `/_astro/*` yang di-hash memakai cache immutable 1 tahun; hero home memakai `preload`, `fetchpriority`, dan `width`/`height`.
- `npm start` menjalankan `scripts/serve.mjs`: HTML/CSS/JS/JSON/SVG >1KB dikompresi gzip bila client mengirim `Accept-Encoding: gzip`, dengan `Vary: Accept-Encoding`; aset biner tidak dikompresi ulang.
- `ExploreLinks` memperkuat tautan internal antar hub dan halaman detail packages/blog/pages.
- ranking #1, sitelinks, dan backlink tidak bisa dijamin oleh kode. Setelah deploy: verifikasi Search Console, kirim sitemap, minta indexing, pantau Core Web Vitals, dan bangun otoritas lewat konten lokal bermanfaat, Google Business Profile, NAP yang konsisten, serta digital PR. Hindari skema pembelian link.
