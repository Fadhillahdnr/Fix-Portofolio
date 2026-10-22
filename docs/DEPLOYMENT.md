# Deployment

Gunakan PHP 8.2–8.5, Node 20+, HTTPS, dan secret manager. Set `APP_ENV=production`, `APP_DEBUG=false`, `APP_URL`, `ADMIN_PATH` acak, Supabase `DB_URL`, `DB_SSLMODE=require`, serta `CLOUDINARY_URL`.

Jalankan `composer install --no-dev --optimize-autoloader`, `npm ci && npm run build`, `php artisan migrate --force`, lalu `php artisan optimize`. Gunakan Supabase Session Pooler port 5432 pada server persisten IPv4 atau direct connection pada host IPv6. Jangan memberikan key rahasia atau database URL ke browser.

Uji login, draft, upload gambar/video, publish, form kontak, sitemap, dan `/up` setelah deploy. Backup database dikelola melalui Supabase; atur backup/retention media sesuai paket Cloudinary.
