# END-TO-END IMPLEMENTATION BRIEF — PREMIUM WEDDING PLANNER

> Versi 2 (direvisi). Brief ini ditulis untuk **proyek Laravel baru yang terpisah**
> dari repository portofolio `Fix-Portofolio`. Repository portofolio hanya menjadi
> referensi pola (lihat Bagian 2.3) dan **tidak boleh diubah**.

---

## 0. Perintah utama untuk AI Agent

Bertindaklah sebagai **senior full-stack engineer, software architect,
product designer, dan QA engineer**. Bangun aplikasi wedding planner yang
benar-benar berjalan, rapi, aman, responsif, mudah dirawat, dan memiliki
tampilan premium. Kerjakan secara end-to-end: periksa kondisi proyek,
rencanakan, tulis kode, jalankan migrasi dan test, perbaiki error, lalu
dokumentasikan cara menjalankannya.

**Jangan berhenti pada rencana, mockup, atau halaman statis.** Implementasikan
fitur nyata dengan database, validasi, otorisasi, state
loading/error/empty, dan pengujian. Jangan mengklaim fitur selesai jika
belum diimplementasikan **dan** diverifikasi.

### 0.1 Aturan kejujuran (berlaku di seluruh brief)

- Jangan mengarang hasil test, skor performa, integrasi, data bisnis,
  testimoni, rating, sertifikasi, atau jumlah klien.
- Jika sebuah perintah tidak bisa dijalankan (dependency, kredensial,
  jaringan, environment), tulis itu apa adanya di laporan akhir.
- Konten demo harus selalu ditandai sebagai demo (lihat Bagian 6.3).

### 0.2 Wajib: skill UI/UX `ui-ux-promax`

1. Cari skill **`ui-ux-promax`** di lingkungan agent (mis. `~/.claude/skills`,
   `.claude/skills`, `.agents/skills`, daftar skill pada sesi). Baca
   sebelum mendesain atau membuat UI.
2. Ikuti panduannya: design direction, visual hierarchy, typography,
   spacing, responsive, accessibility, interaction states, quality checks.
3. Jika skill **tidak ditemukan**: cari instruksi instalasi resmi yang
   memang dapat diakses. Jangan mengarang isi skill dan jangan mengklaim
   telah memakainya. Lanjutkan dengan praktik UI/UX profesional pada brief
   ini, lalu tulis keterbatasan itu di laporan akhir.
4. Jangan hanya memasang komponen UI generik. Buat identitas visual yang
   konsisten dan spesifik untuk wedding planner ini.
5. Catat hasil langkah ini (ditemukan / tidak, dan keputusan desain yang
   diambil) di `docs/DESIGN_SYSTEM.md`.

### 0.3 Aturan kualitas kode sumber (dibawa dari standar tim)

- **Dilarang** menulis kode sumber yang di-minify atau dipadatkan:
  tidak ada TSX/JSX, PHP, CSS, atau Markdown dalam satu baris panjang.
- Satu atribut per baris bila elemen sudah sulit dibaca; class Tailwind
  yang panjang tidak boleh menjadi alasan memadatkan seluruh elemen.
- Array PHP berisi banyak entri ditulis multi-baris; method chain panjang
  dipecah per baris. Ikuti PSR-12 (Laravel Pint) dan Prettier + ESLint.
- Komentar menjelaskan **mengapa**, bukan mengulang apa yang dilakukan kode.
- Prioritas keputusan: Correctness → Security → Readability →
  Maintainability → Consistency → Testability → Performance → Compactness.
- Buat `AGENTS.md` di proyek baru yang memuat aturan-aturan ini agar agent
  berikutnya mengikutinya.

---

## 1. Tujuan produk

Website wedding planner dengan pengalaman digital yang **elegan, editorial,
romantis, modern, premium, cepat, dan terpercaya**. Fondasinya harus bisa
tumbuh dari website pemasaran menjadi layanan dengan katalog
paket/vendor, konsultasi, booking, dan dashboard operasional.

- Bahasa antarmuka: **Bahasa Indonesia** (microcopy hangat, jelas,
  profesional). Pesan validasi server juga Bahasa Indonesia
  (sediakan `lang/id`).
- Mata uang **IDR**, format `Rp 12.500.000`. Tanggal/waktu locale `id`.
- Zona waktu aplikasi dapat dikonfigurasi, default `Asia/Jakarta`.
  Simpan timestamp di UTC, tampilkan di zona waktu aplikasi; tanggal acara
  disimpan sebagai `date` (bukan timestamp) agar tidak bergeser zona.
- Nama brand, logo, domain, kontak, WhatsApp, alamat, dan tautan sosial
  **belum final**: simpan di tabel `site_settings` (dikelola admin) dengan
  nilai default placeholder, bukan hard-coded di komponen.
- Calon pelanggan Indonesia terbiasa dengan WhatsApp: sediakan tombol
  "Hubungi via WhatsApp" yang nomornya dari `site_settings`, bukan
  dari kode.

### 1.1 Non-goals (di luar ruang lingkup versi ini)

Agar scope tidak melebar, hal berikut **tidak dikerjakan** kecuali diminta
kemudian: undangan digital & RSVP, manajemen daftar tamu, seating chart,
marketplace dengan pembayaran antar-vendor, aplikasi mobile native,
multi-bahasa/multi-tenant, chat real-time, review/rating vendor.
Desain schema tidak boleh menutup kemungkinan fitur-fitur ini di masa depan.

---

## 2. Konteks proyek, stack, dan arsitektur

### 2.1 Keputusan yang sudah ditetapkan

| Topik | Keputusan |
| --- | --- |
| Lokasi | Proyek Laravel **baru dan terpisah**, bukan di repo portofolio |
| Frontend | React + TypeScript (strict) + Inertia.js |
| Backend | Laravel stabil terbaru yang kompatibel dengan PHP di lingkungan |
| Styling | Tailwind CSS (versi yang dibawa starter kit) |
| UI primitives | shadcn/ui, hanya komponen yang dipakai, dikustomisasi sesuai brand |
| Motion | Package `motion` (import dari `motion/react`), terpilih dan hemat |
| Database | PostgreSQL (target utama, juga untuk test) |
| Media | Cloudinary (foto, video, audio) |
| Queue | Driver `database` (tanpa Redis di awal) |
| Mata uang | IDR, disimpan sebagai **integer rupiah** (`unsignedBigInteger`) |

### 2.2 Bootstrap proyek

1. Periksa versi PHP, Composer, Node, npm, dan ketersediaan PostgreSQL
   (`php -v`, `composer -V`, `node -v`, `psql --version`).
2. Buat proyek dengan **Laravel React Starter Kit** resmi
   (`laravel new <nama> --react` atau `composer create-project` +
   instalasi starter kit sesuai dokumentasi resmi saat itu). **Baca
   dokumentasi resmi sebelum menjalankan perintah**; jangan mengandalkan
   ingatan karena perintah/versi berubah.
3. Catat versi aktual yang terpasang (Laravel, Inertia, React, Tailwind,
   shadcn/ui, Vite, SDK Cloudinary) di `docs/ARCHITECTURE.md`.
4. Gunakan sistem autentikasi yang dibawa starter kit; jangan menulis
   ulang auth dari nol tanpa alasan teknis.
5. Inisialisasi Git, `.gitignore` yang benar (`.env`, `vendor`,
   `node_modules`, build output), dan commit awal sebelum perubahan besar.

### 2.3 Referensi dari repo portofolio (opsional, hanya baca)

Repo `Fix-Portofolio` sudah memiliki pola yang terbukti dan boleh dijadikan
inspirasi (jangan salin mentah):

- `app/Services/CloudinaryMediaService.php` — upload & destroy server-side.
  **Catatan:** versi itu masih punya kekurangan yang harus diperbaiki di
  proyek baru: tidak memvalidasi tipe/ukuran sendiri, tidak menangani
  exception SDK, `destroy` diam-diam kembali bila konfigurasi kosong,
  dan belum mendukung audio, signed upload, atau media privat.
- `app/Http/Middleware/SecurityHeaders.php` — pola security headers.
- Pola `throttle` pada form kontak dan login, serta halaman error 404/419/500.
- `Dockerfile` & `compose.yaml` — pola environment Docker development.

### 2.4 Aturan arsitektur

- **Modular monolith**, satu repository, alur deploy sederhana. Bukan microservices.
- Controller tipis → Form Request untuk validasi → Policy/Gate untuk
  otorisasi → Service/Action untuk logika kompleks → Eloquent.
- Jangan membuat abstraksi berlebihan (repository pattern, DTO, event)
  kecuali kompleksitas nyata menuntutnya.
- Eloquent relationships, constraint database, transaksi untuk operasi
  atomik, pagination untuk daftar besar, eager loading untuk mencegah N+1.
- Props Inertia: kirim hanya field yang dibutuhkan halaman (gunakan API
  Resource/array eksplisit). **Jangan** mengirim model mentah yang
  membocorkan kolom internal. Gunakan *shared data* secukupnya.
- Inertia SSR: aktifkan hanya bila bisa diverifikasi berjalan
  (`npm run build:ssr` + proses SSR). Jika tidak aktif, dokumentasikan dan
  pastikan halaman publik tetap memiliki metadata via `<Head>`; jangan
  mengklaim SSR/SEO yang tidak benar-benar aktif.
- Jangan menambah Redis, search engine, atau layanan berbayar sejak awal.
  Pencarian katalog cukup memakai query PostgreSQL (`ILIKE`, atau full-text
  bila terbukti perlu).
- Tidak ada REST API terpisah. Endpoint JSON hanya untuk kebutuhan
  khusus (signature upload Cloudinary, webhook).
- Jika perlu bantuan tipe route di frontend, gunakan fitur yang dibawa
  starter kit (mis. Wayfinder) — jangan menulis URL string manual yang rawan salah.

---

## 3. Arah desain dan kualitas visual

### 3.1 Visual direction

Gaya **editorial luxury wedding**: komposisi lapang, foto berkualitas
tinggi, tipografi elegan, grid yang matang, detail halus, navigasi sederhana.

Titik awal (boleh disesuaikan secara konsisten dan didokumentasikan):

- Warna dasar: warm ivory / off-white.
- Teks: charcoal / warm black dengan kontras aksesibel.
- Aksen hemat: muted champagne, soft taupe, atau sage.
- Heading: serif elegan (font dengan lisensi bebas, mis. Google Fonts),
  body/UI: sans-serif yang mudah dibaca. Pakai `font-display: swap`,
  subset yang dibutuhkan, dan preload font kritis. Utamakan self-host
  agar tidak bergantung CDN pihak ketiga.
- Spacing rhythm, garis tipis, dan border-radius konsisten.
- Definisikan **design tokens** (warna, tipografi, spacing, radius,
  bayangan, durasi motion) di satu tempat (CSS variables/Tailwind theme),
  lengkap dengan mode terang. Mode gelap opsional; jika tidak dibuat,
  pastikan tidak ada komponen yang rusak karena preferensi sistem.

### 3.2 Jangan lakukan

- Tampilan template admin generik tanpa karakter.
- Gradient mencolok, glassmorphism berlebihan, bayangan berat, terlalu
  banyak kartu rounded, animasi di setiap elemen, emoji sebagai ikon UI,
  layout padat.
- Gambar rusak, URL gambar acak/hotlink tak terverifikasi, atau
  placeholder kosong di hasil final.
- Semua halaman terlihat sama. Landing, katalog, form booking, dan dashboard
  punya kebutuhan visual berbeda namun satu design system.
- Mengorbankan aksesibilitas demi estetika.

### 3.3 Aset gambar demo

Agen mungkin tidak punya akses internet. Karena itu:

- Utamakan aset **lokal** (komposisi SVG/CSS buatan sendiri, ilustrasi
  sederhana, atau gambar berlisensi bebas yang disimpan di repo beserta
  catatan lisensi di `docs/ASSETS.md`).
- Setiap gambar demo harus memiliki `alt` bermakna, ukuran (width/height)
  tetap, dan fallback saat gagal dimuat.
- Jangan menyatakan foto demo sebagai karya klien nyata.
- Foto asli milik bisnis diunggah kemudian lewat Cloudinary (Bagian 5).

### 3.4 Interaksi, responsive, dan aksesibilitas (target WCAG 2.2 AA)

- Mobile-first; periksa 360px, 768px, 1024px, 1440px.
- Semua tombol/link punya aksi nyata dan label jelas. Tidak ada `href="#"` dekoratif.
- State lengkap: hover, `focus-visible`, active, disabled, loading,
  success, error, empty.
- HTML semantik, satu `h1` per halaman, hierarki heading benar, landmark
  (`header`, `nav`, `main`, `footer`), tautan "lewati ke konten".
- Label form, pesan error terhubung via `aria-describedby`, `aria-invalid`,
  fokus dipindah ke error pertama setelah submit gagal.
- Target sentuh minimal 44×44 CSS px untuk kontrol utama.
- Kontras teks ≥ 4.5:1 (teks besar ≥ 3:1).
- Lightbox/dialog/menu: focus trap, `Esc` menutup, fokus kembali ke pemicu,
  `aria-modal`, navigasi keyboard (gunakan primitif shadcn/Radix yang sudah aksesibel).
- Hormati `prefers-reduced-motion` (matikan/kurangi animasi non-esensial).
- Animasi singkat & bermakna; hindari CLS; muat komponen berat secara
  lazy (`React.lazy`/dynamic import).
- Form mempertahankan input saat validasi gagal; cegah submit ganda
  (disable tombol + indikator loading); beri konfirmasi sebelum aksi destruktif.
- Sediakan halaman error bermerek (403, 404, 419, 429, 500, 503).

---

## 4. Ruang lingkup fitur dan prioritas

Kerjakan bertahap. **Selesaikan satu milestone penuh (database → backend →
UI → test) sebelum memulai berikutnya.** Jika waktu/batas lingkungan habis,
milestone yang selesai harus benar-benar berfungsi — lebih baik sedikit
fitur yang lengkap daripada banyak fitur setengah jadi.

| Fase | Isi | Status wajib |
| --- | --- | --- |
| **MVP (wajib)** | Website publik, paket, katalog vendor (baca), portofolio, form konsultasi → lead, auth + role, dashboard admin (lead, paket, vendor, portofolio, FAQ, settings), manajemen media Cloudinary untuk foto | Harus selesai |
| **Fase 2 (wajib bila MVP stabil)** | Dashboard customer (event, checklist, budget, saved vendor), booking + status workflow + riwayat, email notifikasi via queue | Diupayakan selesai |
| **Fase 3 (opsional)** | Modul vendor (login & kelola profil), video/audio, media privat per event, integrasi payment sandbox | Hanya bila Fase 2 selesai dan stabil |

Laporkan dengan jelas fase mana yang selesai, sebagian, atau belum.

### A. Website publik (MVP)

1. **Homepage**: hero editorial + CTA, pengenalan layanan, paket
   unggulan, portofolio/galeri, alur kerja, vendor/partner unggulan (bila
   ada data), testimoni **hanya** berupa konten demo yang ditandai jelas
   (atau disembunyikan bila belum ada data nyata), FAQ, CTA konsultasi,
   footer lengkap (navigasi, kontak dari settings, sosial bila ada, tautan
   kebijakan privasi).
2. **Layanan/paket**: daftar, deskripsi, harga atau label "mulai dari"
   hanya bila diisi, fasilitas, CTA; halaman detail; perbandingan fitur
   bila berguna. Filter/sort hanya jika benar-benar bekerja.
3. **Katalog vendor**: kategori (venue, katering, dekorasi,
   foto/video, MUA, busana, hiburan, undangan, lainnya); pencarian,
   filter kategori/lokasi/rentang harga sesuai data, pagination,
   halaman detail. Filter disimpan di query string agar bisa dibagikan
   dan tombol Back berfungsi. Tanpa rating/review palsu.
4. **Portofolio/galeri**: kategori, gambar (video di Fase 3), detail proyek,
   gambar responsif, lightbox aksesibel.
5. **Konsultasi/kontak**: nama, kontak (email dan/atau WhatsApp — minimal
   satu wajib), tanggal acara opsional, lokasi opsional, estimasi anggaran
   opsional, catatan; **checkbox persetujuan pemrosesan data pribadi**;
   validasi server-side; honeypot + rate limiting; feedback sukses/gagal;
   simpan sebagai lead. Format nomor telepon Indonesia dinormalisasi.
6. **Halaman wajib lain**: Kebijakan Privasi (draft yang mengacu pada
   prinsip UU PDP Indonesia — tandai bahwa teks hukum final perlu ditinjau
   pemilik bisnis), Syarat & Ketentuan, 404 bermerek.
7. **SEO dasar**: `<title>` & description unik per halaman, canonical,
   Open Graph + Twitter card, sitemap dinamis (paket, vendor,
   portofolio yang `published`), `robots.txt`, URL berslug rapi,
   JSON-LD hanya bila datanya nyata dan valid. Halaman auth/dashboard/admin
   diberi `noindex`.

### B. Akun dan autentikasi (MVP)

- Registrasi, login, logout, reset password, verifikasi email sesuai
  starter kit. Verifikasi email diwajibkan hanya bila mail dikonfigurasi;
  jika tidak, dokumentasikan.
- 2FA untuk akun `admin` (aktifkan bila starter kit menyediakannya).
- Kebijakan password kuat, rate limit login, session aman
  (`secure`, `httponly`, `same_site`), regenerasi session saat login.
- Pengguna dapat **mengedit profil, mengganti password, dan menghapus
  akunnya sendiri** (dengan penanganan data terkait yang aman).

#### Role & matriks hak akses

Role: `admin`, `staff` (planner), `customer`, `vendor` (Fase 3).
Gunakan enum PHP + kolom `role` pada `users` (cukup untuk 4 role; jangan
memasang package permission kecuali ada kebutuhan granular nyata).

| Aksi | admin | staff | customer | vendor | publik |
| --- | --- | --- | --- | --- | --- |
| Baca konten terpublikasi | ✓ | ✓ | ✓ | ✓ | ✓ |
| Kelola paket/vendor/portofolio/FAQ/settings | ✓ | – | – | – | – |
| Kelola lead & booking | ✓ | ✓ (yang ditugaskan) | – | – | – |
| Kelola pengguna & ubah role | ✓ | – | – | – | – |
| Kelola event, task, budget miliknya | ✓ | ✓ (yang ditugaskan) | ✓ (miliknya) | – | – |
| Kirim lead / booking | – | – | ✓ | – | lead saja |
| Ubah profil vendor miliknya | ✓ | – | – | ✓ | – |

Aturan: self-registration **selalu** menghasilkan `customer`. Role lain
hanya dibuat/diubah oleh admin. Role tidak boleh bisa di-set lewat
mass assignment. Admin pertama dibuat lewat seeder/command memakai
variabel environment, **bukan** kredensial hard-coded, dan tidak
dijalankan otomatis di production.

### C. Dashboard customer (Fase 2)

- Ringkasan acara dan progres persiapan.
- Profil pasangan & detail acara: tanggal, lokasi, tipe acara, estimasi
  tamu, anggaran.
- Checklist/tugas dengan status, tenggat, dan template tugas awal yang
  dapat disalin (seed) saat event dibuat.
- Timeline/kalender acara.
- Budget planner: kategori, estimasi, realisasi, sisa anggaran. Rumus
  eksplisit di `docs/DATABASE.md` dan diuji (lihat Bagian 9). Realisasi
  bisa melebihi estimasi → tampilkan status "melebihi anggaran" yang jelas.
- Vendor/paket tersimpan.
- Riwayat konsultasi & booking beserta statusnya.
- Empty state yang membantu pengguna memulai.

### D. Booking dan alur operasional (Fase 2)

- Hubungan entitas: **lead** (dari form publik, belum tentu punya akun) →
  admin/staff dapat **mengonversi** lead menjadi akun customer + event +
  booking; customer yang sudah login dapat membuat booking langsung.
- Booking terkait ke `event` dan ke satu `package` (vendor sebagai
  `booking_items` hanya bila benar-benar dibutuhkan).
- Status & transisi **eksplisit** (tolak transisi lain dengan error jelas):

| Dari | Ke yang diizinkan | Siapa |
| --- | --- | --- |
| `pending` | `confirmed`, `cancelled` | admin/staff; `cancelled` juga customer |
| `confirmed` | `in_progress`, `cancelled` | admin/staff; `cancelled` oleh customer sesuai kebijakan |
| `in_progress` | `completed`, `cancelled` | admin/staff |
| `completed` | — (final) | — |
| `cancelled` | — (final) | — |

- Setiap perubahan status ditulis ke `booking_status_histories`
  (dari, ke, aktor, waktu, catatan) di dalam transaksi yang sama.
- **Aturan konflik/duplikasi (default, dapat dikonfigurasi):**
  1. Satu event tidak boleh punya dua booking aktif (`pending`/`confirmed`/
     `in_progress`) untuk paket yang sama → unique partial index di PostgreSQL.
  2. Kapasitas harian: maksimal N event `confirmed`/`in_progress` per tanggal
     (default 1, dari `site_settings`). Cek dilakukan dalam transaksi dengan
     penguncian (`lockForUpdate`) untuk mencegah race condition.
  3. Tanggal acara di masa lalu ditolak.
- Kebijakan pembatalan: simpan sebagai teks di settings; tidak ada
  logika refund otomatis.
- Email notifikasi lewat queue **hanya jika mail dikonfigurasi**.
  Default dev: driver `log`/Mailpit. Jangan mengklaim email terkirim bila tidak ada provider.
- Pembayaran online **bukan** prasyarat. Default: status pembayaran
  manual (`unpaid`, `deposit_paid`, `paid`) yang diubah staff. Bila
  mengintegrasikan gateway (Fase 3): gunakan sandbox resmi, verifikasi
  signature webhook, idempotency key, transaksi DB, tidak menyimpan data
  kartu, dan jangan percaya status dari browser.

### E. Dashboard admin/staff (MVP + Fase 2)

- Ringkasan lead, booking, paket, vendor, dan acara dari data nyata;
  tanpa grafik/angka palsu — empty state yang jujur.
- CRUD paket, kategori, vendor, portofolio, FAQ, lead, booking, media,
  pengguna, dan `site_settings`.
- Tabel: pagination server-side, pencarian/filter/sort yang benar-benar
  bekerja, validasi, konfirmasi aksi destruktif, toast/feedback.
- Detail lead/booking dengan alur perubahan status dan catatan internal.
- Status publikasi (`draft`/`published`) untuk konten publik; hanya `published`
  yang tampil di situs. Pratinjau draft hanya untuk admin.
- Urutan tampil (sort order) dapat diatur untuk paket, FAQ, dan portofolio.
- Log aktivitas ringan (`activity_logs`: aktor, aksi, subjek, waktu) untuk
  aksi sensitif (ubah role, hapus data, ubah status booking).
- Opsional: ekspor CSV lead.

### F. Modul vendor (Fase 3, opsional)

Profil & layanan vendor, area layanan, status approval
(`pending_review`/`approved`/`rejected`), vendor hanya mengubah data
miliknya (Policy), admin mengelola semua, dokumen/kontak privat tidak
dibuka ke publik tanpa izin. Perubahan profil oleh vendor yang sudah
`approved` memerlukan keputusan: default — langsung tayang kecuali field
sensitif (nama, harga) yang kembali ke review.

---

## 5. Integrasi Cloudinary — wajib

Cloudinary menyimpan dan mengirim **foto, video, dan audio**. Database
hanya menyimpan metadata/referensi; tidak ada blob media di PostgreSQL.

### 5.1 Implementasi

- Satu service terpusat (`App\Services\Media\CloudinaryService` atau
  setara) di balik interface kecil, agar controller tidak memegang detail
  SDK dan test bisa memakai fake.
- Gunakan SDK PHP resmi Cloudinary (`cloudinary/cloudinary_php`). **Periksa
  dokumentasi & versi SDK aktual** sebelum menulis kode; jangan menebak API.
- Konfigurasi lewat environment. Pilih **satu** bentuk yang konsisten dan
  dokumentasikan: `CLOUDINARY_URL` (format `cloudinary://key:secret@cloud`)
  **atau** tiga variabel `CLOUDINARY_CLOUD_NAME`, `CLOUDINARY_API_KEY`,
  `CLOUDINARY_API_SECRET`. Tambahkan juga `CLOUDINARY_FOLDER` (prefix root)
  dan batas ukuran per tipe (lihat 5.3). Semua masuk `.env.example` dengan
  nilai kosong/contoh.
- Hanya `cloud_name` (publik) yang boleh sampai ke frontend. **API secret
  tidak boleh** muncul di browser, repo, log, prop Inertia, atau response.
- Bila kredensial kosong: aplikasi tetap berjalan; fitur upload
  menampilkan pesan konfigurasi yang jelas (bukan error 500, bukan diam-diam
  sukses). Jangan berpura-pura upload berhasil.

### 5.2 Alur upload

Pilih dan dokumentasikan satu alur utama (default: **signed upload
langsung dari browser** untuk file besar; upload via server untuk file kecil):

1. Browser meminta signature ke endpoint server (terautentikasi).
2. Server memverifikasi: pengguna, policy atas subjek (event/vendor/
   portofolio), `resource_type`, folder tujuan, format yang diizinkan,
   ukuran maksimum. Server **menentukan sendiri** folder & parameter yang
   ditandatangani; klien tidak boleh menyodorkan folder/public_id sembarang.
3. Signature berumur pendek; hanya parameter yang ditandatangani yang berlaku.
4. Setelah upload, klien mengirim hasil ke server. Server **memverifikasi
   ulang ke Cloudinary** (mis. Admin API `resource`) bahwa aset itu ada,
   berada di folder yang diharapkan, dan tipe/ukurannya sesuai — baru
   menyimpan record `media_assets`. Jangan percaya `public_id`/URL dari klien.
5. Upload via server: validasi MIME sebenarnya (bukan hanya ekstensi),
   ukuran, dan nama file diabaikan (jangan memakai nama asli).
6. Pertimbangkan webhook notifikasi Cloudinary (dengan verifikasi
   signature) atau job pembersih terjadwal untuk menangani aset yatim
   (terupload tetapi tidak pernah tersimpan di DB).

### 5.3 Tipe media & batas

- Foto: JPEG, PNG, WebP, AVIF.
- Video: MP4, WebM.
- Audio: MP3, OGG, WAV. **Catatan:** pada Cloudinary, audio ditangani
  sebagai `resource_type: video`. Simpan `kind` (`image`/`video`/`audio`)
  di tabel sendiri terpisah dari `resource_type` Cloudinary.
- Batas ukuran per tipe dari `config/media.php` (env-overridable). Jangan
  berasumsi batas paket Cloudinary — verifikasi atau buat configurable.
- Tolak file executable/arsip/SVG upload dari pengguna (SVG berisiko XSS).

### 5.4 Penyimpanan & struktur

- Folder: `{CLOUDINARY_FOLDER}/portfolio`, `/vendors`, `/packages`,
  `/events/{event-id}`, `/audio`.
- Tabel `media_assets` (polymorphic `mediable_type`/`mediable_id` atau
  relasional setara — pilih yang paling mudah dirawat dan jelaskan alasannya):
  `provider`, `public_id` (unique), `kind`, `resource_type`,
  `delivery_type` (`upload`/`authenticated`), `format`, `secure_url`,
  `width`, `height`, `duration`, `bytes`, `alt_text`, `caption`,
  `visibility` (`public`/`private`), `status`
  (`pending`/`ready`/`failed`/`deleting`), `uploaded_by`, `sort_order`,
  timestamps.
- `alt_text` wajib untuk foto yang informatif di konten publik.

### 5.5 Delivery & optimasi

- Bangun URL lewat SDK/helper (jangan merangkai manual), memakai
  `f_auto`, `q_auto`, dan lebar responsif (`srcset`/`sizes`). Komponen
  `<CloudinaryImage>` di frontend harus menetapkan width/height (cegah CLS),
  `loading="lazy"` di luar viewport, dan placeholder/blur ringan.
- Thumbnail memakai transformasi kecil; jangan kirim full-resolution ke kartu.
- Video: poster, `preload="metadata"`, tanpa autoplay bersuara.
- **Media privat**: unggah dengan `type: authenticated` dan kirim lewat
  **signed URL berumur pendek** yang dibuat server setelah Policy
  lolos. Jangan simpan signed URL di DB.
- Musik latar: **tidak autoplay bersuara**. Kontrol play/pause jelas,
  dapat diakses keyboard, `aria-label`, hormati kebijakan browser, ingat
  preferensi pengguna (mis. mute) secara lokal.

### 5.6 Hapus & ganti

- Hapus dilakukan lewat state machine: tandai `deleting` → hapus di
  Cloudinary → hapus record. Jika Cloudinary gagal, record tetap
  (`deleting`) dan dicoba ulang oleh job; log tanpa membocorkan secret.
- Hanya pemilik/aktor berwenang (Policy) yang bisa menghapus. Server
  mencari aset berdasarkan **ID record milik pengguna**, bukan menerima
  `public_id` dari request. Tidak boleh menghapus aset milik pihak lain.
- Mengganti media: unggah baru sukses dulu, baru hapus yang lama.
- Tangani upload gagal, dibatalkan, file invalid, retry yang aman
  (idempotent). Tidak boleh ada record setengah jadi.

### 5.7 Dokumentasi & pengujian

`docs/CLOUDINARY.md` memuat setup, alur, resource type, media privat,
troubleshooting, dan cara menguji tanpa kredensial (fake). Test memakai
fake/mocking sehingga tidak bergantung layanan eksternal.

---

## 6. Database dan model domain

### 6.1 Prinsip

- PostgreSQL. Jangan membuat tabel hanya karena ada di daftar; buat yang
  dibutuhkan alur nyata.
- Foreign key, unique constraint, index (FK, slug, kolom filter/sort),
  enum/status tervalidasi (PHP backed enum + cek di DB bila praktis),
  timestamps, soft deletes hanya bila bermanfaat, aturan cascade aman.
- Uang: integer rupiah (`unsignedBigInteger`) + kolom `currency` hanya bila
  multi-currency benar-benar diperlukan; **bukan float/decimal bebas**.
  Gunakan satu helper format/parse rupiah di backend dan frontend.
- Slug unik untuk paket, vendor, portofolio; slug tidak berubah otomatis
  saat judul diedit (hindari link rusak) kecuali eksplisit.
- Migrasi harus reversible bila praktis, aman untuk data yang ada, dan
  tidak mengedit migrasi lama yang sudah dipakai.

### 6.2 Entitas yang dipertimbangkan

`users`, `customer_profiles`, `events`, `event_staff` (penugasan staff),
`packages`, `package_features`, `vendor_categories`, `vendors`,
`vendor_services`, `portfolio_projects`, `media_assets`,
`consultation_leads`, `bookings`, `booking_status_histories`, `tasks`,
`budget_items`, `saved_vendors`, `faqs`, `testimonials` (dengan flag demo),
`site_settings`, `activity_logs`, tabel `notifications` Laravel bila
memakai notifikasi, `jobs`/`failed_jobs`/`cache`/`sessions` standar.

### 6.3 Seeder & data demo

- Dua jenis seeder: **`DemoSeeder`** (hanya dev/staging; aman dijalankan
  ulang) dan **`ProductionSeeder`** (hanya data referensi: kategori vendor,
  template task, settings default). Production tidak boleh otomatis
  menjalankan DemoSeeder.
- Semua baris demo memiliki `is_demo = true` (atau penanda setara) dan
  label "Konten demo" di UI admin, sehingga mudah dihapus massal.
- Akun demo (admin/staff/customer) hanya untuk lokal, kredensial dari
  variabel environment, tidak pernah di-commit sebagai nilai nyata.
- Factory untuk semua model utama dipakai test.
- Tidak ada testimoni, nama klien, rating, atau statistik yang disajikan sebagai kenyataan.

---

## 7. Keamanan dan privasi

- Ikuti praktik keamanan Laravel dan OWASP Top 10: CSRF, validasi server,
  output escaping, mass-assignment protection, rate limiting (login, reset
  password, form publik, endpoint signature upload), hash password.
- **Rate limit default** (ubah bila ada alasan): login 5/menit per
  email+IP, form konsultasi 5/10 menit per IP, signature upload 30/menit per user.
- **IDOR**: tidak ada akses event, booking, task, budget, atau media milik
  pengguna lain dengan mengganti ID. Gunakan Policy di setiap controller
  dan query yang di-scope ke pemilik; uji dengan test eksplisit.
- Authorization di backend untuk setiap aksi sensitif; UI hanya pelengkap.
- **Security headers** (CSP yang kompatibel dengan Vite/Inertia, HSTS di
  production, `X-Content-Type-Options`, `Referrer-Policy`,
  `Permissions-Policy`, `frame-ancestors`). Sesuaikan CSP agar mengizinkan
  domain Cloudinary untuk gambar/video/audio.
- Cookie aman: `secure`, `httponly`, `same_site=lax` di production; `APP_DEBUG=false`.
- Upload: batas ukuran/tipe, tolak executable, validasi MIME nyata.
- Tidak ada secret di source, bundle frontend, commit, atau log. Jalankan
  secret scan sederhana (mis. grep pola kunci) sebelum commit akhir.
  `.env.example` hanya berisi nama variabel dan nilai kosong/contoh —
  **jangan** mengisi host, username, atau project-id nyata.
- Jangan me-log password, token, signed URL, atau data pribadi yang tidak perlu.
  Mask nomor telepon/email di log bila perlu.
- Halaman error aman tanpa stack trace di production.
- **Privasi (UU PDP)**: persetujuan eksplisit di form, tautan kebijakan
  privasi, tujuan penggunaan data jelas, kebijakan retensi lead
  (mis. dapat dihapus oleh admin), pengguna dapat menghapus akun dan
  meminta data dihapus; hanya kumpulkan data yang perlu.
- Webhook (Cloudinary/payment): verifikasi signature, tolak replay,
  idempotent.
- `composer audit` dan `npm audit`: jalankan, laporkan temuan; jangan
  mengabaikan secara diam-diam.

---

## 8. Performa dan SEO

Target (ukur, jangan klaim tanpa bukti):

- LCP ≤ 2,5 dtk, CLS ≤ 0,1, INP ≤ 200 ms pada halaman publik utama
  (kondisi mobile throttled bila alat tersedia).
- Hindari N+1 (uji dengan `Model::preventLazyLoading()` di non-production
  atau assertion jumlah query pada test kunci).
- Pagination untuk semua daftar; indeks DB untuk kolom filter/sort.
- Code-splitting per halaman (Inertia `resolve` dinamis), animasi & lightbox
  dimuat lazy, tanpa dependency berat yang tidak perlu.
- Lazy-load media di luar viewport; ukuran media ditetapkan; transformasi Cloudinary.
- Font: self-host, subset, `font-display: swap`, preload yang kritis.
- Cache hanya data aman (daftar publik, settings), dengan invalidasi saat
  konten berubah. Mulai dengan cache `database`/`file`; jangan memasang Redis.
- Jalankan `php artisan config:cache`, `route:cache`, `view:cache` di
  dokumentasi deployment.
- Bila Lighthouse/audit setara tersedia, jalankan untuk homepage,
  katalog, dan detail; laporkan angka asli beserta keterbatasannya
  (lab vs. lapangan). Jika tidak tersedia, tulis "tidak diukur".

---

## 9. Testing dan quality gates

### 9.1 Backend (PHPUnit atau Pest — pilih satu, ikuti starter kit)

- Test memakai **PostgreSQL** (bukan SQLite) agar perilaku constraint,
  partial index, dan `ILIKE` sama dengan produksi. Bila tidak tersedia,
  catat sebagai keterbatasan.
- Autentikasi & otorisasi per role (matriks Bagian 4B diuji).
- Customer tidak bisa membaca/mengubah data customer lain (event, booking,
  task, budget, media) — uji dengan ID milik pengguna lain → 403/404.
- Role tidak bisa dinaikkan lewat request registrasi/profil (mass assignment).
- CRUD inti beserta validasi (data valid, tidak valid, batas).
- Transisi status booking: semua transisi valid & tidak valid;
  duplikasi; kapasitas harian; race condition dasar bila bisa diuji.
- Perhitungan budget (total estimasi, realisasi, sisa, melebihi anggaran,
  nilai nol/kosong, pembulatan integer).
- Cloudinary: logika signature, validasi parameter, verifikasi server,
  penghapusan oleh pemilik vs bukan pemilik, kegagalan SDK — semua memakai fake.
- Form konsultasi: validasi, honeypot, persetujuan, rate limiting (429).
- Query count pada halaman katalog/detail (cegah N+1).
- Halaman publik mengembalikan 200 dengan metadata; draft tidak bocor ke publik.

### 9.2 Frontend

- `tsc --noEmit` tanpa error (strict).
- ESLint + Prettier lolos.
- Test komponen/form utama (Vitest + Testing Library) untuk interaksi
  penting dan state loading/error/empty — bila starter kit belum
  menyediakannya, tambahkan secukupnya untuk alur kritis, jangan berlebihan.
- Smoke test E2E dengan Playwright (Chromium biasanya sudah tersedia di
  environment; jangan menjalankan `playwright install`): homepage → buka
  paket → kirim konsultasi → login admin → lihat lead. Cek console tanpa
  error dan tidak ada gambar/link rusak.
- Audit aksesibilitas otomatis (mis. axe) pada halaman kunci + checklist
  manual keyboard (Tab, Esc, focus ring) untuk menu, lightbox, dan form.
- Checklist responsif manual: 360 / 768 / 1024 / 1440 px.
- Pastikan tidak ada tombol penting yang tidak melakukan apa pun, tautan `#`
  dekoratif, atau form yang tidak mengirim data.

### 9.3 CI

Buat workflow CI (mis. GitHub Actions) yang menjalankan dengan service
PostgreSQL: `composer install`, migrasi, Pint `--test`, test backend,
`npm ci`, ESLint, `tsc`, test frontend, dan `npm run build`.

### 9.4 Sebelum dinyatakan selesai

Jalankan dan laporkan hasil nyata: migrate (+ rollback sanity), seed,
test suite, lint, typecheck, production build, audit dependency. Perbaiki
yang gagal. Jika sesuatu tidak bisa dijalankan, catat alasannya — jangan mengklaim lolos.

---

## 10. Dokumentasi dan deliverables

Buat/perbarui dan pastikan **sesuai kode aktual**:

1. `README.md` — produk, stack + versi, requirements, instalasi, konfigurasi,
   development, build, test, deployment, akun demo lokal (cara membuatnya).
2. `.env.example` — semua variabel, tanpa secret/identitas nyata.
3. `AGENTS.md` — aturan kode (Bagian 0.3), perintah dev/test/lint, konvensi.
4. `docs/ARCHITECTURE.md` — arsitektur, batas modul, keputusan & trade-off, versi.
5. `docs/DATABASE.md` — entitas, relasi, rumus budget, aturan status booking.
6. `docs/CLOUDINARY.md` — setup, alur upload, resource type, media privat,
   troubleshooting.
7. `docs/DESIGN_SYSTEM.md` — token, tipografi, komponen, hasil langkah skill UI/UX.
8. `docs/DEPLOYMENT.md` — checklist deployment, environment, queue worker &
   scheduler, storage, HTTPS, backup DB, health check (`/up`), migrasi
   zero-downtime sederhana, rollback, dan opsi hosting SSR bila dipakai.
9. `docs/ASSETS.md` — sumber & lisensi aset.
10. `docs/STATUS.md` — daftar fitur: selesai, sebagian, belum, dan yang
    menunggu kredensial/keputusan bisnis.

Tidak ada dokumentasi yang bertentangan dengan kode.

---

## 11. Cara kerja agent — urutan wajib

### Fase 1: Audit
- Periksa direktori kerja, Git status, versi runtime, tool yang tersedia
  (PostgreSQL, Node, Chromium), akses jaringan, dan skill `ui-ux-promax`.
- Baca README/AGENTS.md bila ada. Jangan menimpa pekerjaan pengguna.
- Tulis ringkasan kondisi & risiko di `docs/PROGRESS.md`.

### Fase 2: Rencana
- Susun milestone terverifikasi sesuai Bagian 4 (MVP → Fase 2 → Fase 3).
- Tetapkan schema & design system sebelum membangun banyak halaman.
- Daftar konfigurasi eksternal (Cloudinary, email, payment) dan penanganan
  graceful bila belum ada.
- Jangan bertanya hal non-esensial; pilih default masuk akal dan buat
  mudah diubah. Bertanya hanya bila keputusan memblokir atau berisiko
  merusak data/biaya.

### Fase 3: Fondasi
- Bootstrap starter kit, design tokens, layout, navigasi, tipografi,
  komponen reusable, form primitives, pola feedback, halaman error.
- Auth, role, migrasi, model, policy **sebelum** fitur yang menyimpan data privat.
- CI dan lint/format sejak awal.

### Fase 4: Fitur end-to-end
- Per milestone: database → model/policy → controller/request → UI → test → dokumentasi.
- Urutan: publik → katalog/detail → lead → auth/admin → media Cloudinary →
  dashboard customer → booking → optimasi → Fase 3.
- Data yang tampil berasal dari database atau konfigurasi yang jelas — bukan data statis yang berpura-pura dinamis.
- **Commit kecil dan bermakna** per unit kerja (Conventional Commits:
  `feat:`, `fix:`, `test:`, `docs:`, `refactor:`). Jangan push ke branch
  utama langsung dan jangan membuat PR kecuali diminta.
- Perbarui `docs/PROGRESS.md` di tiap milestone (apa selesai, apa berikutnya, blocker).

### Fase 5: Verifikasi & polish
- Jalankan semua quality gate (9.4). Periksa console/log, link/gambar rusak,
  error form, empty/loading state, aksesibilitas, responsif.
- Pass desain akhir untuk konsistensi dan detail.
- Review keamanan akhir: IDOR, mass assignment, upload, secret scan, header.

### Fase 6: Laporan akhir
Gunakan format ini:
1. Fitur yang benar-benar selesai (per fase).
2. Fitur sebagian/belum selesai dan alasannya.
3. Keputusan arsitektur penting.
4. File penting yang dibuat/diubah.
5. Perintah yang dijalankan beserta hasil **nyata** (test, build, lint, audit, Lighthouse bila ada).
6. Cara menjalankan dari nol.
7. Variabel environment yang perlu diisi.
8. Yang terblokir kredensial/keputusan eksternal.
9. Risiko, temuan keamanan, dan pekerjaan lanjutan.
10. Status skill `ui-ux-promax` (dipakai / tidak tersedia).

---

## 12. Definition of Done

- [ ] Aplikasi berjalan dari nol mengikuti README.
- [ ] MVP selesai penuh; Fase 2/3 dilaporkan jujur (selesai/sebagian/belum).
- [ ] Website publik premium, responsif, konsisten; aset tidak rusak.
- [ ] Fitur inti terhubung ke backend/database, bukan mockup statis.
- [ ] Auth & authorization berfungsi di backend; matriks role diuji; tidak ada IDOR.
- [ ] Form punya validasi server, pesan Indonesia, feedback jelas, dan persetujuan data pribadi.
- [ ] CRUD utama menangani loading/empty/error state.
- [ ] Cloudinary aman (secret tidak bocor, signed upload, verifikasi server);
      konfigurasi kosong ditangani dengan jujur; media privat terlindungi.
- [ ] Migrasi, seeder (demo vs production), factory, dan relasi konsisten; data demo berlabel.
- [ ] Aksesibilitas WCAG 2.2 AA diperiksa (otomatis + manual keyboard).
- [ ] `tsc`, lint, test backend & frontend, production build, dan audit
      dependency dijalankan; hasil dilaporkan apa adanya.
- [ ] CI terkonfigurasi.
- [ ] Tidak ada secret/identitas nyata di commit atau `.env.example`.
- [ ] Kode sumber terbaca (tidak minified), mengikuti Pint/Prettier/ESLint.
- [ ] README & docs sesuai kode aktual.
- [ ] Tidak ada klaim palsu tentang test, integrasi, data, atau performa.

---

## Instruksi penutup

Mulai dengan audit lingkungan dan membaca skill `ui-ux-promax`. Lanjutkan
secara mandiri hingga batas kemampuan lingkungan, mengikuti urutan fase dan
prioritas MVP. **Utamakan aplikasi yang benar-benar berfungsi, aman, rapi,
dan indah daripada banyak file atau tampilan demo.** Jangan melakukan
rewrite besar tanpa alasan teknis, jangan menghapus pekerjaan yang ada, dan
jangan mengarang hasil verifikasi.
