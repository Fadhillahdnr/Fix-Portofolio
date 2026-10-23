# END-TO-END IMPLEMENTATION BRIEF — APLIKASI PERENCANA PERNIKAHAN PRIBADI

> Versi 4. Brief ini menggantikan versi sebelumnya (yang membayangkan layanan
> wedding planner untuk banyak klien). Sekarang tujuannya: **aplikasi privat
> untuk merencanakan pernikahan milik sendiri** — anggaran, timeline menuju
> hari H (target sekitar **24 bulan dari sekarang**), dan vendor yang akan
> dipakai. Ditulis untuk **proyek Laravel baru yang terpisah** dari repository
> portofolio `Fix-Portofolio`; repo portofolio hanya referensi baca.

---

## 0. Perintah utama untuk AI Agent

Bertindaklah sebagai **senior full-stack engineer, software architect,
product designer, dan QA engineer**. Bangun aplikasi perencana pernikahan
pribadi yang benar-benar berjalan, aman (berisi data keuangan & pribadi),
nyaman dipakai di ponsel, dan berdesain tenang-premium. Kerjakan end-to-end:
audit lingkungan, rencanakan, tulis kode, jalankan migrasi dan test, perbaiki
error, lalu dokumentasikan cara menjalankannya.

**Jangan berhenti pada rencana, mockup, atau halaman statis.** Fitur harus
nyata: database, validasi, otorisasi, state loading/error/empty, dan test.
Jangan mengklaim fitur selesai bila belum diimplementasikan **dan** diverifikasi.

### 0.1 Aturan kejujuran (berlaku di seluruh brief)

- Jangan mengarang hasil test, skor performa, integrasi, atau harga.
- **Jangan mengarang data vendor, harga pasar, atau aturan hukum/administrasi
  pernikahan.** Semua angka harga berasal dari input pengguna. Template
  anggaran/timeline hanyalah titik awal yang bisa diubah dan harus diberi
  label "template umum, sesuaikan".
- Bila sebuah perintah tidak bisa dijalankan (dependency, kredensial,
  jaringan), tulis apa adanya di laporan akhir.

### 0.2 Wajib: skill UI/UX `ui-ux-promax` (gerbang desain, bukan saran)

UI **tidak boleh** ditulis sebelum seluruh gerbang di bawah terpenuhi.
Tujuannya: hasil akhir terlihat dirancang oleh desainer manusia untuk
brand ini, bukan keluaran generik AI ("AI slop", lihat Bagian 3.2).

**Gerbang 1 — Temukan & muat skill.**
1. Cari skill di lingkungan agent dengan nama `ui-ux-promax` **dan**
   variasi ejaan `ui-ux-pro-max` (periksa daftar skill sesi, `~/.claude/skills`,
   `.claude/skills`, `.agents/skills`, dan folder plugin). Muat dan baca
   `SKILL.md` serta berkas pendukungnya (skrip pencarian, data, referensi)
   sampai selesai sebelum menulis satu pun komponen.
2. Jika tidak ditemukan, cari instruksi instalasi resmi yang dapat
   diakses di lingkungan kerja dan pasang hanya lewat jalur resmi itu.
   Jangan mengarang isi skill, jangan mengklaim telah memakainya.
3. Jika tetap tidak tersedia: **berhenti sejenak dan laporkan di
   `docs/PROGRESS.md`**, lalu lanjutkan dengan Bagian 3 sebagai pengganti
   yang ketat (bukan sebagai alasan untuk bekerja asal-asalan). Tulis
   keterbatasan ini di laporan akhir.

**Gerbang 2 — Hasilkan design system lewat skill.**
1. Jalankan alur design-system milik skill (mis. pencarian/rekomendasi
   desain dengan konteks: "aplikasi perencana pernikahan pribadi, editorial hangat dan tenang, mobile-first, Indonesia")
   dan **simpan hasilnya** sebagai sumber kebenaran, mengikuti pola
   yang sudah dipakai di repo portofolio:
   - `design-system/<nama-proyek>/MASTER.md` — aturan global (palet,
     tipografi, spacing, komponen, anti-pattern, checklist pra-pengiriman)
   - `design-system/<nama-proyek>/pages/<halaman>.md` — override per
     halaman (login, onboarding, dashboard, anggaran, timeline, vendor)
2. Tetapkan **design dials** (mis. Variance / Motion / Density) dan
   tuliskan alasannya untuk produk ini. Halaman login/onboarding/countdown
   boleh lebih ekspresif; anggaran, vendor, dan tabel lebih tenang dan padat informasi.
3. Aturan di `MASTER.md` mengikat: bila halaman punya file override, file
   itu menang. Jika hasil skill bertentangan dengan brief ini, **brief ini
   menang untuk isi/fungsi dan skill menang untuk keputusan visual**,
   kecuali skill menyarankan sesuatu yang melanggar aksesibilitas (a11y menang).
4. Skill sering menghasilkan palet/font default yang cenderung generik
   atau bertema teknologi. **Verifikasi hasilnya cocok untuk wedding
   editorial** (hangat, ivory, serif elegan). Jika tidak cocok, override
   secara sadar di `MASTER.md` dengan alasan tertulis — jangan menerima
   output mentah begitu saja.

**Gerbang 3 — Rencana visual sebelum kode.**
Tulis `docs/DESIGN_SYSTEM.md` yang memuat: referensi ke `MASTER.md`,
konsep visual satu kalimat, pasangan font + alasan, palet + rasio kontras
terukur, skala spacing, aturan foto (crop, rasio, treatment), daftar
komponen kustom, aturan motion, dan sketsa tata letak tiap halaman utama
(deskripsi teks/ASCII: urutan section, titik fokus, ritme, di mana ada
ruang kosong). Baru setelah ini UI boleh dikerjakan.

**Gerbang 4 — Bangun dari design system, bukan dari kebiasaan.**
Semua warna, font, spacing, radius, bayangan, dan durasi motion berasal
dari token (CSS variables/Tailwind theme) yang diturunkan dari `MASTER.md`.
Tidak ada nilai warna/ukuran acak di komponen.

**Gerbang 5 — Review pra-pengiriman dengan bukti.**
Sebelum menyatakan UI selesai:
1. Jalankan checklist "pre-delivery" milik skill (bila ada) pada setiap halaman utama.
2. Ambil **screenshot nyata** (Playwright/Chromium yang sudah tersedia) pada
   360, 768, 1024, dan 1440 px untuk: onboarding, dashboard, anggaran, detail kategori anggaran,
   timeline, daftar vendor, perbandingan kandidat vendor, tugas, login.
   Simpan di `docs/screenshots/`.
3. **Lihat sendiri screenshot-nya** (buka gambarnya) dan nilai dengan
   daftar anti-slop di Bagian 3.2. Perbaiki setiap pelanggaran, ulangi
   sampai bersih. Catat hasil review (temuan + perbaikan) di `docs/PROGRESS.md`.
4. Jangan menulis "desain sudah premium" tanpa bukti screenshot dan checklist ini.

Jangan hanya memasang komponen UI generik. Identitas visual harus
konsisten dan spesifik untuk produk ini.

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

Satu tempat privat bagi dua orang (pemilik dan pasangan) untuk menjawab tiga
pertanyaan sepanjang ±24 bulan persiapan:

1. **Uang** — berapa total yang dibutuhkan, dari mana dananya, berapa harus
   ditabung per bulan, dan kapan setiap pembayaran jatuh tempo?
2. **Waktu** — apa yang harus dikerjakan, kapan, dan apakah kita masih
   sesuai jadwal menuju hari H?
3. **Vendor** — vendor apa saja yang dibutuhkan, siapa kandidatnya, mana
   yang dipilih, berapa harganya, dan sudah sejauh apa pembayarannya?

Rasa produk: **tenang, hangat, editorial, personal**. Bukan dashboard
korporat, bukan marketplace. Dipakai terutama di **ponsel** (saat survei
vendor, meeting, atau di wedding fair) dan laptop (saat menyusun anggaran).

### 1.1 Fakta yang belum diketahui — jangan di-hardcode

Semua hal berikut dimasukkan pengguna lewat **onboarding & halaman
Pengaturan Pernikahan**, bukan ditulis di kode:

- Nama kedua mempelai, panggilan, dan pengguna yang terhubung.
- **Tanggal hari H**: bisa *belum pasti*. Simpan `target_date` (nullable) dan
  `target_month` (perkiraan). Default saat onboarding: kira-kira 24 bulan
  dari hari ini, bisa diubah. Semua countdown & timeline harus bekerja
  dengan tanggal perkiraan dan menyesuaikan diri begitu tanggal pasti dipilih.
- Kota/lokasi, perkiraan jumlah tamu, jumlah acara (akad/pemberkatan,
  resepsi, ngunduh mantu, dll.), agama/adat yang memengaruhi template tugas.
- Total anggaran (boleh kosong → aplikasi menampilkan total proyeksi dari
  item yang diisi), sumber dana, dan tabungan awal.
- Apakah honeymoon dan pre-wedding dihitung dalam anggaran.

Bila data belum diisi, tampilkan empty state yang membantu ("Mulai dengan
menentukan perkiraan jumlah tamu"), bukan angka nol yang menyesatkan.

### 1.2 Bahasa & format
Antarmuka **Bahasa Indonesia** (microcopy natural, tidak kaku). Mata uang
**IDR** `Rp 12.500.000`; tanggal locale `id`; zona waktu default
`Asia/Jakarta` (dapat dikonfigurasi). Timestamp di UTC; **hari H dan tenggat
disimpan sebagai `date`**, bukan timestamp, agar tidak bergeser zona.

### 1.3 Non-goals
Bukan marketplace/pencarian vendor publik, tidak ada pemesanan vendor online,
tidak ada pembayaran online atau penyimpanan data kartu, tidak ada scraping
data vendor, tidak ada situs pemasaran publik, tidak ada chat real-time,
tidak ada multi-tenant SaaS. Undangan digital/RSVP publik hanya Fase 3
opsional. Skema tetap memakai `wedding_id` agar kelak bisa dikembangkan.

---

## 2. Stack dan arsitektur

### 2.1 Keputusan yang sudah ditetapkan

| Topik | Keputusan |
| --- | --- |
| Lokasi | Proyek Laravel **baru dan terpisah** |
| Frontend | React + TypeScript (strict) + Inertia.js |
| Backend | Laravel stabil terbaru yang kompatibel dengan PHP di lingkungan |
| Styling | Tailwind CSS (versi starter kit) + shadcn/ui yang dikustomisasi |
| Motion | Package `motion` (`motion/react`), hemat |
| Database | PostgreSQL (juga untuk test) |
| Media | Cloudinary (foto, dokumen/PDF; video & audio opsional) |
| Queue | Driver `database`, tanpa Redis |
| Uang | **Integer rupiah** (`unsignedBigInteger`), tanpa float |
| Akses | Privat: hanya 2 pengguna terundang, tanpa pendaftaran publik |

### 2.2 Bootstrap
1. Periksa `php -v`, `composer -V`, `node -v`, ketersediaan PostgreSQL.
2. Buat proyek dengan **Laravel React Starter Kit** resmi; **baca dokumentasi
   resmi saat itu**, jangan mengandalkan ingatan. Catat versi aktual
   (Laravel, Inertia, React, Tailwind, shadcn/ui, Vite, SDK Cloudinary) di
   `docs/ARCHITECTURE.md`.
3. Pakai auth bawaan starter kit, **lalu matikan pendaftaran publik**
   (route register dihapus/dikembalikan 404). Akun dibuat lewat perintah
   artisan (`app:create-owner`, nilai dari prompt/env, bukan hard-coded) dan
   pasangan ikut lewat tautan undangan (lihat 4.A).
4. Inisialisasi Git, `.gitignore` benar, commit awal sebelum perubahan besar.

### 2.3 Aturan arsitektur
- Modular monolith. Controller tipis → Form Request → Policy → Service/Action
  untuk logika berhitung → Eloquent. Tanpa repository pattern/DTO/event
  kecuali kompleksitas menuntut.
- **Logika hitung (anggaran, rencana tabungan, penjadwalan ulang timeline)
  ditaruh di class murni yang mudah diuji**, bukan di controller atau
  komponen React. Frontend hanya menampilkan hasil dari backend; angka
  tidak boleh dihitung ganda dengan rumus berbeda di dua tempat.
- Semua query di-scope ke `wedding_id` milik pengguna (global scope atau
  relasi eksplisit) + Policy. Eager loading untuk N+1; pagination bila daftar besar.
- Props Inertia hanya berisi field yang dibutuhkan halaman (Resource/array eksplisit).
- Tidak ada REST API terpisah. Endpoint JSON hanya untuk signature upload Cloudinary.
- Jangan menambah Redis/search engine/layanan berbayar. Pencarian cukup SQL `ILIKE`.
- Inertia SSR tidak dibutuhkan (aplikasi privat, `noindex`); jangan diaktifkan tanpa alasan.
- Gunakan fitur tipe-route yang dibawa starter kit (mis. Wayfinder) bila ada.

---

## 3. Arah desain dan kualitas visual

### 3.1 Visual direction
**Editorial hangat & tenang**, terasa seperti buku catatan pernikahan yang
dirancang dengan baik — bukan spreadsheet dan bukan dashboard SaaS. Angka
keuangan harus mudah dibaca dan menenangkan, bukan menakutkan.

Titik awal hipotesis — **keputusan final berasal dari `MASTER.md` hasil skill
(Bagian 0.2) dan harus didokumentasikan alasannya**:
- Warna dasar warm ivory; teks charcoal hangat; satu aksen hemat (champagne,
  sage, atau terracotta lembut). Warna status anggaran (aman / mendekati /
  melebihi) harus dapat dibedakan **tanpa bergantung pada warna saja**
  (ikon/teks/pola).
- Heading serif elegan, body/UI sans yang nyaman; angka memakai
  `tabular-nums` agar kolom rapi. Font self-host, subset, `font-display: swap`.
- Foto (moodboard, kunjungan vendor) jadi elemen visual; tata letak lapang
  di halaman ringkasan, padat-rapi di tabel anggaran.
- Design tokens (warna, tipografi, spacing, radius, bayangan, motion) di satu
  tempat. Mode gelap opsional; jika tidak dibuat, pastikan tidak rusak.
- Visualisasi data (grafik arus kas, donat alokasi anggaran, progres
  timeline): bila skill `dataviz` tersedia, ikuti; grafik harus jujur
  (sumbu mulai dari nol untuk batang), berlabel, dan aksesibel (ada ringkasan
  teks/tabel alternatif).

### 3.2 Anti-AI-slop — daftar larangan yang diperiksa satu per satu

"AI slop" = tampilan generik yang terasa dibuat otomatis: aman, rata, dan
bisa dipasang di brand apa pun. Setiap butir di bawah adalah **item
review** pada Gerbang 5; satu pelanggaran = belum selesai.

**Tipografi & warna**
- Dilarang memakai Inter/Roboto/Arial/system-ui sebagai satu-satunya
  identitas. Heading memakai serif editorial yang dipilih sadar; body sans
  yang nyaman dibaca. Maksimal 2 keluarga font, dengan hierarki ukuran jelas.
- Dilarang gradient ungu-biru/neon, gradient teks, "mesh blob" dekoratif,
  dan palet default Tailwind (`indigo-500`, `slate-900`, `blue-600`) tanpa penyesuaian.
- Aksen dipakai hemat (CTA, state aktif); mayoritas permukaan netral hangat.
- Hindari teks abu-abu pucat di atas ivory (kontras rendah) dan putih murni `#fff` + hitam murni `#000`.

**Layout & komposisi**
- Dilarang pola template berulang: hero tengah + 3 kartu fitur sama besar
  + deretan ikon + CTA berpita. Variasikan ritme: sesekali asimetris,
  full-bleed foto, kolom sempit teks, penomoran editorial, caption kecil.
- Maksimal satu pola "grid kartu" per halaman; jangan membungkus semua
  hal dalam kartu berborder + bayangan + radius besar.
- Tentukan **satu titik fokus** per section dan beri ruang kosong yang
  disengaja. Hindari section yang semuanya berbobot sama.
- Radius, garis, dan bayangan konsisten dan hemat; bayangan lembut atau tidak ada.
- Jarak antar section berasal dari skala spacing, bukan angka acak.

**Konten & microcopy**
- Dilarang copy klise: "Wujudkan pernikahan impian Anda", "Solusi terbaik",
  "Pengalaman tak terlupakan", "Menyulap momen spesial", dan variasinya.
  Tulis copy spesifik, hangat, konkret (apa yang dilakukan planner, untuk
  siapa, bagaimana prosesnya), tanpa klaim yang tidak bisa dibuktikan.
- Dilarang Lorem ipsum, nama "John & Jane", angka statistik/rating bulat
  karangan ("500+ pasangan bahagia", "5.0 ★"), dan logo partner palsu.
- Heading, label tombol, dan empty state memakai kalimat natural, bukan
  "Get Started"/"Learn More" generik.

**Ikon, gambar, dan dekorasi**
- Dilarang emoji sebagai ikon UI. Satu set ikon konsisten (satu pustaka,
  satu stroke width). Jangan memberi ikon pada setiap judul/butir.
- Dilarang foto stok klise atau gambar rusak/acak; tidak ada placeholder
  abu-abu kosong di hasil final. Perlakukan foto secara editorial:
  rasio konsisten, crop disengaja, caption, tanpa overlay gelap tebal.
- Hindari ornamen berlebihan (bunga/hati/cincin di mana-mana). Ornamen
  harus langka, halus, dan fungsional.
- Dilarang glassmorphism berlebihan, glow/neon, dan efek parallax liar.

**Motion**
- Tanpa animasi masuk (fade-up) pada setiap elemen. Gunakan motion
  untuk makna: transisi halaman halus, reveal hero, hover galeri, state
  perubahan. Durasi pendek, easing konsisten, hormati `prefers-reduced-motion`.

**Komponen & dashboard**
- Dashboard dan admin jangan terlihat seperti template admin bawaan
  (sidebar gelap + kartu statistik warna-warni). Pakai bahasa visual yang
  sama dengan halaman lain, lebih tenang dan padat; angka tampil dari data nyata.
- Komponen shadcn/ui **harus dikustomisasi** (radius, warna, tipografi,
  state fokus) sehingga tidak terlihat seperti tampilan default.
- Form: label jelas, grup logis, ruang cukup, error dekat input; bukan
  tumpukan input identik tanpa struktur.

**Uji cepat "slop"** — sebelum selesai, jawab jujur di `docs/PROGRESS.md`:
1. Jika logo dan teks disembunyikan, apakah halaman ini masih jelas milik
   brand wedding ini dan bukan template? Jika tidak, desain ulang.
2. Apakah ada section yang bisa ditukar ke situs SaaS lain tanpa diubah?
3. Apakah setiap pilihan font/warna/ruang punya alasan di `DESIGN_SYSTEM.md`?

### 3.2a Larangan lain yang tetap berlaku

- Template admin generik tanpa karakter; layout padat tanpa napas.
- Gambar rusak, hotlink acak tak terverifikasi, URL tak berlisensi.
- Semua halaman terlihat sama: dashboard, anggaran, timeline, dan vendor
  punya kebutuhan visual berbeda namun satu design system.
- Mengorbankan aksesibilitas demi estetika.

### 3.3 Aset gambar
Aplikasi ini sebagian besar menampilkan foto milik pengguna. Karena itu:
- Gunakan komposisi tipografi, ilustrasi SVG sederhana buatan sendiri, atau
  aset berlisensi bebas (catat di `docs/ASSETS.md`). Tidak ada hotlink acak.
- Empty state berupa ilustrasi/tipografi yang dirancang, bukan kotak abu-abu.
- Semua gambar punya `alt` bermakna (kecuali dekoratif), width/height tetap,
  dan fallback bila gagal dimuat.

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

Selesaikan satu milestone penuh (database → backend → UI → test) sebelum
berikutnya. Lebih baik sedikit fitur yang lengkap daripada banyak yang setengah jadi.

| Fase | Isi | Status |
| --- | --- | --- |
| **MVP (wajib)** | Auth privat + undang pasangan, onboarding & pengaturan, dashboard, **anggaran**, **timeline & tugas**, **vendor** (kategori, kandidat, perbandingan, pilihan) | Harus selesai |
| **Fase 2** | Jadwal pembayaran + pengingat, rencana tabungan lanjutan & arus kas, dokumen/kontrak privat (Cloudinary), catatan meeting, daftar tamu (jumlah & kelompok), moodboard, ekspor CSV/PDF & `.ics`, PWA installable | Diupayakan |
| **Fase 3 (opsional)** | Tautan baca-saja untuk keluarga (cakupan terbatas), skenario anggaran (hemat/standar/premium), undangan digital/RSVP, audio/video | Hanya bila Fase 2 stabil |

### A. Akun, akses, dan pasangan (MVP)
- Tidak ada pendaftaran publik. Hanya anggota workspace (`weddings` ↔
  `wedding_members`) yang bisa masuk. Role: `owner` dan `partner` (hak akses
  data sama; hanya `owner` yang dapat mengundang/mengeluarkan anggota dan
  menghapus workspace).
- Undangan pasangan: tautan bertanda tangan, **sekali pakai, kedaluwarsa**
  (mis. 7 hari, dapat dicabut), pasangan menetapkan password sendiri.
- Login, logout, reset password; **2FA** diaktifkan/ditawarkan bila starter
  kit mendukung (disarankan keras karena data keuangan). Rate limit login,
  session aman, regenerasi session saat login.
- Hapus akun/ekspor semua data oleh pemilik (lihat 7).

### B. Onboarding & pengaturan pernikahan (MVP)
Wizard ringkas 4–5 langkah yang boleh dilewati sebagian: nama & pasangan →
perkiraan tanggal/bulan (default ~24 bulan dari sekarang) → kota & jumlah
tamu → total anggaran (opsional) & sumber dana → pilih agama/adat & acara
(menentukan template tugas) → ringkasan. Setelah selesai, sistem membuat
**template** kategori anggaran, tugas timeline, dan slot vendor (lihat
lampiran 4.G). Semua hasil template **dapat diedit/dihapus** dan ditandai
`source = template`. Menjalankan ulang template tidak boleh menimpa data
yang sudah diubah pengguna.

### C. Dashboard (MVP)
Satu layar yang menjawab "kita ada di mana?":
- **Countdown** ke hari H (hari / minggu / bulan; bila tanggal belum pasti,
  tampilkan "perkiraan ±N bulan" dengan jelas).
- **Fase saat ini** di timeline (mis. "Menentukan venue") dan progres tugas fase itu.
- **Ringkasan anggaran**: total proyeksi, sudah disepakati, sudah dibayar,
  sisa yang harus disiapkan, status (aman / mendekati / melebihi).
- **Rencana tabungan**: butuh berapa per bulan vs rencana setoran saat ini.
- **Yang perlu perhatian**: tugas terlambat/minggu ini, pembayaran jatuh tempo
  30 hari ke depan, kategori vendor yang sudah melewati waktu idealnya tapi
  belum dipilih.
- Pintasan cepat: tambah kandidat vendor, catat pembayaran, tambah tugas
  (besar & mudah dijangkau di ponsel).
- Semua angka dari data nyata; tanpa grafik/angka palsu.

### D. Anggaran (MVP)
Struktur: `Anggaran total` → `Kategori` → `Item`. Tiga angka berbeda per item
(jangan dicampur):
1. **Estimasi** — rencana/perkiraan (boleh berupa rentang min–maks).
2. **Kesepakatan** — harga yang disepakati dengan vendor terpilih (otomatis
   dari kandidat vendor yang dipilih, atau diisi manual).
3. **Terbayar** — jumlah pembayaran yang sudah dicatat lunas.

**Rumus (wajib didokumentasikan di `docs/DATABASE.md` dan diuji):**
- `proyeksi_item = kesepakatan ?? estimasi` (bila rentang, gunakan nilai
  tengah untuk total dan tampilkan rentang terpisah).
- `proyeksi_total = Σ proyeksi_item + dana_tak_terduga`.
- `sisa_dibayar = Σ kesepakatan − Σ terbayar` (tidak boleh negatif tanpa
  peringatan; pembayaran > kesepakatan ditandai).
- `selisih_anggaran = anggaran_total − proyeksi_total`; status: aman bila
  selisih ≥ 0; "mendekati" bila proyeksi ≥ 90% anggaran (ambang
  dikonfigurasi); "melebihi" bila selisih < 0.
- **Item per tamu** (`pricing_mode = per_guest`, mis. katering, souvenir):
  `estimasi = harga_per_tamu × perkiraan_jumlah_tamu`. Mengubah jumlah tamu
  menghitung ulang semua item per tamu. Tamu yang dihitung memakai satu
  sumber (pengaturan pernikahan, atau jumlah daftar tamu bila Fase 2 aktif).
- **Dana tak terduga**: persentase dari subtotal (default **5–10%**, bisa
  diubah) sebagai baris tersendiri yang tidak bisa terhapus tanpa peringatan.
- **Buffer kenaikan harga** (opsional, default 0%): persentase tambahan atas
  item yang belum disepakati karena harga vendor dapat naik dalam 2 tahun.
- **Alokasi awal (template, bukan standar)** saat total anggaran diisi —
  titik awal umum yang harus diubah sesuai kebutuhan: venue + katering ~45%,
  dekorasi ~10%, dokumentasi ~10%, rias & busana ~10%, hiburan/MC ~4%,
  undangan & souvenir ~4%, seserahan/mahar/cincin ~7%, administrasi ~1%,
  transport & akomodasi ~3%, dana tak terduga ~6%. Pembulatan: bilangan
  bulat rupiah, sisa pembulatan masuk ke kategori terbesar sehingga jumlah
  alokasi **selalu sama persis** dengan total. Bila honeymoon/pre-wedding
  dihitung, mereka berupa kategori terpisah di luar alokasi di atas.

**Sumber dana & rencana tabungan**
- `Sumber dana`: nama (tabungan pribadi A/B, orang tua, bonus/THR, dll.),
  jumlah terkumpul, target, dan kontribusi bulanan terencana.
- `kebutuhan_per_bulan = max(0, proyeksi_total − Σ terkumpul) / sisa_bulan`
  (`sisa_bulan` minimal 1; bila tanggal belum pasti pakai tanggal perkiraan;
  bila hari H sudah lewat/hari ini, tampilkan sisa total, bukan pembagian nol).
- Bandingkan dengan `Σ kontribusi_bulanan_terencana` → "sesuai jalur" atau
  "kurang Rp X per bulan". Tampilkan juga **titik temu**: perkiraan kapan
  dana terkumpul mencukupi bila setoran saat ini dipertahankan.
- **Arus kas (Fase 2):** grafik dana yang dibutuhkan per bulan berdasarkan
  jadwal pembayaran (DP biasanya jauh sebelum hari H); tandai bulan dengan
  pengeluaran besar.

**Pembayaran (Fase 2 penuh, model dasar di MVP)**
- Setiap item/vendor punya jadwal pembayaran: label (DP, termin, pelunasan),
  jumlah, tanggal jatuh tempo, status (`direncanakan`, `jatuh_tempo`,
  `lunas`, `dibatalkan`), tanggal bayar, metode, catatan, bukti (media privat).
- Pengingat jatuh tempo (in-app; email via queue **hanya jika mail dikonfigurasi**).
- Mencatat pembayaran memperbarui "Terbayar" dalam transaksi database.

### E. Timeline & tugas (MVP)
- **Tiga tampilan**: *Roadmap* per fase (T-24 bulan sampai pasca-nikah),
  *Kalender bulanan*, dan *Fokus* ("bulan ini/minggu ini").
- Setiap tugas: judul, deskripsi, tenggat (`date`), status (`belum`,
  `dikerjakan`, `selesai`, `dilewati`), prioritas, penanggung jawab (anggota
  workspace), fase, relasi opsional ke kategori vendor/item anggaran,
  `date_locked` (tenggat dikunci manual).
- **Penjadwalan relatif hari H**: template tugas menyimpan `offset`
  (mis. "24 bulan sebelum", "10 hari sebelum"). Tenggat dihitung dari tanggal
  hari H. Ketika tanggal hari H diubah, aplikasi **menghitung ulang tenggat
  tugas yang belum selesai & belum dikunci**, menampilkan pratinjau
  perubahan, dan meminta konfirmasi sebelum menyimpan. Tugas selesai/dikunci
  tidak berubah.
- Jika waktu tersisa lebih pendek dari offset template (mis. tanggal
  dimajukan), tugas yang tenggatnya sudah lewat ditandai "perlu dijadwal
  ulang" — bukan diam-diam dipindah ke masa lalu.
- Peringatan risiko: kategori vendor kritis belum dipilih padahal sudah
  melewati "waktu ideal" (offset) ditampilkan di Dashboard.
- Ekspor kalender `.ics` (unduhan, Fase 2). Tidak ada feed publik.

### F. Vendor (MVP)
**Kategori/slot vendor** (template, bisa ditambah, ditandai *dibutuhkan /
tidak dibutuhkan / opsional*):
- **Inti:** venue/gedung, katering, dekorasi & florist, dokumentasi
  (foto & video), rias (MUA), busana pengantin (gaun/jas/kebaya/adat),
  wedding organizer atau day-of coordinator.
- **Pendukung:** MC, hiburan & musik, sound/lighting, undangan & cetak,
  souvenir, cincin, seserahan & mahar, transportasi, akomodasi keluarga/tamu
  luar kota, kue pernikahan/wedding cake, photobooth.
- **Administrasi & prosesi:** KUA/catatan sipil/pemuka agama atau lembaga
  terkait (lihat catatan hukum di 4.G).
- **Opsional:** pre-wedding, honeymoon, hotel malam pertama, keamanan & parkir,
  panitia keluarga.

**Alur per slot (pipeline)**: `belum_dicari` → `riset` → `shortlist` →
`survei_terjadwal` → `sudah_survei` → `negosiasi` → `terpilih` →
`dp_dibayar` → `lunas`. Status slot diturunkan dari kandidat terpilih &
pembayarannya, bukan diisi manual yang bisa tidak konsisten.

**Kandidat vendor** (`vendor_candidates`): nama, kategori, kota, kontak
(telepon/WhatsApp, Instagram, situs — URL divalidasi), nama paket, **harga
penawaran** (integer rupiah) + tanggal penawaran & masa berlaku, apa saja
yang termasuk/tidak termasuk, kelebihan & kekurangan, sumber (rekomendasi,
wedding fair, media sosial), skor pribadi per kriteria yang dapat diatur
(mis. harga, kualitas, komunikasi, kecocokan gaya; 1–5), catatan, status
(`shortlist`, `dihubungi`, `survei`, `negosiasi`, `terpilih`, `ditolak`).
- **Hanya satu kandidat `terpilih` per slot** (unique partial index +
  validasi). Memilih kandidat membuat/memperbarui "Kesepakatan" item anggaran
  terkait (dengan konfirmasi) dan menawarkan jadwal pembayaran.
- **Perbandingan berdampingan** (hingga 4 kandidat): harga, inklusi/eksklusi,
  skor tertimbang, total proyeksi, selisih terhadap estimasi anggaran.
  Di ponsel tampil sebagai kartu bertumpuk/gulir horizontal yang tetap terbaca.
- **Catatan pertemuan/survei**: tanggal, catatan, foto (Cloudinary),
  tindak lanjut → dapat dijadikan tugas dengan satu tindakan.
- **Dokumen vendor** (kontrak, invoice, bukti bayar — Fase 2): media privat.
- Tombol "WhatsApp" membuka `wa.me` dengan nomor yang dinormalisasi;
  tautan eksternal memakai `rel="noopener noreferrer"`.
- **Jangan mengisi data vendor nyata atau harga pasar sebagai seed.**
  Kandidat selalu input pengguna. Data contoh hanya lewat factory untuk dev/test,
  berlabel jelas dan tidak ikut seeder production.

### G. Template awal (seed, dapat diubah)
Label di UI: **"Template umum — sesuaikan dengan adat, agama, dan keluarga
Anda."** Persyaratan administrasi/legal berbeda per agama, daerah, dan waktu;
template hanya menulis tugas "cari tahu & verifikasi persyaratan resmi di
KUA/catatan sipil/lembaga terkait", **tanpa menyebut aturan, durasi, atau
biaya spesifik sebagai fakta**.

Offset relatif hari H (T = hari H); fase dan angka hanya titik awal:

| Fase | Perkiraan waktu | Contoh tugas |
| --- | --- | --- |
| 1. Fondasi | T-24 s/d T-21 bln | Diskusi visi & restu keluarga; tetapkan perkiraan jumlah tamu; tetapkan total anggaran & sumber dana; buka rekening/pos tabungan khusus; tentukan pembagian peran dua keluarga |
| 2. Konsep & riset | T-21 s/d T-18 bln | Tentukan gaya acara & prioritas (mana yang paling penting); riset venue; kunjungi wedding fair; buat daftar vendor yang dibutuhkan |
| 3. Kunci yang langka | T-18 s/d T-14 bln | Survei & pilih venue (tanggal populer cepat penuh); tentukan tanggal final; pilih WO/koordinator bila dipakai; booking dokumentasi & katering bila terpisah |
| 4. Vendor utama | T-14 s/d T-9 bln | Pilih rias, busana, dekorasi, MC/hiburan; jadwalkan pre-wedding; mulai daftar tamu awal |
| 5. Detail | T-9 s/d T-6 bln | Desain undangan; souvenir; daftar seserahan; cincin; rencana akomodasi & transport |
| 6. Administrasi | T-6 s/d T-3 bln | Cari tahu & lengkapi persyaratan resmi pernikahan; kursus/bimbingan pranikah bila relevan; cek kesehatan; fitting pertama |
| 7. Finalisasi | T-3 s/d T-1 bln | Cetak & sebar undangan; konfirmasi kehadiran; tasting; rundown acara; fitting akhir; konfirmasi semua vendor |
| 8. Minggu terakhir | T-4 minggu s/d H-1 | Pelunasan; gladi resik; siapkan amplop/pembayaran sisa; briefing keluarga & panitia; kemas kebutuhan |
| 9. Hari H & sesudahnya | H s/d H+1 bln | Delegasikan tugas hari H; bayar sisa; kirim ucapan terima kasih; arsipkan dokumen & foto; evaluasi anggaran |

### H. Fase 2 & 3 singkat
- **Daftar tamu (F2):** nama, kelompok/sisi keluarga, jumlah orang, status
  undangan, catatan khusus (diet/akomodasi). Memberi angka tamu ke anggaran.
  Tanpa seating chart.
- **Moodboard (F2):** gambar (Cloudinary) + tag + tautan referensi (hanya
  disimpan, tidak di-fetch server → hindari SSRF) + catatan palet warna.
- **Catatan & keputusan (F2):** log keputusan keluarga ("kita sepakat X karena Y").
- **Ekspor (F2):** CSV anggaran/vendor/tugas, PDF ringkasan anggaran, `.ics`, dan
  ekspor penuh data (JSON) sebagai cadangan.
- **PWA (F2):** manifest + ikon, dapat dipasang di layar utama ponsel;
  offline penuh tidak wajib.
- **Tautan baca-saja (F3):** token bertanda tangan, dapat dicabut, cakupan
  dipilih (mis. hanya timeline & vendor, **anggaran tersembunyi secara default**).

---

## 5. Integrasi Cloudinary — wajib

Cloudinary menyimpan **foto (moodboard, kunjungan vendor), dokumen PDF
(kontrak, invoice, bukti bayar)**, dan opsional video/audio. Database hanya
menyimpan metadata & referensi, tidak ada blob di PostgreSQL.

### 5.1 Implementasi
- Satu service terpusat di balik interface kecil (`MediaStorage` →
  `CloudinaryService`) agar controller tidak memegang detail SDK dan test
  memakai fake. Gunakan SDK PHP resmi; **periksa dokumentasi & versi aktual
  sebelum menulis kode**, jangan menebak API.
- Konfigurasi lewat env, **satu bentuk yang konsisten**: `CLOUDINARY_URL`
  *atau* tiga variabel `CLOUDINARY_CLOUD_NAME/API_KEY/API_SECRET`; plus
  `CLOUDINARY_FOLDER` dan batas ukuran di `config/media.php`. Semua ada di
  `.env.example` dengan nilai kosong/contoh (bukan host/ID nyata).
- Hanya `cloud_name` yang boleh sampai ke browser. **API secret tidak pernah**
  muncul di browser, repo, log, props Inertia, atau response.
- Kredensial kosong → aplikasi tetap jalan; fitur unggah menampilkan pesan
  konfigurasi yang jelas. Jangan berpura-pura upload berhasil.

### 5.2 Visibilitas — pada dasarnya SEMUA PRIVAT
Aplikasi ini privat. Default semua aset **`type: authenticated`** dan
dikirim lewat **signed URL berumur pendek** yang dibuat server setelah Policy
lolos. Jangan simpan signed URL di database. Dokumen keuangan/kontrak tidak
pernah publik. Periksa cara Cloudinary memperlakukan PDF (`image` vs `raw`)
di dokumentasi resmi dan simpan `resource_type` sesuai kenyataan.

### 5.3 Alur upload
1. Browser meminta signature ke endpoint server (terautentikasi, rate limited).
2. Server memverifikasi anggota workspace, Policy atas subjek (vendor/item/
   catatan), tipe, ukuran, dan **menentukan sendiri** folder
   (`{CLOUDINARY_FOLDER}/weddings/{wedding-id}/...`) dan parameter yang
   ditandatangani. Klien tidak boleh menyodorkan folder/`public_id`.
3. Setelah upload, server **memverifikasi ulang ke Cloudinary** (aset ada,
   folder benar, tipe/ukuran sesuai) sebelum menyimpan `media_assets`.
4. Upload kecil via server: validasi MIME nyata dan ukuran; nama file asli
   diabaikan.
5. Aset yatim (terupload tapi tidak tersimpan) dibersihkan job terjadwal.

### 5.4 Tipe & batas
Foto: JPEG, PNG, WebP, AVIF. Dokumen: PDF. Video: MP4/WebM, audio:
MP3/OGG/WAV (Fase 3; audio di Cloudinary bertipe `video`). Tolak
executable, arsip, dan SVG unggahan. Batas ukuran per tipe dari config
(jangan berasumsi batas paket Cloudinary).

### 5.5 Penyimpanan, delivery, hapus
- `media_assets`: `provider`, `public_id` (unique), `kind`
  (`image`/`document`/`video`/`audio`), `resource_type`, `delivery_type`,
  `format`, `width`, `height`, `bytes`, `alt_text`, `caption`, relasi
  polymorphic ke vendor/pembayaran/catatan/moodboard, `uploaded_by`,
  `status` (`pending`/`ready`/`failed`/`deleting`), timestamps.
- Bangun URL lewat SDK (bukan string manual); `f_auto,q_auto`, lebar
  responsif; thumbnail kecil untuk daftar; `loading="lazy"`; ukuran tetap
  (cegah CLS).
- Hapus: tandai `deleting` → hapus di Cloudinary → hapus record; bila gagal,
  record tetap dan dicoba ulang job. Server mencari aset berdasarkan ID record
  milik workspace, **bukan** menerima `public_id` dari request.
- Mengganti media: unggah baru sukses dulu, baru hapus lama. Tidak ada record setengah jadi.
- `docs/CLOUDINARY.md` memuat setup, alur, troubleshooting, dan cara menguji
  tanpa kredensial (fake).

---

## 6. Database dan model domain

### 6.1 Prinsip
PostgreSQL; foreign key, unique constraint, index (FK, kolom filter/sort,
`wedding_id`), backed enum + cek DB bila praktis, timestamps, soft deletes
hanya bila bermanfaat (mis. kandidat vendor yang ditolak tetap bisa dilihat).
Uang **integer rupiah**. Migrasi reversible bila praktis, tidak mengedit
migrasi lama yang sudah dipakai. Semua tabel data milik pengguna punya
`wedding_id` dan di-scope.

### 6.2 Entitas
`users`, `weddings` (nama pasangan, `target_date` nullable, `target_month`,
timezone, kota, `guest_estimate`, `total_budget` nullable, persen dana tak
terduga, persen buffer harga), `wedding_members` (role owner/partner),
`invitations` (token hash, kedaluwarsa, sekali pakai), `budget_categories`,
`budget_items` (`pricing_mode` fixed/per_guest, estimasi min/maks,
kesepakatan, relasi opsional ke kandidat vendor), `funding_sources`,
`funding_plans` (setoran bulanan terencana), `payments` (jadwal & realisasi),
`vendor_slots` (kategori, `need` dibutuhkan/opsional/tidak), `vendor_candidates`,
`vendor_scores` & `score_criteria`, `vendor_meetings`, `tasks`,
`task_templates` (offset), `guests` & `guest_groups` (F2), `notes`,
`media_assets`, `activity_logs` (aksi sensitif: ubah anggaran total, hapus,
undang/keluarkan anggota, ganti tanggal hari H), plus tabel standar
Laravel (`jobs`, `sessions`, `cache`, dll.).
Buat hanya yang dibutuhkan fase yang sedang dikerjakan.

### 6.3 Seeder
- `ProductionSeeder` / onboarding menyalin **template** (kategori anggaran,
  slot vendor, tugas) ke workspace pengguna. Tanpa nama vendor, harga, atau
  data pribadi.
- Factory untuk semua model, dipakai test & data contoh lokal (berlabel
  contoh; tidak dijalankan di production).
- Tidak ada kredensial default. Akun pertama dibuat lewat perintah artisan.

---

## 7. Keamanan dan privasi

Aplikasi ini menyimpan **data keuangan, kontrak, dan data pribadi dua
keluarga**; perlakukan sebagai sensitif.

- Semua halaman & endpoint di balik autentikasi, kecuali login/reset/terima
  undangan. Tidak ada pendaftaran publik (uji bahwa route register 404).
- **IDOR**: setiap query di-scope ke `wedding_id` anggota + Policy; uji
  mengakses data workspace lain lewat ID → 403/404.
- CSRF, validasi server-side (pesan Indonesia, `lang/id`), escaping, mass-assignment
  protection, hash password, session aman (`secure`, `httponly`, `same_site`).
- Rate limit: login 5/menit per email+IP; undangan & reset password;
  signature upload 30/menit per user.
- 2FA disarankan/diaktifkan; password kuat; sesi lama dicabut saat ganti password.
- Token undangan/tautan baca-saja: acak kuat, simpan hash, kedaluwarsa, sekali pakai/dapat dicabut.
- Header keamanan (CSP yang kompatibel Vite & mengizinkan Cloudinary, HSTS di
  production, `X-Content-Type-Options`, `Referrer-Policy`, `frame-ancestors`),
  `X-Robots-Tag: noindex` dan `robots.txt` disallow semua.
- Tidak ada secret di source, bundle, commit, atau log; `.env.example` hanya
  nama variabel & nilai kosong (jangan isi host/username/ID proyek nyata).
  Lakukan secret scan sebelum commit akhir. Jangan log password, token,
  signed URL, atau jumlah uang bersama identitas yang tak perlu.
- Link eksternal (vendor/Instagram) divalidasi skema `http/https`, tampil
  dengan `rel="noopener noreferrer"`; server **tidak** meng-fetch URL buatan pengguna.
- Halaman error aman tanpa stack trace di production; `APP_DEBUG=false`.
- **Cadangan**: dokumentasikan `pg_dump` terjadwal + uji restore; sediakan
  ekspor data penuh (JSON/CSV) yang dapat diunduh pemilik.
- Hak pengguna: pemilik dapat menghapus akun/workspace (hapus data + aset Cloudinary).
- `composer audit` & `npm audit`: jalankan dan laporkan hasilnya.

---

## 8. Performa

Target (ukur, jangan klaim tanpa bukti): LCP ≤ 2,5 dtk, CLS ≤ 0,1,
INP ≤ 200 ms pada halaman utama di ponsel (throttled bila alat tersedia).
Hindari N+1 (`preventLazyLoading` di non-production + assertion jumlah query
pada halaman dashboard & anggaran). Code-splitting per halaman; grafik
dimuat lazy; font self-host; media lazy dengan ukuran tetap. Cache hanya hal
aman dan terinvalidasi; tanpa Redis. Dokumentasikan `config:cache`,
`route:cache`, `view:cache`. Jika Lighthouse tersedia, jalankan untuk
dashboard, anggaran, dan vendor; laporkan angka asli dan keterbatasannya,
atau tulis "tidak diukur".

---

## 9. Testing dan quality gates

### 9.1 Backend (PHPUnit/Pest sesuai starter kit; **test memakai PostgreSQL**)
- **Anggaran**: proyeksi dengan/ tanpa kesepakatan, item per tamu & perubahan
  jumlah tamu, rentang min–maks, dana tak terduga, buffer harga, alokasi
  template (jumlah **tepat sama** dengan total, kasus pembulatan), status
  aman/mendekati/melebihi, anggaran total kosong, nilai nol.
- **Rencana tabungan**: sisa bulan = 0/1/negatif, tanpa tanggal hari H,
  dana terkumpul ≥ kebutuhan, setoran kurang/lebih, titik temu.
- **Pembayaran**: pembayaran > kesepakatan, pembatalan, sinkron "Terbayar" dalam transaksi.
- **Timeline**: generasi tugas dari offset, perhitungan ulang saat hari H
  berubah (hanya tugas belum selesai & belum dikunci), pratinjau perubahan,
  tanggal dimajukan sehingga tenggat lewat → "perlu dijadwal ulang", zona waktu/tahun kabisat/akhir bulan.
- **Vendor**: satu kandidat `terpilih` per slot (konkurensi dasar), efek pemilihan ke anggaran, turunan status slot.
- **Akses**: register publik 404; pengguna di luar workspace tidak bisa
  membaca/mengubah apa pun; pasangan punya akses; `partner` tidak bisa
  mengeluarkan owner; undangan kedaluwarsa/dipakai ulang ditolak.
- **Cloudinary** (fake): signature, validasi parameter, verifikasi server,
  hapus hanya oleh anggota workspace pemilik aset, kegagalan SDK.
- Template tidak menimpa perubahan pengguna saat dijalankan ulang.

### 9.2 Frontend
- `tsc --noEmit` strict tanpa error; ESLint + Prettier.
- Test komponen/form kunci (Vitest + Testing Library): form anggaran, pilih
  vendor, ubah tanggal hari H dengan pratinjau, state loading/error/empty.
- E2E smoke dengan Playwright (Chromium sudah tersedia; jangan `playwright install`):
  login → onboarding → isi anggaran → tambah kandidat & pilih → ubah tanggal
  → dashboard menampilkan angka konsisten. Tanpa error console, tanpa link/gambar rusak.
- Axe pada halaman kunci + cek keyboard manual; checklist responsif 360/768/1024/1440.
- Tidak ada tombol yang tidak melakukan apa-apa, tautan `#` dekoratif, atau form yang tidak mengirim data.

### 9.3 CI
Workflow (mis. GitHub Actions) dengan service PostgreSQL: install, migrasi,
Pint `--test`, test backend, `npm ci`, ESLint, `tsc`, test frontend, `npm run build`.

### 9.4 Sebelum dinyatakan selesai
Jalankan dan laporkan hasil nyata: migrate (+ rollback sanity), seed/template,
test, lint, typecheck, build, audit dependency. Yang tidak bisa dijalankan
dicatat alasannya; jangan mengklaim lolos.

---

## 10. Dokumentasi dan deliverables

Sesuai kode aktual: `README.md` (produk, stack+versi, instalasi, konfigurasi,
membuat akun pertama, development, test, build, deployment), `.env.example`,
`AGENTS.md` (aturan 0.3 + perintah dev/test/lint), `docs/ARCHITECTURE.md`,
`docs/DATABASE.md` (entitas, **rumus anggaran & tabungan**, aturan timeline),
`docs/CLOUDINARY.md`, `docs/DESIGN_SYSTEM.md`, `docs/DEPLOYMENT.md`
(hosting hemat biaya, HTTPS, queue worker & scheduler, backup & restore
`pg_dump`, health check `/up`, rollback), `docs/ASSETS.md`,
`docs/STATUS.md` (selesai / sebagian / belum / menunggu keputusan),
`docs/PROGRESS.md` (catatan kerja per milestone).

---

## 11. Cara kerja agent — urutan wajib

1. **Audit**: direktori kerja, Git status, versi runtime, PostgreSQL, Node,
   Chromium, akses jaringan, skill `ui-ux-promax`. Ringkas di `docs/PROGRESS.md`.
2. **Rencana**: milestone terverifikasi (MVP → Fase 2 → Fase 3). Jangan
   bertanya hal non-esensial; pilih default masuk akal yang mudah diubah
   (semua parameter bisnis ada di Pengaturan, bukan kode).
3. **Desain dulu**: selesaikan Gerbang 1–3 Bagian 0.2 (skill, `MASTER.md`,
   `DESIGN_SYSTEM.md`) **sebelum** menulis UI.
4. **Fondasi**: bootstrap starter kit, matikan registrasi publik, workspace &
   anggota, Policy/scope, token desain, layout mobile-first, komponen
   reusable, halaman error, CI.
5. **Fitur end-to-end** per milestone: database → logika hitung (class murni +
   test) → controller/request → UI → test → dokumentasi. Urutan: onboarding &
   pengaturan → anggaran → timeline & tugas → vendor → dashboard → Fase 2.
   Commit kecil bermakna (Conventional Commits). Jangan push ke branch
   utama langsung dan jangan membuat PR kecuali diminta.
6. **Verifikasi & polish**: semua quality gate (9.4), Gerbang 5 (screenshot
   4 viewport + review anti-slop), review keamanan akhir (IDOR, mass
   assignment, upload, secret scan, header).
7. **Laporan akhir**: fitur selesai/sebagian/belum; keputusan arsitektur;
   file penting; perintah & hasil nyata; cara menjalankan dari nol; env yang
   perlu diisi; hal terblokir kredensial/keputusan; risiko & temuan
   keamanan; status skill `ui-ux-promax`.

---

## 12. Definition of Done

- [ ] Aplikasi berjalan dari nol mengikuti README; akun pertama bisa dibuat; pasangan bisa diundang.
- [ ] MVP selesai penuh; Fase 2/3 dilaporkan jujur.
- [ ] Anggaran, rencana tabungan, timeline (dengan penjadwalan ulang), dan vendor (kandidat, perbandingan, pilihan) bekerja dengan data nyata di database.
- [ ] Rumus terdokumentasi di `docs/DATABASE.md` dan **diuji** (termasuk kasus tepi).
- [ ] Tidak ada pendaftaran publik; semua data ter-scope workspace; tidak ada IDOR (diuji).
- [ ] Data keuangan/dokumen privat; Cloudinary aman (signed, terverifikasi server, secret tidak bocor); konfigurasi kosong ditangani jujur.
- [ ] Template jelas berlabel "template umum"; tidak ada data vendor/harga/hukum karangan.
- [ ] Skill `ui-ux-promax` dimuat dan dipakai (atau ketidaktersediaannya dilaporkan); `MASTER.md` & `docs/DESIGN_SYSTEM.md` ada dan diikuti.
- [ ] Screenshot 4 viewport di `docs/screenshots/` dan lolos review anti-slop (3.2); nyaman dipakai di ponsel.
- [ ] Aksesibilitas WCAG 2.2 AA diperiksa (otomatis + keyboard); status anggaran tidak hanya bergantung warna.
- [ ] `tsc`, lint, test backend & frontend, build, audit dependency dijalankan dan dilaporkan apa adanya; CI terkonfigurasi.
- [ ] Cadangan & ekspor data terdokumentasi dan diuji.
- [ ] Tidak ada secret/identitas nyata di commit atau `.env.example`.
- [ ] Kode sumber terbaca (tidak minified), mengikuti Pint/Prettier/ESLint.
- [ ] README & docs sesuai kode aktual; tidak ada klaim palsu tentang test, integrasi, data, atau performa.

---

## Instruksi penutup

Mulai dengan audit lingkungan dan memuat skill `ui-ux-promax`. Lanjutkan
mandiri hingga batas kemampuan lingkungan. **Utamakan aplikasi yang benar-benar
berfungsi, aman, dan nyaman dipakai dua orang selama dua tahun persiapan,
daripada banyak fitur atau tampilan demo.** Jangan melakukan rewrite besar
tanpa alasan teknis dan jangan mengarang hasil verifikasi.

