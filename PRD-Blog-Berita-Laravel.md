# PRD: Website Blog & Berita dengan Laravel

**Versi:** 1.0
**Status:** Draft
**Tanggal:** September 2026

---

## 1. Ringkasan Singkat

Dokumen ini menjelaskan rencana pembuatan website blog/berita menggunakan Laravel. Website ini punya dua sisi:

1. **Sisi Pembaca (Frontend)** — halaman yang dilihat pengunjung: daftar artikel, halaman baca artikel, kategori, pencarian.
2. **Sisi Pengelola (Admin Panel)** — tempat penulis dan editor menulis artikel, mengunggah gambar, mengatur kategori, dan memantau statistik.

Fokus utama: **artikel mudah ditemukan di Google, halaman cepat dibuka, dan penulis nyaman menulis**.

---

## 2. Kenapa Website Ini Dibuat?

### Masalah yang ingin diselesaikan
- Konten masih tersebar di media sosial, tidak punya "rumah" sendiri yang bisa dicari lewat Google.
- Tidak ada kontrol penuh atas tampilan, iklan, dan data pembaca.
- Sulit mengelola banyak penulis tanpa sistem yang jelas.

### Tujuan
| Tujuan | Cara mengukurnya |
|---|---|
| Menjadi sumber informasi yang dipercaya | Jumlah pembaca rutin (returning visitor) |
| Mendatangkan trafik dari Google | Jumlah artikel yang terindeks & peringkat kata kunci |
| Memudahkan tim redaksi bekerja | Waktu dari menulis sampai terbit lebih singkat |
| Membuka peluang monetisasi | Trafik cukup untuk iklan/sponsor |

---

## 3. Siapa Saja Penggunanya?

| Pengguna | Apa yang mereka lakukan |
|---|---|
| **Pembaca (tamu)** | Membaca artikel, mencari topik, membagikan ke media sosial |
| **Pembaca terdaftar** *(opsional)* | Berkomentar, menyimpan artikel, berlangganan newsletter |
| **Penulis (Author)** | Menulis dan mengirim artikel untuk ditinjau |
| **Editor** | Memeriksa, merevisi, menyetujui, dan menjadwalkan artikel |
| **Admin** | Mengelola semua konten, pengguna, kategori, dan pengaturan situs |

---

## 4. Batasan Proyek (Scope)

### Termasuk dalam proyek ini
- Website publik untuk membaca artikel.
- Admin panel lengkap untuk redaksi.
- Sistem kategori, tag, dan pencarian.
- Sistem hak akses bertingkat (Penulis/Editor/Admin).
- SEO dasar sampai menengah.
- Sistem komentar.
- Newsletter (berlangganan email).

### Tidak termasuk (bisa jadi fase berikutnya)
- Artikel berbayar / paywall / langganan premium.
- Aplikasi mobile (Android/iOS) — website sudah responsive.
- Multi-bahasa.
- Forum diskusi.
- Video streaming mandiri (video tetap di-embed dari YouTube).

---

## 5. Daftar Halaman

### 5.1 Halaman Publik (untuk pembaca)

| Halaman | Alamat (URL) | Isinya apa |
|---|---|---|
| Beranda | `/` | Artikel utama (headline), artikel terbaru, artikel populer, per kategori |
| Daftar Artikel | `/artikel` | Semua artikel dengan pagination |
| Baca Artikel | `/artikel/{slug}` | Isi artikel lengkap + artikel terkait + komentar |
| Kategori | `/kategori/{slug}` | Artikel dalam satu kategori |
| Tag | `/tag/{slug}` | Artikel dengan tag tertentu |
| Profil Penulis | `/penulis/{username}` | Bio penulis + daftar tulisannya |
| Pencarian | `/cari?q=` | Hasil pencarian artikel |
| Arsip | `/arsip/{tahun}/{bulan}` | Artikel berdasarkan waktu terbit |
| Halaman Statis | `/tentang`, `/kontak`, `/redaksi`, `/kebijakan-privasi`, `/pedoman-media-siber` | Informasi & legal |
| Login/Daftar Pembaca | `/login`, `/register` | Jika komentar butuh akun |
| 404 | — | Halaman tidak ditemukan |
| RSS Feed | `/feed` | Untuk pembaca yang pakai RSS reader / agregator berita |

> **Catatan:** Untuk media berita di Indonesia, halaman **Pedoman Media Siber** dan **Redaksi** sering jadi syarat verifikasi Dewan Pers. Kalau ini website berita serius, sebaiknya disiapkan sejak awal.

### 5.2 Halaman Admin (untuk tim redaksi)

| Halaman | Alamat | Isinya apa |
|---|---|---|
| Login Admin | `/admin/login` | Masuk ke sistem |
| Dashboard | `/admin` | Ringkasan: artikel terbit, draft menunggu review, artikel terpopuler |
| Daftar Artikel | `/admin/posts` | Tabel semua artikel + filter status |
| Tulis/Edit Artikel | `/admin/posts/create`, `/admin/posts/{id}/edit` | Editor artikel |
| Kategori | `/admin/categories` | Tambah/ubah/hapus kategori |
| Tag | `/admin/tags` | Kelola tag |
| Media Library | `/admin/media` | Semua gambar yang pernah diunggah |
| Komentar | `/admin/comments` | Moderasi komentar (setujui/tolak/hapus) |
| Pengguna | `/admin/users` | Kelola akun penulis, editor, admin |
| Halaman Statis | `/admin/pages` | Kelola konten halaman seperti "Tentang Kami" |
| Newsletter | `/admin/subscribers` | Daftar pelanggan email + kirim broadcast |
| Banner/Iklan | `/admin/ads` | Kelola slot iklan |
| Statistik | `/admin/analytics` | Artikel terpopuler, trafik, performa penulis |
| Pengaturan | `/admin/settings` | Nama situs, logo, SEO default, media sosial |
| Log Aktivitas | `/admin/activity-log` | Catatan siapa mengubah apa |

---

## 6. Detail Fitur

Setiap fitur diberi **prioritas**:
- **Wajib** = harus ada sebelum website bisa dipakai
- **Sebaiknya** = penting, tapi bisa menyusul
- **Opsional** = bagus kalau ada, tidak mendesak

### 6.1 Artikel (Fitur Inti)

| Kode | Kebutuhan | Prioritas |
|---|---|---|
| ART-1 | Artikel punya: judul, slug (URL), ringkasan (excerpt), isi lengkap, gambar utama, penulis, kategori, tag, tanggal terbit | Wajib |
| ART-2 | **Slug otomatis dibuat dari judul**, tapi bisa diubah manual (penting untuk SEO) | Wajib |
| ART-3 | Status artikel: `Draft` → `Menunggu Review` → `Terbit` → `Arsip` | Wajib |
| ART-4 | **Jadwal terbit otomatis** — artikel bisa disiapkan sekarang, terbit otomatis nanti | Wajib |
| ART-5 | Editor teks kaya (rich text): tebal, miring, heading, daftar, kutipan, tabel, sisip gambar, sisip video YouTube | Wajib |
| ART-6 | Penghitung jumlah dibaca (view counter) | Wajib |
| ART-7 | Estimasi waktu baca ("baca 5 menit") — dihitung otomatis dari jumlah kata | Sebaiknya |
| ART-8 | Artikel bisa ditandai sebagai **Headline** / **Pilihan Editor** untuk tampil menonjol di beranda | Sebaiknya |
| ART-9 | **Riwayat revisi** — bisa melihat & mengembalikan versi sebelumnya | Sebaiknya |
| ART-10 | Auto-save draft saat mengetik (biar tulisan tidak hilang) | Sebaiknya |
| ART-11 | **Preview** artikel sebelum terbit (lihat tampilan asli tanpa menerbitkan) | Sebaiknya |
| ART-12 | Artikel terkait (berdasarkan kategori/tag yang sama) | Sebaiknya |
| ART-13 | Satu artikel bisa punya lebih dari satu penulis (co-author) | Opsional |
| ART-14 | Artikel berseri (bagian 1, 2, 3 yang saling terhubung) | Opsional |
| ART-15 | **Liveblog** untuk peliputan langsung (update berurutan dalam satu artikel) | Opsional |

### 6.2 Kategori & Tag

| Kode | Kebutuhan | Prioritas |
|---|---|---|
| KAT-1 | Kategori punya: nama, slug, deskripsi, gambar, urutan tampil | Wajib |
| KAT-2 | Kategori bisa bertingkat (Kategori → Sub-kategori), misal: Olahraga → Sepak Bola | Sebaiknya |
| KAT-3 | Satu artikel masuk ke satu kategori utama | Wajib |
| KAT-4 | Tag bersifat bebas dan satu artikel bisa punya banyak tag | Wajib |
| KAT-5 | Tag dibuat otomatis kalau mengetik tag yang belum ada | Sebaiknya |
| KAT-6 | Halaman kategori & tag punya deskripsi sendiri (bagus untuk SEO) | Sebaiknya |

### 6.3 Pencarian & Navigasi

| Kode | Kebutuhan | Prioritas |
|---|---|---|
| CARI-1 | Pencarian berdasarkan judul, ringkasan, dan isi artikel | Wajib |
| CARI-2 | Filter hasil pencarian: kategori, rentang tanggal, penulis | Sebaiknya |
| CARI-3 | Urutkan hasil: paling relevan, terbaru, terpopuler | Sebaiknya |
| CARI-4 | Saran otomatis saat mengetik (autocomplete) | Sebaiknya |
| CARI-5 | Pencarian toleran typo (salah ketik tetap ketemu) — pakai Meilisearch/Algolia | Opsional |
| CARI-6 | Halaman "tidak ada hasil" menampilkan saran artikel populer | Sebaiknya |

### 6.4 Halaman Baca Artikel

| Kode | Kebutuhan | Prioritas |
|---|---|---|
| BACA-1 | Tampilan nyaman dibaca: ukuran font cukup besar, lebar teks terbatas (±700px), jarak baris lega | Wajib |
| BACA-2 | Breadcrumb (Beranda › Kategori › Judul) | Wajib |
| BACA-3 | Info penulis, tanggal terbit, dan tanggal update | Wajib |
| BACA-4 | Tombol bagikan ke WhatsApp, Facebook, X/Twitter, dan salin tautan | Wajib |
| BACA-5 | Daftar artikel terkait di bawah artikel | Sebaiknya |
| BACA-6 | Indikator progres baca (progress bar di atas halaman) | Opsional |
| BACA-7 | Daftar isi otomatis untuk artikel panjang (dari heading) | Opsional |
| BACA-8 | Mode gelap (dark mode) | Opsional |
| BACA-9 | Ukuran teks bisa diperbesar/diperkecil pembaca | Opsional |

### 6.5 Komentar

| Kode | Kebutuhan | Prioritas |
|---|---|---|
| KOM-1 | Pembaca bisa berkomentar di artikel | Sebaiknya |
| KOM-2 | **Moderasi** — komentar masuk antrian sebelum tampil (mencegah spam & ujaran kebencian) | Wajib (jika komentar aktif) |
| KOM-3 | Balasan bertingkat (reply) minimal 1–2 level | Sebaiknya |
| KOM-4 | Proteksi spam (honeypot / reCAPTCHA / rate limit) | Wajib (jika komentar aktif) |
| KOM-5 | Lapor komentar bermasalah | Sebaiknya |
| KOM-6 | Komentar bisa dimatikan per artikel | Sebaiknya |
| KOM-7 | Alternatif: pakai layanan pihak ketiga (Disqus) untuk menghemat waktu pengembangan | Opsional |

### 6.6 Pengguna & Hak Akses

| Kode | Kebutuhan | Prioritas |
|---|---|---|
| USR-1 | Login, logout, lupa password | Wajib |
| USR-2 | **Empat peran** dengan hak berbeda (lihat tabel di bawah) | Wajib |
| USR-3 | Profil penulis: foto, bio singkat, tautan media sosial | Wajib |
| USR-4 | Verifikasi email saat pendaftaran | Sebaiknya |
| USR-5 | Login dengan Google | Opsional |
| USR-6 | Autentikasi dua faktor (2FA) untuk akun admin | Opsional |

**Tabel Hak Akses:**

| Aksi | Pembaca | Penulis | Editor | Admin |
|---|:---:|:---:|:---:|:---:|
| Membaca artikel terbit | ✅ | ✅ | ✅ | ✅ |
| Berkomentar | ✅ | ✅ | ✅ | ✅ |
| Menulis artikel baru | ❌ | ✅ | ✅ | ✅ |
| Mengedit artikel sendiri | ❌ | ✅ | ✅ | ✅ |
| Mengedit artikel orang lain | ❌ | ❌ | ✅ | ✅ |
| Menerbitkan artikel | ❌ | ❌ | ✅ | ✅ |
| Menghapus artikel | ❌ | ❌ | ✅ | ✅ |
| Moderasi komentar | ❌ | ❌ | ✅ | ✅ |
| Kelola kategori & tag | ❌ | ❌ | ✅ | ✅ |
| Kelola pengguna | ❌ | ❌ | ❌ | ✅ |
| Ubah pengaturan situs | ❌ | ❌ | ❌ | ✅ |

### 6.7 Media & Gambar

| Kode | Kebutuhan | Prioritas |
|---|---|---|
| MED-1 | Unggah gambar dengan drag-and-drop | Wajib |
| MED-2 | **Kompresi & ubah ukuran otomatis** saat diunggah (gambar besar bikin website lambat) | Wajib |
| MED-3 | Membuat beberapa ukuran otomatis: thumbnail, sedang, besar | Wajib |
| MED-4 | Konversi ke format WebP (ukuran file lebih kecil) | Sebaiknya |
| MED-5 | Media library — cari & pakai ulang gambar yang sudah diunggah | Sebaiknya |
| MED-6 | Isian `alt text` dan keterangan gambar (wajib untuk SEO & aksesibilitas) | Wajib |
| MED-7 | Kredit/sumber foto (penting untuk media berita) | Sebaiknya |
| MED-8 | Pemotongan gambar (crop) langsung di admin | Opsional |

### 6.8 SEO

| Kode | Kebutuhan | Prioritas |
|---|---|---|
| SEO-1 | Meta title & meta description bisa diisi manual per artikel | Wajib |
| SEO-2 | URL bersih dan deskriptif (`/artikel/cara-merawat-tanaman-hias`) | Wajib |
| SEO-3 | **Structured data JSON-LD**: tipe `NewsArticle`/`BlogPosting`, `BreadcrumbList`, `Organization` | Wajib |
| SEO-4 | Open Graph & Twitter Card (preview cantik saat dibagikan di WA/FB) | Wajib |
| SEO-5 | `sitemap.xml` otomatis terisi semua artikel & kategori | Wajib |
| SEO-6 | `robots.txt` | Wajib |
| SEO-7 | Canonical URL (mencegah konten dianggap duplikat) | Wajib |
| SEO-8 | RSS Feed | Sebaiknya |
| SEO-9 | **Google News Sitemap** (khusus jika website berita, agar masuk Google News) | Sebaiknya |
| SEO-10 | Indikator SEO di editor (peringatan jika judul terlalu panjang, alt text kosong, dsb) | Opsional |
| SEO-11 | Pengalihan (redirect) otomatis jika slug artikel diubah, agar tautan lama tidak mati | Sebaiknya |

### 6.9 Newsletter & Notifikasi

| Kode | Kebutuhan | Prioritas |
|---|---|---|
| NEWS-1 | Form berlangganan email di sidebar/footer | Sebaiknya |
| NEWS-2 | Email konfirmasi berlangganan (double opt-in) | Sebaiknya |
| NEWS-3 | Tautan berhenti berlangganan (unsubscribe) di setiap email — **wajib secara hukum di banyak negara** | Wajib (jika newsletter aktif) |
| NEWS-4 | Kirim email berisi artikel terbaru (manual atau terjadwal) | Sebaiknya |
| NEWS-5 | Notifikasi email ke editor saat ada artikel menunggu review | Sebaiknya |
| NEWS-6 | Push notification browser | Opsional |

### 6.10 Statistik & Laporan

| Kode | Kebutuhan | Prioritas |
|---|---|---|
| STAT-1 | Jumlah pembaca per artikel | Wajib |
| STAT-2 | Artikel terpopuler (hari ini / minggu ini / bulan ini) | Sebaiknya |
| STAT-3 | Statistik per penulis (jumlah artikel & total pembaca) | Sebaiknya |
| STAT-4 | Integrasi Google Analytics 4 | Sebaiknya |
| STAT-5 | Grafik trafik di dashboard admin | Sebaiknya |
| STAT-6 | Export laporan ke Excel/CSV | Opsional |

### 6.11 Iklan & Monetisasi (Opsional)

| Kode | Kebutuhan | Prioritas |
|---|---|---|
| ADS-1 | Slot iklan yang bisa diatur (header, sidebar, tengah artikel, footer) | Opsional |
| ADS-2 | Dukungan Google AdSense | Opsional |
| ADS-3 | Banner mandiri dengan hitungan klik & tayangan | Opsional |
| ADS-4 | Penanda "Artikel Bersponsor" / advertorial | Opsional |

---

## 7. Teknologi yang Digunakan

| Bagian | Pilihan | Kenapa dipilih |
|---|---|---|
| Framework | **Laravel 12** | Matang, dokumentasi lengkap, komunitas besar |
| Bahasa | **PHP 8.3+** | Versi modern, performa jauh lebih baik |
| Database | **MySQL 8** atau **PostgreSQL** | Keduanya cocok; MySQL lebih umum di hosting Indonesia |
| Tampilan Frontend | **Blade + Tailwind CSS + Alpine.js** | Ringan, cepat, tanpa kerumitan SPA |
| Interaktivitas | **Livewire 3** | Fitur dinamis tanpa perlu menulis API terpisah |
| Admin Panel | **Filament 3** | Admin panel siap pakai — menghemat berminggu-minggu pengembangan |
| Hak Akses | **Spatie Laravel Permission** | Standar industri untuk role & permission |
| Slug Otomatis | **Spatie Laravel Sluggable** | Slug rapi & otomatis |
| Gambar | **Spatie Media Library** | Unggah, konversi, & beberapa ukuran otomatis |
| Riwayat Revisi | **Spatie Activity Log** | Mencatat perubahan & aktivitas |
| Editor Artikel | **TipTap** atau **CKEditor 5** | Editor modern; hindari editor lama yang menghasilkan HTML berantakan |
| Pencarian | **Laravel Scout + Meilisearch** | Cepat & toleran typo (kalau belum perlu, cukup query database biasa) |
| Cache | **Redis** | Mempercepat halaman yang sering dibuka |
| Antrian (Queue) | **Laravel Queue + Redis** | Untuk tugas berat: kirim email, proses gambar |
| Terjadwal | **Laravel Scheduler** | Untuk artikel terjadwal & laporan otomatis |
| Email | **Laravel Mail + Resend/Mailgun** | Pengiriman email andal |
| Penyimpanan File | **Local** (awal) → **S3/Cloudflare R2** (saat besar) | Skalabilitas penyimpanan gambar |
| Monitoring | **Laravel Telescope** (dev) + **Sentry** (produksi) | Deteksi error |
| Testing | **Pest / PHPUnit** | Memastikan fitur tidak rusak saat ada perubahan |
| Deployment | **Laravel Forge + VPS**, atau **Ploi** | Otomatis & mudah dikelola |
| Code Quality | **Laravel Pint + Larastan** | Format kode konsisten & deteksi bug dini |

> **Catatan penting soal Filament:** admin panel adalah bagian yang paling memakan waktu kalau dibuat dari nol. Filament menyediakan tabel, form, filter, dan upload yang sudah jadi. Kalau tujuannya cepat jalan dengan kualitas baik, ini pilihan paling efisien.

---

## 8. Struktur Database (Ringkasan)

```
users               — id, name, username, email, password, role, avatar, bio,
                      social_links (JSON), email_verified_at, created_at

posts               — id, user_id, category_id, title, slug, excerpt,
                      content, featured_image, status, published_at,
                      views_count, reading_time, is_featured, is_headline,
                      meta_title, meta_description, created_at, updated_at, deleted_at

categories          — id, parent_id, name, slug, description, image, sort_order

tags                — id, name, slug

post_tag            — post_id, tag_id            (tabel penghubung many-to-many)

comments            — id, post_id, user_id, parent_id, author_name, author_email,
                      content, status, ip_address, created_at

media               — id, model_type, model_id, file_name, mime_type, size,
                      alt_text, caption, credit, created_at

subscribers         — id, email, name, token, is_confirmed, confirmed_at,
                      unsubscribed_at, created_at

pages               — id, title, slug, content, status        (halaman statis)

settings            — id, key, value                          (pengaturan situs)

post_revisions      — id, post_id, user_id, title, content, created_at

redirects           — id, from_slug, to_slug, status_code     (jaga tautan lama)

activity_log        — id, causer_id, description, subject_type, subject_id,
                      properties (JSON), created_at
```

### Catatan penting tentang database

1. **`deleted_at` (soft delete)** — artikel yang dihapus tidak langsung hilang permanen, bisa dipulihkan. Penting untuk mencegah kehilangan konten karena salah klik.

2. **Indexing** — kolom `slug`, `status`, `published_at`, dan `category_id` harus diberi index. Tanpa ini, website akan melambat drastis setelah artikel mencapai ribuan.

3. **`views_count`** — jangan update langsung ke database setiap kali halaman dibuka (berat). Gunakan Redis untuk menampung dulu, lalu simpan ke database secara berkala lewat scheduler.

4. **Penyimpanan konten** — simpan sebagai HTML hasil editor, dan **wajib disanitasi** sebelum ditampilkan agar tidak bisa disisipi script berbahaya.

---

## 9. Alur Kerja Redaksi

```
Penulis membuat artikel (status: Draft)
        ↓
Penulis menyelesaikan → kirim untuk ditinjau (status: Menunggu Review)
        ↓
Editor menerima notifikasi email
        ↓
   ┌────┴────┐
   ↓         ↓
Disetujui   Ditolak/Revisi → kembali ke Penulis dengan catatan
   ↓
Editor menerbitkan sekarang (Terbit)
   atau menjadwalkan (terbit otomatis pada waktu yang ditentukan)
        ↓
Artikel tayang di website + masuk sitemap + terkirim ke newsletter
        ↓
Jika perlu koreksi → edit → tampilkan "Diperbarui pada [tanggal]"
```

**Aturan penting:**
- Artikel yang sudah terbit **tidak boleh diubah slug-nya** tanpa membuat redirect otomatis — kalau tidak, tautan yang sudah tersebar akan mati dan peringkat SEO hilang.
- Perubahan pada artikel yang sudah terbit sebaiknya tercatat di riwayat revisi, untuk transparansi (terutama di media berita).

---

## 10. Kebutuhan Non-Fungsional

### 10.1 Kecepatan
- Halaman artikel terbuka dalam **< 2 detik** pada koneksi 4G.
- Skor Lighthouse minimal **90** untuk Performance & SEO.
- **Cache halaman** untuk artikel yang sudah terbit (isinya jarang berubah).
- Cache query berat seperti "artikel populer" dan menu kategori.
- Gambar dimuat secara lazy loading (hanya saat akan terlihat).
- Gunakan CDN (Cloudflare) untuk gambar dan aset statis.

### 10.2 Keamanan
- Password di-hash (Laravel sudah melakukan ini otomatis dengan bcrypt).
- **Sanitasi HTML** dari editor untuk mencegah XSS — ini titik rawan utama di aplikasi blog.
- Proteksi CSRF pada semua form (bawaan Laravel).
- Rate limiting pada login, pendaftaran, komentar, dan pencarian.
- Validasi ketat pada unggahan file: batasi tipe (jpg, png, webp) dan ukuran maksimal.
- File yang diunggah disimpan di luar folder publik atau diberi nama acak, agar tidak bisa dieksekusi.
- Hanya pengguna dengan peran yang sesuai yang bisa mengakses halaman admin (middleware + policy).
- HTTPS wajib, plus security header (CSP, HSTS, X-Frame-Options).
- Backup database otomatis harian.

### 10.3 Aksesibilitas
- Kontras warna memenuhi standar WCAG AA.
- Semua gambar punya alt text.
- Struktur heading rapi (satu `h1` per halaman, lalu `h2`, `h3` berurutan).
- Navigasi bisa dilakukan dengan keyboard.
- Gunakan HTML semantik (`<article>`, `<nav>`, `<main>`, `<time>`).

### 10.4 Tampilan di Berbagai Perangkat
- Dirancang **mobile-first** — mayoritas pembaca berita di Indonesia menggunakan HP.
- Breakpoint: HP (<640px), tablet (640–1024px), desktop (>1024px).
- Menu berubah jadi hamburger di layar kecil.
- Target sentuh minimal 44×44px.

### 10.5 Keandalan
- Target uptime ≥ 99.5%.
- Error dicatat & dikirim notifikasi (Sentry).
- Backup otomatis database + file media.
- Antrian (queue) dipantau agar email dan proses gambar tidak macet.

---

## 11. Struktur Folder Laravel

```
/app
  /Models
    Post.php, Category.php, Tag.php, Comment.php, User.php, Media.php
  /Http
    /Controllers
      /Frontend                → HomeController, PostController, CategoryController
      /Auth
    /Middleware
    /Requests                  → Validasi form (StorePostRequest, dll)
  /Policies                    → Aturan siapa boleh apa (PostPolicy, CommentPolicy)
  /Services                    → Logika bisnis (PostService, SeoService, ImageService)
  /Filament
    /Resources                 → PostResource, CategoryResource, UserResource
    /Widgets                   → Widget statistik di dashboard
  /Jobs                        → Tugas latar: kirim newsletter, proses gambar
  /Observers                   → PostObserver (auto slug, hitung waktu baca)
  /View/Components             → Komponen Blade yang dipakai berulang

/resources
  /views
    /layouts                   → app.blade.php (kerangka halaman)
    /components                → post-card, pagination, share-buttons
    /frontend                  → home, post-show, category, search
    /emails                    → template email
  /css, /js

/routes
  web.php                      → rute publik
  admin.php                    → rute admin (jika tidak sepenuhnya pakai Filament)

/database
  /migrations                  → struktur tabel
  /seeders                     → data contoh untuk testing
  /factories

/tests
  /Feature                     → tes alur (terbitkan artikel, komentar, dll)
  /Unit
```

**Prinsip yang dipegang:**
- **Controller tetap tipis** — hanya menerima request dan memanggil Service.
- **Logika bisnis di Service** — supaya bisa diuji dan dipakai ulang.
- **Validasi di Form Request** — jangan menumpuk di controller.
- **Aturan izin di Policy** — terpusat, tidak tersebar di banyak tempat.

---

## 12. Ukuran Keberhasilan

| Yang diukur | Target awal |
|---|---|
| Waktu buka halaman artikel | < 2 detik |
| Skor Lighthouse (Performance & SEO) | ≥ 90 |
| Artikel terindeks Google | ≥ 90% dari total artikel terbit |
| Trafik organik dari Google | Tumbuh setiap bulan |
| Rata-rata waktu baca per artikel | > 1 menit |
| Bounce rate | < 70% |
| Waktu dari draft ke terbit | Lebih cepat dari proses sebelumnya |
| Error rate (5xx) | < 0.5% |

---

## 13. Tahapan Pengerjaan

### **Tahap 1 — Versi Dasar (± 3–4 minggu)**
Target: website sudah bisa dipakai untuk menerbitkan artikel.
- Setup Laravel, database, dan struktur dasar
- Sistem artikel lengkap (tulis, edit, terbit, jadwal)
- Kategori & tag
- Admin panel dengan Filament
- Hak akses Penulis/Editor/Admin
- Upload & optimasi gambar
- Halaman publik: beranda, daftar artikel, baca artikel, kategori
- Pencarian dasar
- SEO dasar (meta tag, sitemap, structured data)
- Deploy ke server

### **Tahap 2 — Pengembangan (± 2–3 minggu)**
Target: meningkatkan keterlibatan pembaca & kenyamanan redaksi.
- Sistem komentar + moderasi
- Newsletter & langganan email
- Profil penulis & halaman penulis
- Artikel terkait & artikel populer
- Riwayat revisi artikel
- Statistik di dashboard admin
- RSS feed & Google News sitemap
- Halaman statis (Tentang, Redaksi, Kebijakan Privasi)

### **Tahap 3 — Penyempurnaan (berkelanjutan)**
- Pencarian canggih (Meilisearch)
- Slot iklan & monetisasi
- Dark mode & daftar isi otomatis
- Push notification
- Liveblog untuk peliputan langsung
- Optimasi performa lanjutan (CDN, cache agresif)

> **Estimasi sampai Tahap 2: ± 5–7 minggu**, dengan asumsi 1 developer full-time dan admin panel menggunakan Filament. Tanpa Filament (membangun admin dari nol), tambahkan sekitar 2–3 minggu.

---

## 14. Risiko & Cara Mengatasinya

| Risiko | Dampak | Solusi |
|---|---|---|
| **Konten disisipi script berbahaya** lewat editor | Tinggi | Sanitasi HTML wajib (HTMLPurifier), batasi tag yang diizinkan |
| Website melambat saat artikel mencapai ribuan | Tinggi | Index database yang tepat, pagination, caching, hindari query N+1 |
| Gambar besar bikin halaman berat | Tinggi | Kompresi otomatis + konversi WebP + lazy loading + CDN |
| Slug diubah → tautan lama mati → peringkat SEO hilang | Tinggi | Buat redirect 301 otomatis setiap slug berubah |
| Spam komentar membanjiri | Sedang | Moderasi wajib + honeypot + rate limit + reCAPTCHA |
| View counter membebani database | Sedang | Tampung di Redis, simpan ke DB secara berkala |
| Email masuk folder spam | Sedang | Gunakan layanan email profesional + setup SPF, DKIM, DMARC |
| Kehilangan artikel karena salah hapus | Sedang | Soft delete + backup harian + riwayat revisi |
| Fitur terus bertambah, proyek tidak selesai | Sedang | Disiplin pada tahapan; fitur baru masuk daftar tunggu, bukan Tahap 1 |

---

## 15. Asumsi

- Website berbahasa Indonesia, satu bahasa saja.
- Konten berupa teks dan gambar; video di-embed dari YouTube, tidak di-hosting sendiri.
- Jumlah artikel awal relatif kecil dan bertumbuh bertahap (bukan migrasi puluhan ribu artikel sekaligus).
- Tim redaksi berukuran kecil-menengah (1–10 orang).
- Hosting menggunakan VPS atau shared hosting yang mendukung PHP 8.3 dan Composer.

---

## 16. Hal yang Perlu Diputuskan

1. **Jenis website**: blog personal, blog perusahaan, atau media berita? (Media berita butuh halaman Redaksi, Pedoman Media Siber, dan Google News sitemap.)
2. **Berapa banyak penulis** yang akan mengelola? (Menentukan seberapa rumit sistem hak akses yang dibutuhkan.)
3. **Komentar**: dibuat sendiri, pakai Disqus, atau tidak ada komentar sama sekali?
4. **Monetisasi**: apakah dari awal sudah berencana pasang iklan?
5. **Migrasi**: apakah ada artikel lama (dari WordPress/Blogger) yang perlu dipindahkan?
6. **Hosting**: sudah punya server/VPS, atau perlu rekomendasi?
7. **Desain**: sudah ada acuan tampilan/brand, atau perlu dibuat dari nol?
8. **Prioritas**: cepat tayang dengan fitur dasar, atau lengkap sejak awal?
