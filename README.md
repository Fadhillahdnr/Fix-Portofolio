# Portofolio Fadhillah

Portofolio Laravel 12 dengan Blade progressive enhancement, Three.js lazy-loaded, admin content studio, Supabase PostgreSQL, dan Cloudinary untuk foto/video.

## Setup lokal

1. `cp .env.example .env` lalu isi kredensial.
2. Untuk local cepat, ubah `DB_CONNECTION=sqlite`, kosongkan `DB_URL`, dan buat `database/database.sqlite`.
3. `composer install && npm install`
4. `php artisan key:generate && php artisan migrate --seed`
5. `composer run dev`

Login berada tepat pada path `ADMIN_PATH` dan tidak ditautkan dari situs publik. Kerahasiaan URL hanya lapisan tambahan; keamanan utama tetap password kuat, session Laravel, role admin, CSRF, dan rate limit. Jangan commit `.env`. Ganti password akun sesudah seed pertama karena perubahan env tidak mengubah user yang sudah ada.

Production memakai `DB_URL` Supabase dengan SSL wajib. Browser tidak mengakses Data API. Upload foto/video dilakukan server-side melalui Cloudinary; database menyimpan secure URL dan `public_id`.

Jalankan `php artisan test`, `vendor/bin/pint --test`, `npm run build`, `composer audit`, dan `npm audit`. Lihat [deployment](docs/DEPLOYMENT.md). Design system berada di `design-system/portofolio-fadhillah/MASTER.md`.

## Development dengan Docker

Docker development memakai bind mount untuk seluruh source code. Perubahan Blade/PHP langsung terbaca, sedangkan CSS dan JavaScript diperbarui melalui Vite HMR tanpa build ulang image.

```bash
docker compose up --build
```

Buka `http://localhost:8000`. Build image hanya diperlukan saat `Dockerfile` atau extension PHP berubah. Untuk start berikutnya cukup `docker compose up`.

```bash
docker compose exec app php artisan migrate --seed
docker compose logs -f app vite queue
docker compose exec app php artisan test
docker compose down
```

Source code di-mount ke `/var/www/html`. Dependency container disimpan pada named volume `composer_vendor` dan `node_modules`, sehingga dependency host tidak menimpa dependency Linux di container.
