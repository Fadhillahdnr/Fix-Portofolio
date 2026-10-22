<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Experience;
use App\Models\Profile;
use App\Models\Project;
use App\Models\Service;
use App\Models\Skill;
use App\Models\SocialLink;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        Service::whereIn('slug', ['product-engineering', 'experience-design', 'creative-development'])->delete();
        Experience::where('company', 'Independent')->where('position', 'Creative Developer')->delete();
        Skill::whereIn('slug', ['product-design', 'threejs'])->delete();
        Category::where('slug', 'digital-product')->whereDoesntHave('projects')->delete();

        $admin = User::updateOrCreate(
            ['email' => env('ADMIN_EMAIL', 'admin@example.com')],
            ['name' => env('ADMIN_NAME', 'Muhamad Fadhillah Dinurahman'), 'password' => env('ADMIN_PASSWORD', 'change-this-password'), 'role' => 'admin'],
        );

        Profile::updateOrCreate(['user_id' => $admin->id], [
            'headline' => 'Fullstack Developer — Java, Spring Boot & Modern Web',
            'short_bio' => 'Fresh graduate Web Development yang membangun aplikasi fullstack, REST API, sistem IoT, dan eksperimen AI/ML dengan fokus utama Java, Spring Boot, PostgreSQL, Nuxt, TypeScript, dan Laravel.',
            'full_bio' => 'Saya mengembangkan kemampuan dari web development menuju backend, database, fullstack, IoT, embedded system, dan AI/ML. Kekuatan utama saya adalah menghubungkan frontend, backend/API, database, deployment, hingga perangkat keras menjadi satu sistem yang dapat dipahami dan dikembangkan. Saat ini saya memusatkan pengembangan profesional pada Java, Spring Boot, PostgreSQL, REST API, security, testing, Docker, dan system design dengan arah karier menuju Senior Fullstack Java Developer.',
            'location' => 'Sukabumi, Indonesia',
            'availability' => 'Terbuka untuk peluang Fullstack / Backend Java Developer',
        ]);

        $skills = collect([
            ['Java', 'Backend', 88], ['Spring Boot', 'Backend', 84], ['PHP', 'Backend', 84], ['Laravel', 'Backend', 87],
            ['PostgreSQL', 'Database', 85], ['MySQL', 'Database', 78], ['MongoDB', 'Database', 62], ['Supabase', 'Database', 76],
            ['JavaScript', 'Frontend', 82], ['TypeScript', 'Frontend', 80], ['Vue.js', 'Frontend', 80], ['Nuxt', 'Frontend', 78],
            ['Angular', 'Frontend', 70], ['Inertia.js', 'Frontend', 74], ['Docker', 'DevOps', 78], ['Git', 'DevOps', 85],
            ['ESP32', 'IoT', 84], ['REST API', 'Architecture', 86], ['TensorFlow Lite Micro', 'AI/ML', 62],
            ['Random Forest', 'AI/ML', 68], ['NLP', 'AI/ML', 64], ['Cloudinary', 'Cloud', 72],
        ])->map(function ($item, $index) {
            return Skill::updateOrCreate(['slug' => Str::slug($item[0])], [
                'name' => $item[0], 'category' => $item[1], 'proficiency' => $item[2],
                'sort_order' => $index, 'is_featured' => $index < 16,
            ]);
        })->keyBy('name');

        $categories = collect([
            'Fullstack' => 'Aplikasi yang menghubungkan frontend, backend, dan database.',
            'Java' => 'Backend dan aplikasi enterprise berbasis Java.',
            'Web' => 'Aplikasi dan pengalaman berbasis web.',
            'E-Commerce' => 'Marketplace, transaksi, katalog, dan pengalaman belanja.',
            'IoT' => 'Integrasi sensor, perangkat, komunikasi, backend, dan dashboard.',
            'AI/ML' => 'Machine learning, NLP, predictive analytics, dan TinyML.',
            'DevOps' => 'Deployment, container, environment, dan troubleshooting produksi.',
        ])->map(fn ($description, $name) => Category::updateOrCreate(['slug' => Str::slug($name)], ['name' => $name, 'description' => $description]));

        foreach ([
            ['Backend Java & REST API', 'Pengembangan backend dengan Java, Spring Boot, arsitektur controller–service–repository, autentikasi, integrasi database, dan REST API.'],
            ['Fullstack Web Development', 'Membangun aplikasi end-to-end menggunakan Laravel atau Spring Boot dengan Vue, Nuxt, Angular, TypeScript, PostgreSQL, dan MySQL.'],
            ['IoT & Web Monitoring', 'Mengintegrasikan ESP32, sensor lingkungan, GPS/GSM, backend, database, dan dashboard web untuk monitoring data.'],
            ['Database & Deployment', 'Perancangan schema PostgreSQL/MySQL, Docker development environment, deployment Laravel, serta troubleshooting koneksi dan runtime.'],
        ] as $index => $service) {
            Service::updateOrCreate(['slug' => Str::slug($service[0])], ['title' => $service[0], 'description' => $service[1], 'sort_order' => $index, 'is_active' => true]);
        }

        foreach ([
            ['Universitas Muhammadiyah Sukabumi', 'Sarjana — Web Development', 'Mengembangkan fondasi pemrograman, web, database, IoT, dan AI/ML melalui pembelajaran serta proyek akademik.', '2021-09-01', '2025-08-01'],
            ['Independent Professional Development', 'Fullstack & Backend Developer', 'Memperdalam Java, Spring Boot, PostgreSQL, Docker, Nuxt, TypeScript, REST API, deployment, dan system troubleshooting melalui proyek terarah.', '2025-08-01', null],
            ['Enterprise Training Project', 'Java & Angular Developer', 'Mengembangkan modul Cetak Kwitansi untuk penjualan distro pada existing codebase Java 11, Spring Boot 2.7.18, Angular 9, Clarity Design, dan MySQL.', '2025-01-01', '2025-06-01'],
        ] as $index => $item) {
            Experience::updateOrCreate(['company' => $item[0], 'position' => $item[1]], ['description' => $item[2], 'start_date' => $item[3], 'end_date' => $item[4], 'is_current' => $item[4] === null, 'sort_order' => $index]);
        }

        $projects = [
            ['SemuaAda Marketplace', 'semuaada-marketplace', 'Marketplace multi-seller dengan alur customer, seller, dan admin yang dirancang menggunakan Nuxt, TypeScript, Spring Boot, dan PostgreSQL.', 'Bagaimana merancang marketplace lintas kategori yang memisahkan kebutuhan customer, seller, serta admin tanpa membuat domain transaksi sulit dikembangkan?', 'Menyusun arsitektur REST API dan domain bertahap: authentication, profile, address, product, cart, checkout, order, payment, review, voucher, promotion, serta product moderation.', 'Proyek berada pada tahap development/architecture dan menjadi flagship untuk mendemonstrasikan fullstack Java, role-based access, e-commerce flow, dan desain sistem.', 'Fullstack Developer', ['Fullstack', 'Java', 'E-Commerce'], ['Java', 'Spring Boot', 'Nuxt', 'TypeScript', 'PostgreSQL', 'Git'], true],
            ["Kalung Pintar Al-Qur'an", 'kalung-pintar-alquran', "Wearable IoT dan embedded AI yang menggabungkan pengenalan audio, pemrosesan pada ESP32, serta respons audio Al-Qur'an.", 'Bagaimana menjalankan interaksi suara dan model pengenalan pada perangkat embedded dengan memori serta komputasi terbatas?', 'Merancang integrasi ESP32 DevKit_C, mikrofon I2S INMP441, TensorFlow Lite Micro, DFPlayer Mini, microSD, amplifier PAM8403, dan speaker.', 'Proyek akademik/thesis yang mempertemukan IoT, audio embedded, TinyML, dan edge inference dalam satu arsitektur perangkat.', 'IoT & Embedded Developer', ['IoT', 'AI/ML'], ['ESP32', 'TensorFlow Lite Micro'], true],
            ['Air Quality Monitoring', 'air-quality-monitoring', 'Sistem monitoring kualitas udara berbasis ESP32, sensor lingkungan, Laravel, database, OLED, dan dashboard web.', 'Data suhu, kelembapan, debu, gas, serta ozon perlu dikumpulkan secara stabil dan disajikan agar mudah dipantau.', 'Mengintegrasikan DHT11, GP2Y1010AU0F, MQ-135, MQ-131, OLED SSD1306, smoothing tegangan baterai, backend Laravel, dan dashboard.', 'Menghasilkan pengalaman integrasi sensor-ke-web sekaligus pembelajaran kalibrasi, noise reduction, battery reading, dan monitoring data.', 'IoT & Web Developer', ['IoT', 'Web'], ['ESP32', 'Laravel', 'PHP'], true],
            ['Smart Farming Monitoring', 'smart-farming-monitoring', 'Konsep sistem monitoring pertanian yang menyatukan sensor lingkungan, kelembapan tanah, pH, dan dashboard Laravel.', 'Kondisi udara dan tanah datang dari sensor berbeda sehingga membutuhkan integrasi, pemrosesan, serta penyajian data yang konsisten.', 'Merancang alur ESP32 dengan DHT11, soil moisture, pH, TCS3200, sensor debu/gas, backend, database, dan dashboard web.', 'Project experience pada integrasi multi-sensor dan rancangan sistem monitoring lingkungan/pertanian.', 'IoT & Web Developer', ['IoT', 'Web'], ['ESP32', 'Laravel'], true],
            ['Cetak Kwitansi Distro', 'cetak-kwitansi-distro', 'Modul cetak kwitansi penjualan distro pada aplikasi enterprise/training berbasis Java 11, Spring Boot, Angular 9, dan MySQL.', 'Fitur baru harus mengikuti pola existing codebase dan menghubungkan UI enterprise dengan backend serta database yang sudah tersedia.', 'Menavigasi struktur controller, service, repository, entity, HTTP integration, Angular/TypeScript, dan komponen Clarity Design.', 'Memberikan pengalaman feature development pada stack enterprise Java serta bekerja di dalam codebase yang telah memiliki pola arsitektur.', 'Java & Angular Developer', ['Java', 'Fullstack'], ['Java', 'Spring Boot', 'Angular', 'TypeScript', 'MySQL'], true],
            ['GPS Tracking System', 'gps-tracking-system', 'Sistem pelacakan lokasi berbasis ESP32, GPS NEO-6M, komunikasi seluler SIM800L, server, database, dan dashboard.', 'Perangkat perlu memperoleh koordinat dan mengirimkannya melalui jaringan seluler untuk dapat dipantau dari web.', 'Merancang alur koordinat NEO-6M ke ESP32, transmisi SIM800L, penyimpanan server, dan visualisasi lokasi.', 'Project experience pada integrasi GPS, GSM/cellular, embedded device, backend, dan web monitoring.', 'IoT Developer', ['IoT', 'Web'], ['ESP32'], false],
            ['Prediksi Hasil Panen Udang', 'prediksi-hasil-panen-udang', 'Proyek akademik predictive analytics untuk memperkirakan hasil panen udang menggunakan Random Forest Regression.', 'Data historis budidaya perlu diolah menjadi estimasi hasil panen yang dapat mendukung analisis.', 'Menyusun alur data cleaning, preprocessing, feature engineering, train/test split, pemodelan Random Forest Regression, dan evaluasi prediksi.', 'Memberikan project experience pada supervised learning, regression, dan proses pengolahan dataset terstruktur.', 'Machine Learning Developer', ['AI/ML'], ['Random Forest'], false],
            ['Sentiment Analysis', 'sentiment-analysis', 'Proyek akademik NLP untuk mengklasifikasikan teks ke dalam sentimen positif, netral, atau negatif.', 'Teks tidak terstruktur memerlukan pembersihan dan representasi fitur sebelum dapat diklasifikasikan.', 'Menyusun pipeline cleaning, preprocessing, feature extraction, training model, dan klasifikasi sentimen.', 'Memberikan pengalaman mendasar pada NLP, text classification, preprocessing, dan evaluasi model.', 'Machine Learning Developer', ['AI/ML'], ['NLP'], false],
            ['Berdu Product Landing Page', 'berdu-product-landing-page', 'Landing page penjualan produk kaos dengan struktur komunikasi AIDA dan alur menuju checkout atau WhatsApp.', 'Produk membutuhkan halaman yang membawa pengunjung dari perhatian menuju keputusan pembelian secara jelas.', 'Menyusun konten Attention, Interest, Desire, Action, CTA, serta alur pilihan metode pembayaran.', 'Project experience pada landing page e-commerce, conversion flow, dan penerapan kerangka digital marketing AIDA.', 'Web Developer', ['Web', 'E-Commerce'], ['JavaScript'], false],
            ['Laravel Cloud Deployment', 'laravel-cloud-deployment', 'Deployment Laravel sekaligus troubleshooting kompatibilitas PHP, Composer, environment, database, dan HTTP 500.', 'Deployment gagal akibat ketidakcocokan runtime PHP/Symfony, lalu aplikasi mengalami kegagalan koneksi database setelah berhasil dibangun.', 'Menyesuaikan runtime ke PHP 8.4, memeriksa dependency Composer, environment variable, koneksi database, dan server-side error.', 'Deployment berhasil dan membentuk pengalaman praktis pada runtime compatibility serta diagnosis masalah production.', 'Laravel Developer', ['DevOps', 'Web'], ['Laravel', 'PHP'], false],
            ['PostgreSQL Fullstack Store', 'postgresql-fullstack-store', 'Environment pembelajaran database dengan PostgreSQL 17, Docker Compose, psql, DBeaver, user khusus, dan pemisahan schema auth, catalog, sales, serta public.', 'Membangun fondasi database relasional yang tidak berhenti pada satu tabel, sekaligus memahami identity, constraint, timestamp, schema, dan hak akses.', 'Menjalankan PostgreSQL dalam container, membuat database dan user, menyusun schema, berlatih CRUD/aggregation, serta menangani duplicate key dan identity restriction.', 'Learning project yang memperkuat SQL, relational modeling, containerized database, serta troubleshooting PostgreSQL.', 'Backend & Database Developer', ['DevOps', 'Fullstack'], ['PostgreSQL', 'Docker'], false],
            ['DB2 Thread Investigation', 'db2-thread-investigation', 'Investigasi teknis terhadap thread/process DB2 yang mengalami kondisi hanging dan runtime issue.', 'Proses database tidak bergerak seperti yang diharapkan dan memerlukan penelusuran dari gejala runtime menuju resource yang digunakan.', 'Menganalisis thread, process ID, resource, open files/socket, dan working directory menggunakan pendekatan process inspection serta lsof.', 'Troubleshooting experience yang memperkuat diagnosis process, port, thread, dan runtime; bukan diklaim sebagai implementasi produk penuh.', 'Technical Troubleshooting', ['DevOps'], [], false],
        ];

        foreach ($projects as $index => $data) {
            [$title, $slug, $summary, $problem, $solution, $result, $role, $categoryNames, $skillNames, $featured] = $data;
            $project = Project::updateOrCreate(['slug' => $slug], [
                'title' => $title, 'summary' => $summary, 'problem' => $problem, 'solution' => $solution,
                'result' => $result, 'role' => $role, 'status' => 'published', 'published_at' => now(),
                'is_featured' => $featured, 'sort_order' => $index, 'created_by' => $admin->id, 'updated_by' => $admin->id,
            ]);
            $project->categories()->sync($categories->filter(fn ($category) => in_array($category->name, $categoryNames))->pluck('id'));
            $project->skills()->sync($skills->filter(fn ($skill) => in_array($skill->name, $skillNames))->pluck('id'));
        }

        SocialLink::updateOrCreate(['platform' => 'GitLab'], ['label' => 'GitLab / Fadhillahdnr', 'url' => 'https://gitlab.com/Fadhillahdnr', 'sort_order' => 0, 'is_active' => true]);
    }
}
