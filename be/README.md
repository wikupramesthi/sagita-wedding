# Sagita Wedding — Backend

Aplikasi Laravel untuk panel admin dan API publik yang dikonsumsi oleh `fe/` (Express). Panduan gabungan ada di `../README.md`.

## Menjalankan

```bash
cp .env.example .env
composer install
php artisan key:generate
php artisan migrate --seed
npm install && npm run dev
php artisan storage:link
php artisan serve
```

`SESSION_COOKIE` harus sama dengan `SESSION_COOKIE_NAME` di `fe/.env`. `FRONTEND_API_KEY` harus sama dengan `API_KEY` di `fe/.env`.

## API

Semua route `/api/*` memakai `ApiSecurityHeaders`, `throttle:api`, dan `VerifyApiKey` (header `X-Api-Key`). Ringkasan endpoint dan kontrak batch ada di `../README.md`.

Error API memakai `{ "success": false, "status": "error", "message": "...", "errors": {...} }`. Field `errors` hanya muncul untuk 422.

## Testing

```bash
vendor\bin\pint --test app tests bootstrap routes config
$env:DB_DATABASE='yogaroots_testing'; $env:APP_ENV='testing'; vendor\bin\phpunit
```

Ganti nama database sesuai lingkungan lokal. `phpunit.xml` memakai MySQL; jangan jalankan test pada database produksi.
