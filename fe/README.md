# YogaRoots FE — Theme (Tailwind CSS v4 + EJS + Express)

Theme frontend YogaRoots. Konten dinamis diambil dari backend Laravel via `src/services/`.

## Cara jalan

Butuh Node.js >= 22.

```bash
cp .env.example .env   # sesuaikan API_URL, SITE_URL, WHATSAPP_NUMBER
npm install
npm run build:css      # build sekali
npm run dev            # server http://localhost:3000
```

## Struktur

```
fe/
├── server.ts                 # bootstrap tipis (tidak ada logika route di sini)
├── .env / .env.example       # API_URL, SITE_URL, GOOGLE_AUTH_URL, WHATSAPP_NUMBER, ASSET_V, HTML_MINIFY, PORT
├── src/
│   ├── config/env.ts         # env tervalidasi + link terpusat (WA, OAuth) + ASSET_V + HTML_MINIFY
│   ├── config/locales.ts     # sumber tunggal locale (en|id), label, flag, format tanggal
│   ├── middleware/security.ts# helmet CSP, rate limit, origin check
│   ├── middleware/locals.ts  # site/nav/contactLinks/currentUrl/assetV (anti host-injection)
│   ├── middleware/i18n.ts    # ?lang=en|id, cookie HttpOnly, fallback ke en
│   ├── middleware/minify.ts  # HTML minify — view-source ringkas, file EJS tetap rapi
│   ├── middleware/errors.ts  # 404 + error handler
│   ├── routes/pages.ts       # semua GET halaman (satu batch call per halaman)
│   ├── routes/api.ts         # /api/* (validasi body, rate-limit, proxy batch jadwal)
│   ├── services/             # client backend (apiClient + buildQuery + per-resource)
│   │   ├── apiClient.ts      # base URL dari env, timeout 10 dtk, buildQuery whitelist
│   │   └── batchService.ts   # agregator GET /api/batch/{page}
│   ├── data/
│   │   ├── sagitaData.ts     # HANYA site/nav/contact statis
│   │   └── translations.ts   # string i18n en|id (tambah bahasa = update config/locales.ts)
│   └── utils/validate.ts     # validator input
│   └── utils/sanitize.ts     # sanitasi HTML CMS (sanitize-html)
├── views/
│   ├── partials/             # head, navbar, footer (+whatsapp-float, booking-modal, cta-help;
│   │                         # footer memuat /js/main.js SEKALI untuk semua halaman)
│   └── pages/                # 14 halaman (full HTML + include partials)
└── public/js/main.js         # interaksi (modal, form, lightbox, cookie consent)
```

## Aturan kerapian

- Tidak ada URL/nomor hardcode di views, public/js & services — lewat `contactLinks` / `env` / `site` (footer pakai `site.phone`, `site.instagram`, `contactLinks.*`; `main.js` wajib baca `data-wa-base`).
- Blok UI yang dipakai >1 halaman wajib jadi partial (`cta-help`, `booking-modal`, `whatsapp-float`; `footer` memuat `main.js` sekali — jangan duplikasi `<script>` di pages).
- Semua input (slug, query, body POST) wajib lewat `src/utils/validate.ts`.
- HTML dari CMS wajib lewat `sanitizeRichHtml()` sebelum `<%- ... %>`.
- Tidak ada `console.log` PII di route — booking hanya `console.debug` saat non-prod, error masuk `errorHandler`.
- Query backend wajib lewat `buildQuery(params, whitelist)` di `src/services/apiClient.ts`.
- Halaman publik mengambil data lewat `getPageBatch(page)` — jangan panggil service per-resource satu per satu di `pages.ts`.
- HTML yang dikirim ke browser di-minify (`minifyHtml`, default `HTML_MINIFY=1`) — view-source ringkas; set `HTML_MINIFY=0` kalau butuh view-source readable saat debug.

## API internal

- `GET /api/classes` → proxy rapi ke backend
- `GET /api/class-schedules` → jadwal kelas (filter `date`, `level`, `time`, `venue_uuid`)
- `GET /api/batch/schedules` → jadwal + daftar venue dalam satu request
- `GET /api/contact/captcha` → soal captcha + `captcha_id`
- `POST /api/contact` `{nama,email,no_telp,isi,captcha_id,captcha_answer}` → teruskan ke backend
- `POST /api/booking` → **501 Not Implemented** (backend belum punya endpoint booking; booking publik lewat WhatsApp)
- `POST /api/newsletter` → **501 Not Implemented** (belum ada fitur newsletter)

Keduanya sengaja membalas 501, bukan sukses palsu, supaya klien tidak
mengira aksi tersimpan.

## Testing

```bash
npm test          # vitest run (70 test: unit validate + integrasi supertest)
npm run typecheck # tsc server + browser
npm run lint      # biome
npm run build     # build CSS + bundle browser
```

Test memakai `vitest` + `supertest` dan **memock seluruh service** agar tidak
perlu backend MySQL.

## Halaman & route

`/`, `/about`, `/classes`, `/classes/:slug`, `/schedules`, `/pages/:slug`, `/vendors`,
`/blog`, `/blog/:slug`, `/packages`, `/packages/:slug`, `/event`,
`/gallery`, `/contact`

## SEO

- `/sitemap.xml` dinamis (10 URL statis + slug classes/packages/blog/pages dari backend, cache 1 jam, fail-open)
- `/robots.txt` dinamis dengan `Sitemap:` absolut mengikuti `SITE_URL`
