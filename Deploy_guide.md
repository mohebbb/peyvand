# Deployment guide

## Requirements (production server)

- PHP **8.3+** with extensions: `pdo_mysql`, `gd`, `iconv`, `fileinfo`, `mbstring`, `openssl`, `intl`, `zip`, `curl`, `xml`
- Composer, Node.js + npm (only to build the landing-page assets once). There is no `package-lock.json` in the repo yet — run `npm install` once and commit the lock file so future builds are reproducible.
- MariaDB/MySQL database

## First deployment

```bash
git fetch origin
git reset --hard origin/master

composer install --no-dev --optimize-autoloader
npm ci && npm run build          # builds the "/" landing page assets (Vite)

cp .env.example .env
php artisan key:generate
```

Edit `.env` on the server:

- `APP_ENV=production`, `APP_DEBUG=false`, `LOG_LEVEL=warning`
- **`APP_URL=https://<your-real-domain>`** — QR codes and short links are built from it (`trackableUrl()`), so it must match the public domain
- `DB_*` (MariaDB/MySQL), `SESSION_DRIVER=database`, `CACHE_STORE=database`
- Optional: `QR_LOGO_DISK=s3` + AWS keys when running multiple servers

Then:

```bash
php artisan migrate --force       # creates users, sessions, cache, jobs, short_links, qr_code_settings
chmod -R ug+rwX storage bootstrap/cache   # writable by the PHP user
php artisan optimize              # config/route/view/event caches
```

Create the first admin user (there is no registration route):

```bash
php artisan tinker --execute="App\Models\User::create(['name' => 'Admin', 'email' => 'admin@example.com', 'password' => bcrypt('change-me')]);"
```

## After every deployment

```bash
git fetch origin
git reset --hard origin/master
composer install --no-dev --optimize-autoloader
php artisan migrate --force
php artisan optimize
```

`git reset --hard` does not touch untracked files, so `storage/app/private/qr-logos` (uploaded logos) survives redeploys.

## Notes

- **Storage**: `storage/app/private` holds Livewire's `livewire-tmp` uploads and `qr-logos`. It must be writable by the PHP user. Livewire deletes temp files older than 24 hours automatically.
- **Multiple servers**: Livewire's temp uploads live on the local disk; run the panel on one server or publish Livewire's config and point `temporary_file_upload.disk` at a shared disk (e.g. `s3`). Set `QR_LOGO_DISK=s3` for the logos too.
- **HTTPS behind a reverse proxy**: set `APP_URL=https://…` and trust the proxy so cookies/URLs use the right scheme, e.g. in `bootstrap/app.php`: `$middleware->trustProxies(at: '*');`
- **PHP ini**: keep `opcache` enabled; `upload_max_filesize`/`post_max_size` must be ≥ the 1 MB logo limit (defaults are fine). On Windows dev machines using `php artisan serve`, pin `upload_tmp_dir` + `sys_temp_dir` (see README troubleshooting).
- **Uploads security**: logos are stored on the private `local` disk and embedded into generated QR files as data URIs — never publicly reachable. The upload endpoint is signed, CSRF-protected and throttled (60/min).
