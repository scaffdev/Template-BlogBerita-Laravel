# 📰 Berita Lokal — Template Portal Berita & Blog Modern (Laravel 12)

Template portal berita, media daring, dan blog editorial berbasis **Laravel 12** dengan desain *editorial majalah premium*, sistem peran redaksi bertingkat, liveblog, optimasi SEO lengkap, PWA, dan panel administrasi yang siap pakai.

---

## ✨ Fitur Utama

### 📖 Pengalaman Membaca (Frontend)
- **Identitas Desain Editorial**: Tipografi headline serif **Fraunces** + isi **Source Serif 4**, aksen kertas hangat, dan layout multi-kanal responsif.
- **Reading Utility**: Indikator progress baca, estimasi waktu baca, daftar isi otomatis (TOC), mode gelap (*dark mode*) tersimpan, pembesar/pengecil font (A-/A+), mode fokus, dan Text-to-Speech (TTS).
- **Interaksi Pembaca**: Suka artikel, simpan artikel ke bookmark (`/disimpan`), komentar bertingkat 1-level, dan dialog pelaporan komentar.
- **Pencarian Cerdas**: Autocomplete live (`/cari/saran`), filter kanal/penulis/rentang tanggal, pencarian toleran typo per-kata (`searchSmart`).
- **Format Artikel Khusus**: Seri artikel bersambung, liveblog dengan timeline pembaruan langsung, artikel advertorial/bersponsor, dan co-authors (hingga 3 penulis).
- **SEO & Performa**: JSON-LD Schema (NewsArticle, Breadcrumb), RSS feed (`/feed`), Google News sitemap (`/news-sitemap.xml`), Open Graph + Twitter Cards dinamis, dan PWA siap instalasi offline statis (`/manifest.webmanifest`, `/sw.js`).

### ⚙️ Panel Redaksi & Administrasi (`/admin`)
- **Multi-Role RBAC**: Admin, Editor, Author, dan Reader dengan pemisahan hak akses ketat.
- **Manajemen Artikel**: Editor WYSIWYG, auto-slug, autosave draft lokal/server, penjadwalan terbit, kalkulasi skor SEO (0-100) live, dan preview draft aman.
- **Manajemen Media**: Upload massal, kompresi otomatis thumbnail (300px) & medium (800px), konversi otomatis ke WebP, dan ekstraksi metadata.
- **Kategori, Tag, & Halaman**: CRUD lengkap dengan proteksi slug otomatis dan URL redirect 301 bawaan.
- **Komentar & Moderasi**: Tab filter (pending, approved, spam, reported), aksi massal, dan log pelaporan pembaca.
- **Newsletter**: Tangkap email subscriber, konfirmasi token, unsubscribe 1-klik, dan log broadcast.
- **Analitik & Audit**: Export data artikel & penulis ke CSV, activity logs untuk setiap aksi editorial, dan backup database harian.

---

## 🛠️ Tech Stack

- **Backend**: PHP 8.2+, Laravel 12.x
- **Frontend**: Blade Templates, Tailwind CSS 4, Alpine.js 3, Vanilla JS
- **Build Tool**: Vite 6, Laravel Vite Plugin
- **Database**: SQLite (default lokal) / MySQL / PostgreSQL
- **Asset Processing**: GD / WebP image pipeline bawaan

---

## 📁 Struktur Folder Utama

```
├── app/
│   ├── Console/Commands/       # Backup database (app:backup-db)
│   ├── Http/
│   │   ├── Controllers/Admin/  # Controller panel redaksi (Post, Media, User, dll.)
│   │   ├── Controllers/Frontend/ # Controller publik (Home, Post, Cari, Bookmark, dll.)
│   │   ├── Middleware/         # SecurityHeaders (CSP, HSTS, X-Frame)
│   │   └── Requests/           # Form requests validasi (StorePostRequest, dll.)
│   ├── Models/                 # Eloquent models (Post, Category, Tag, Media, dll.)
│   └── Services/               # ContentSanitizer, ImageService
├── database/
│   ├── migrations/             # Skema tabel posts, categories, live_updates, dll.
│   └── seeders/                # UserSeeder, CategorySeeder, DemoPostsSeeder
├── resources/
│   ├── css/app.css             # Tailwind 4 design system & custom editorial styles
│   ├── js/app.js               # Utility baca, dark mode, autocomplete, shortcuts
│   └── views/
│       ├── admin/              # Tampilan dashboard & form manajemen
│       ├── frontend/           # Tampilan portal berita, single post, arsip, profil
│       └── layouts/            # Master layout publik & admin
├── routes/
│   ├── web.php                 # Rute frontend, auth, dan API publik
│   └── admin.php               # Rute terlindungi panel administrasi
└── scaff.template.json         # Manifest integrasi Scaffdev
```

---

## 🚀 Panduan Instalasi Cepat

### Prasyarat
- PHP >= 8.2 dengan ekstensi `pdo_sqlite` / `pdo_mysql`, `gd`, `fileinfo`, `mbstring`
- Composer 2.x
- Node.js >= 18.x dan npm

### Langkah Instalasi

```bash
# 1. Clone repository
git clone https://github.com/scaffdev/Template-BlogBerita-Laravel.git
cd Template-BlogBerita-Laravel

# 2. Install dependensi PHP & Node
composer install
npm ci

# 3. Setup Environment
cp .env.example .env
php artisan key:generate

# 4. Setup Database & Jalankan Migrasi + Seeder
touch database/database.sqlite
php artisan migrate --seed

# 5. Link storage untuk upload media
php artisan storage:link

# 6. Build asset frontend
npm run build

# 7. Jalankan server lokal
php artisan serve
```

Buka browser di `http://127.0.0.1:8000`.

Untuk mode development dengan hot reload asset:
```bash
npm run dev   # di terminal terpisah
```

---

## 🔐 Akun Demo Bawaan

Password default ditentukan oleh variabel env `SEED_ADMIN_PASSWORD` (default: `ChangeMeBeforeDeploy!`).

| Role | Email | Password | Akses URL |
|---|---|---|---|
| **Admin** | `admin@berita.local` | env `SEED_ADMIN_PASSWORD` | `/admin/login` |
| **Editor** | `editor@berita.local` | env `SEED_ADMIN_PASSWORD` | `/admin/login` |
| **Author** | `author@berita.local` | env `SEED_ADMIN_PASSWORD` | `/admin/login` |
| **Reader** | `reader@berita.local` | env `SEED_ADMIN_PASSWORD` | `/login` |

> ⚠️ **PENTING UNTUK PRODUKSI**: Wajib set `SEED_ADMIN_PASSWORD` dengan kata sandi acak kuat di `.env` sebelum menjalankan seeder di server produksi, atau segera ganti password seluruh akun demo via `/admin/users` atau `/profil`.

---

## ⚙️ Variabel Environment Penting (`.env`)

| Variabel | Keterangan | Default |
|---|---|---|
| `APP_URL` | URL publik portal | `http://127.0.0.1:8000` |
| `DB_CONNECTION` | Driver database (`sqlite`, `mysql`, `pgsql`) | `sqlite` |
| `SEED_ADMIN_PASSWORD` | Kata sandi untuk seeding akun demo | `ChangeMeBeforeDeploy!` |
| `MAIL_MAILER` | Driver email notifikasi redaksi & password reset | `log` |
| `CACHE_STORE` | Driver cache untuk view counters & setting | `database` / `file` |

---

## 🛡️ Keamanan & Hardening

- **Content Sanitization**: Konten artikel disaring menggunakan `ContentSanitizer` dengan allowlist tag HTML aman sebelum disimpan untuk mencegah Stored XSS.
- **JSON-LD Escaping**: Output schema terenkode aman dengan flag hex entities (`JSON_HEX_TAG`, `JSON_HEX_AMP`, dll.) untuk mencegah tag breaking injection.
- **Strict Authorization**: Pemeriksaan kepemilikan diterapkan pada postingan, media, dan live update. Author hanya dapat mengelola konten milik sendiri; Editor/Admin memiliki hak moderasi global.
- **HTTP Security Headers**: Middleware `SecurityHeaders` menyuntikkan CSP, HSTS, `X-Frame-Options: SAMEORIGIN`, `X-Content-Type-Options: nosniff`, dan `Referrer-Policy`.

---

## 🧪 Verifikasi & Pengujian

Untuk panduan instruksi otomatis bagi AI agent testing, lihat file [`VERIFY.md`](./VERIFY.md).

```bash
# Jalankan test suite bawaan
php artisan test

# Audit dependensi keamanan
composer audit
npm audit --omit=dev
```

---

## 📄 Lisensi

Didistribusikan di bawah lisensi MIT. Lihat [`LICENSE`](./LICENSE) untuk rincian lengkap. Hak Cipta © 2026 Scaffdev.
