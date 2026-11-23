# Sagita Wedding FE — Theme (Tailwind CSS v4 + EJS + Express)

Theme frontend Sagita Wedding. Konten dinamis diambil dari backend Laravel via `src/services/`.

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

- `GET /api/batch/{page}` → passthrough batch; daftar page mengikuti `SUPPORTED_BATCH_PAGES` di `src/services/batchService.ts`
- `GET /api/classes`, `GET /api/class-schedules`, `GET /api/batch/schedules` → `410 Gone` (modul dihapus)
- `GET /api/contact/captcha`, `GET /api/booking/captcha` → soal captcha + `captcha_id`
- `POST /api/contact` `{nama,email,no_telp,isi,captcha_id,captcha_answer}` → teruskan ke backend
- `POST /api/booking` `{nama,email,no_telp,paket,tanggal,isi,captcha_id,captcha_answer}` → dibuat sebagai leads konsultasi via `/api/contact`
- `POST /api/newsletter` → **501 Not Implemented** (belum ada fitur newsletter)

Newsletter sengaja membalas 501, bukan sukses palsu, supaya klien tidak
mengira aksi tersimpan.

Widget Sita AI mencocokkan pertanyaan dengan FAQ, paket, venue, dan vendor dari database
Sagita melalui service server-side yang sudah ada. Meski bernama AI, jawabannya
berbasis aturan dan data, bukan model generatif atau API pihak ketiga.

Pemahaman pesan memakai NLU lokal di `src/services/sitaNlu.ts` (dipakai bersama oleh
server dan widget browser):

- normalisasi bahasa gaul/typo (mis. `pket`/`hrga`/`venu`) dan akhiran Indonesia
  (`paketnya` → `paket`) plus fuzzy match levenshtein ≤ 1 untuk FAQ;
- deteksi topik bertingkat (skor strong/weak) sehingga "harga venue" tetap dianggap
  pertanyaan venue, dan pertanyaan campuran seperti "paket sama venue" memberi chip
  topik kedua;
- sapaan/terima kasih singkat dijawab dengan menu topik, bukan fallback;
- setiap jawaban menyertakan `confidence` (0.95 sapaan/kontak, 0.8-0.9 topik/FAQ,
  0.15 fallback) sebagai penanda seberapa yakin pesan tertangkap.

Kriteria sesi (anggaran, jumlah tamu, kota, tanggal, kategori vendor) diingat di
browser selama percakapan dan dikirim sebagai `context` tanpa menyimpan teks chat.
Pesan chat tidak disimpan; nama dan nomor WhatsApp tetap di browser dan hanya
ditambahkan ke draf WhatsApp setelah pengguna menyetujui dan memilih lanjut.

- `POST /api/sita/chat` `{message, locale, context?: {topic, criteria?}}` → jawaban dari
  data Sagita atau fallback yang mengarahkan pengguna ke admin, lengkap dengan
  `confidence` dan `suggestions` (chip topik ber-`topic` untuk melanjutkan maksud).
  Konteks browser
  hanya memuat topik dan kriteria yang diizinkan (anggaran, jumlah tamu, kota,
  tanggal, dan kategori vendor); konteks tidak disimpan sebagai riwayat chat.
  Tautan konsultasi dapat mengisi kriteria terkait melalui `sessionStorage`
  sekali pakai, tanpa menyertakan teks percakapan atau data pribadi.
  Pilihan “Layanan WO” menampilkan tautan ke `/formulir-konsultasi`.
- `POST /api/telemetry` menerima event allowlist `sita.no_match`. Event ini hanya
  mencatat nama event dan path tetap `/` sekali per halaman, tanpa pertanyaan
  atau data pribadi.

## Testing

```bash
npm test          # vitest run (unit and integration suite)
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

- `/sitemap.xml` dinamis (URL statis + slug packages/blog/pages dari backend, cache 1 jam, fail-open)
- `/robots.txt` dinamis dengan `Sitemap:` absolut mengikuti `SITE_URL`
- URL `?lang=id` dipertahankan agar canonical dan `hreflang` bahasa Indonesia dapat dirayapi; bahasa Inggris memakai URL tanpa parameter.
- Tetapkan `SITE_URL` ke domain publik HTTPS yang kanonis di production, lalu verifikasi domain dan kirim sitemap melalui Google Search Console.
- Peringkat, sitelink, dan backlink tidak dapat dijamin oleh perubahan kode; bangun backlink melalui rujukan dan kemitraan yang relevan, bukan tautan spam atau berbayar.
