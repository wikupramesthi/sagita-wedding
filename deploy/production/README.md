# Deployment produksi (Linux native)

Panduan ini memakai Ubuntu/Debian, Apache, PHP-FPM, MySQL/MariaDB, Node.js 22+, dan systemd. Apache melayani Laravel secara langsung melalui `be/public` dan meneruskan website Astro ke Node pada `127.0.0.1:3000`. Jangan memakai `php artisan serve` sebagai server produksi.

Template `example.com` dan `app.example.com` adalah placeholder, bukan domain siap pakai. Ganti semua nama domain dan lokasi `/var/www/sagita-wedding` sebelum mengaktifkan layanan.

## 1. DNS dan prasyarat server

Siapkan DNS A/AAAA untuk domain website (mis. `example.com`, `www.example.com`) dan backend/admin (mis. `app.example.com`) agar mengarah ke server yang sama. Website dan backend perlu berbagi domain induk agar cookie session dapat dipakai pada kedua subdomain.

Pasang Apache, MySQL/MariaDB, PHP 8.3 CLI/FPM dan ekstensi `curl`, `dom`, `fileinfo`, `gd`, `mbstring`, `pdo_mysql`, `xml`, `zip`, Composer 2, serta Node.js 22+ dan npm. Aktifkan modul Apache berikut:

```bash
sudo a2enmod proxy proxy_http proxy_fcgi setenvif rewrite headers ssl
sudo a2enconf php8.3-fpm
```

Pastikan versi yang dipakai oleh terminal dan systemd sama. Bila `npm` atau PHP dipasang melalui version manager di home pengguna, gunakan path binari absolut yang benar pada unit systemd.

## 2. Buat database dan clone

Buat database kosong serta pengguna aplikasi khusus di client admin MySQL. Jangan gunakan akun `root` MySQL untuk aplikasi:

```sql
CREATE DATABASE sagita CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
CREATE USER 'sagita_app'@'localhost' IDENTIFIED BY 'GANTI_DENGAN_PASSWORD_UNIK';
GRANT ALL PRIVILEGES ON sagita.* TO 'sagita_app'@'localhost';
```

Buat pengguna sistem `sagita`, lalu clone repository ke `/var/www/sagita-wedding` dengan pengguna tersebut. Pastikan pengguna `www-data` dapat membaca kode dan menulis ke `be/storage` serta `be/bootstrap/cache`.

```bash
sudo adduser --system --group --home /home/sagita --create-home sagita
sudo install -d -o sagita -g www-data -m 2750 /var/www/sagita-wedding
sudo -u sagita git clone URL_REPOSITORY /var/www/sagita-wedding
```

Ganti `URL_REPOSITORY` dengan URL clone repository. Jika repository privat, siapkan akses deploy key untuk pengguna `sagita` tanpa menyimpan private key di repository.

## 3. Konfigurasi environment

Salin template produksi, lalu edit sebelum menjalankan build atau migrasi:

```bash
sudo -u sagita cp /var/www/sagita-wedding/deploy/production/backend.env.example /var/www/sagita-wedding/be/.env
sudo -u sagita cp /var/www/sagita-wedding/deploy/production/frontend.env.example /var/www/sagita-wedding/fe/.env
```

Atur minimal:

| Nilai | `be/.env` | `fe/.env` |
|---|---|---|
| Domain website kanonis | `FRONTEND_URL=https://DOMAIN_WEBSITE` | `SITE_URL=https://DOMAIN_WEBSITE` |
| Host Laravel/admin | `APP_URL=https://DOMAIN_BACKEND` | `BACKEND_URL=https://DOMAIN_BACKEND` dan `GOOGLE_AUTH_URL=https://DOMAIN_BACKEND/auth/google` |
| Cookie session | `SESSION_COOKIE=sagita_session` dan `SESSION_DOMAIN=.DOMAIN_INDUK` | `SESSION_COOKIE_NAME=sagita_session` |
| API key bersama | `FRONTEND_API_KEY` | `API_KEY` |

`DOMAIN_INDUK` harus suffix bersama yang benar, misalnya `.example.com` untuk `example.com` dan `app.example.com`. Jangan pertahankan nilai contoh. Browser tidak mengirim cookie host-only backend ke subdomain website, jadi `SESSION_DOMAIN` lintas subdomain diperlukan untuk login bersama. Gunakan hanya jika seluruh subdomain pada domain itu dipercaya dan dilayani lewat HTTPS.

Lanjutkan konfigurasi berikut:

- Isi database, username, dan password khusus aplikasi.
- Buat `APP_KEY` dengan `php artisan key:generate` setelah menyalin `.env`.
- Buat nilai API key acak dengan `openssl rand -hex 32`, lalu simpan nilai yang sama di `FRONTEND_API_KEY` dan `API_KEY`. Jangan masukkan secret ke Git atau log.
- Set `CORS_ALLOWED_ORIGINS` hanya ke origin website yang benar-benar digunakan.
- Jika Google login dipakai, isi `GOOGLE_CLIENT_ID`/`GOOGLE_CLIENT_SECRET` dan daftarkan URI persis `https://DOMAIN_BACKEND/auth/google/callback` di Google Cloud Console.
- `MAIL_MAILER=log` tidak mengirim email. Konfigurasikan SMTP sebelum mengandalkan notifikasi email. Isi kredensial captcha, WhatsApp, dan rekening manual jika fitur terkait dipakai.
- Untuk notifikasi Telegram, tambahkan bot ke grup admin dan isi `TELEGRAM_ENABLED=true`, `TELEGRAM_BOT_TOKEN`, serta `TELEGRAM_CHAT_ID` di `be/.env`. Gunakan token bot yang sudah dirotasi jika token pernah dibagikan; jangan simpan kredensial di Git. Pastikan worker `sagita-queue` aktif agar notifikasi terkirim.

Jaga permission file: `be/.env` perlu dapat dibaca PHP-FPM (`www-data`) tetapi tidak boleh publik; `fe/.env` hanya perlu dapat dibaca pengguna layanan Astro. Jangan menyimpan kedua file itu di Git.

## 4. Build pertama dan inisialisasi database

Jalankan perintah berikut sebagai pengguna `sagita` dari clone yang sudah memiliki kedua file `.env` (misalnya buka shell dengan `sudo -u sagita -H bash`):

```bash
cd /var/www/sagita-wedding/be
composer install --no-dev --optimize-autoloader --no-interaction
npm ci --include=dev
npm run build
php artisan key:generate --force
php artisan migrate --seed --force
php artisan storage:link
php artisan optimize

cd /var/www/sagita-wedding/fe
npm ci --include=dev
npm run build
```

Seed pertama memasukkan snapshot konten aplikasi. `CurrentSnapshotSeeder` mengosongkan dan mengisi ulang tabel aplikasi; **jangan jalankan `db:seed` atau `migrate --seed` lagi pada database berisi data produksi**. Deploy rutin cukup memakai `php artisan migrate --force`, setelah backup database dan file unggahan.

Atur hak akses Laravel agar PHP-FPM dapat menulis:

```bash
sudo chown -R sagita:www-data /var/www/sagita-wedding/be/storage /var/www/sagita-wedding/be/bootstrap/cache
sudo chmod -R ug+rwX /var/www/sagita-wedding/be/storage /var/www/sagita-wedding/be/bootstrap/cache
sudo chown sagita:www-data /var/www/sagita-wedding/be/.env
sudo chmod 640 /var/www/sagita-wedding/be/.env
sudo chown sagita:sagita /var/www/sagita-wedding/fe/.env
sudo chmod 600 /var/www/sagita-wedding/fe/.env
```

### Akun admin pertama

Seeder tidak membuat akun admin. Buat akun lewat `https://DOMAIN_BACKEND/register`, lalu berikan role admin dari CLI server (jangan membuka panel sebelum langkah ini selesai):

```bash
cd /var/www/sagita-wedding/be
sudo -u sagita php artisan tinker
```

Di Tinker, cari akun yang baru dibuat dan berikan role:

```php
$user = \App\Models\User::where('email', 'EMAIL_ADMIN')->firstOrFail();
$user->assignRole('super-admin');
```

Keluar dari Tinker dengan `exit`, lalu login melalui `https://DOMAIN_BACKEND/auth/login`.

## 5. Apache, HTTPS, queue, dan scheduler

Salin template Apache ke `/etc/apache2/sites-available/` dengan nama site yang akan diaktifkan. Ganti `ServerName`, `ServerAlias`, dan path bila berbeda. Backend harus memakai document root `be/public`, bukan root repository.

```bash
sudo cp /var/www/sagita-wedding/deploy/production/apache-frontend.conf /etc/apache2/sites-available/sagita-frontend.conf
sudo cp /var/www/sagita-wedding/deploy/production/apache-backend.conf /etc/apache2/sites-available/sagita-backend.conf
sudo a2ensite sagita-frontend.conf sagita-backend.conf
sudo apachectl configtest
sudo systemctl reload apache2
```

Setelah DNS aktif, pasang sertifikat HTTPS dengan Certbot untuk domain website, alias `www` (jika dipakai), dan domain backend. Contoh, ganti semua nama domain sebelum menjalankan:

```bash
sudo certbot --apache -d example.com -d www.example.com -d app.example.com
```

Pastikan redirect HTTP ke HTTPS aktif setelah sertifikat siap. `SESSION_SECURE_COOKIE=true` mengharuskan HTTPS.

Pasang unit systemd ke `/etc/systemd/system/`. Sesuaikan pengguna, path proyek, dan path `/usr/bin/npm` jika berbeda:

```bash
sudo cp /var/www/sagita-wedding/deploy/production/sagita-frontend.service /etc/systemd/system/
sudo cp /var/www/sagita-wedding/deploy/production/sagita-queue.service /etc/systemd/system/
sudo systemctl daemon-reload
sudo systemctl enable --now sagita-frontend.service sagita-queue.service
```

Laravel scheduler juga diperlukan untuk pengingat, expiry, dan pembersihan data. Tambahkan cron untuk pengguna `sagita`:

```cron
* * * * * cd /var/www/sagita-wedding/be && /usr/bin/php artisan schedule:run >> /dev/null 2>&1
```

## 6. Verifikasi dan deploy berikutnya

```bash
curl -fsS https://DOMAIN_WEBSITE/healthz
curl -fsS https://DOMAIN_WEBSITE/readyz
curl -fsS https://DOMAIN_BACKEND/up
sudo systemctl status sagita-frontend sagita-queue apache2 php8.3-fpm
```

Untuk rilis berikutnya: backup database dan `be/storage/app`, ambil perubahan Git, jalankan `composer install`, build aset backend dan frontend seperti di atas, `php artisan optimize:clear`, `php artisan migrate --force`, `php artisan optimize`, lalu restart `sagita-frontend` dan `sagita-queue`. **Jangan sertakan `--seed` pada deploy rutin.**
