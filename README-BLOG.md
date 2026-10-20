# Blog Berita Laravel — Edisi Maksimal

## Yang baru di edisi maksimal
- **Baca (BACA-6/7/8/9):** progress bar, daftar isi otomatis, **dark mode** (tombol 🌙 / tombol `D`, tersimpan), **ukuran teks A-/A+** tersimpan, tombol **cetak**, riwayat "Terakhir dibaca" (localStorage).
- **Interaksi:** **Suka** (`POST /artikel/{slug}/suka`) + **Simpan/Bookmark** (`POST /artikel/{slug}/simpan`, halaman `/disimpan`), counter `likes_count`/`bookmarks_count`.
- **Komentar (KOM-3/5):** balas 1 level langsung di halaman artikel, lapor menyimpan `report_count` + alasan.
- **Pencarian (CARI-2/3/4/5):** filter kanal + **penulis + rentang tanggal**, sort relevan/terbaru/populer, **autocomplete** `GET /cari/saran?q=`, pencarian toleran typo per-kata (`searchSmart`, cocok di judul/ringkasan/isi/tag).
- **Artikel (ART-13/14/15 + ADS-4):** kolom `series_name`/`series_order` + box "Seri" otomatis, `co_authors` (maks 3, tampil "Ditulis bersama"), `is_liveblog` + tabel `live_updates` (kelola di form admin, timeline di halaman baca), `is_sponsored` + label advertorial.
- **SEO (SEO-10):** indikator **skor SEO 0–100** live di form admin (judul, meta, slug, panjang konten).
- **Admin:** export **CSV artikel & penulis** (`/admin/analytics/export-*`), kelola liveblog di form artikel, log broadcast newsletter (`newsletter_logs`).
- **Profil:** `/profil` (nama, username, bio, avatar, sosmed) — tampil di halaman penulis.
- **API publik:** `GET /api/posts?q=&kategori=&tag=&per_page=`, `GET /api/meta` (throttle 60/menit).
- **PWA:** `/manifest.webmanifest` + `/sw.js` (cache aset statis, auto-register).
- **Keamanan & performa (10.1/10.2/10.5):** middleware `SecurityHeaders` (CSP, X-Frame, Referrer, Permissions), cache menu/settings/tags/ticker + sitemap/RSS 10 menit, view counter buffer cache, backup terjadwal `app:backup-db` tiap 02:00 + `storage/app/backups`.
- **Fix penting:** `ImageService::upload()/delete()` ditambahkan (controller sebelumnya memanggil method yang belum ada), validasi `tags` koma diperbaiki, `allow_comments` boolean dinormalisasi.

## Cara Install
```bash
cp .env.example .env
composer install
npm install
php artisan key:generate
php artisan storage:link
php artisan migrate --seed
npm run build   # WAJIB: CSS/JS kini via Vite (tanpa CDN Tailwind/Alpine)
php artisan serve
```
Saat pengembangan frontend gunakan `npm run dev` (hot reload) di terminal terpisah.

## Edisi Desain Template (UI/UX v2)
- **Identitas "editorial majalah premium"**: tipografi serif **Fraunces** untuk headline + **Source Serif 4** untuk isi artikel, palet kertas hangat (ivory/ink), sudut nyaris siku, garis ganda (double rule) antar seksi, label seksi kapital-tracking — konsisten di seluruh halaman termasuk admin, light & dark mode.
- **Design system Tailwind 4** (`resources/css/app.css`): token warna brand (merah berita) + `ink`/`paper`, komponen `.btn/.chip/.kicker/.input/.ad-slot/.section-flag`, dark mode class-based dengan override otomatis untuk admin juga.
- **Vanilla JS tanpa dependensi** (`resources/js/app.js`): dark mode tersimpan, A-/A+, progress baca, TOC, ticker pause, tabs, share native + salin tautan, autocomplete `/cari/saran`, shortcut `Ctrl/⌘+K` & `/`, mode fokus, **dengar artikel (TTS)**, riwayat baca lokal, simpan/suka via AJAX + toast, dialog lapor, bottom-nav mobile.
- **Layout baru**: masthead + navbar sticky + ticker + bottom nav, footer berisi kontak redaksi & sosial dari Pengaturan (email/telepon/alamat kini tersedia di `/admin/settings`).
- **Halaman artikel**: kotak “Yang perlu diketahui” (kolom baru `summary_points`, maks 4 poin, satu per baris di form admin) + kredit foto (`featured_image_credit`).
- **Auth**: login publik & `/admin/login` dipisah; alur **lupa/reset kata sandi berfungsi penuh** (`/forgot-password`, `/reset-password/{token}`); dukungan `?redirect=` setelah login.
- **PWA**: ikon 192/512/maskable + `og-default.jpg` di `public/images`, manifest dengan shortcuts.
- **Perbaikan admin**: method `index()` yang hilang pada Category/Tag/Media/User/Page/Subscriber/Redirect/ActivityLog kini lengkap — semua halaman admin berfungsi.
- **CSP** diperbarui: CDN Tailwind/Alpine dihapus, hanya Google Fonts & GA yang diizinkan.
- **Smoke test**: `tests/Feature/SmokeAdminPagesTest.php` menguji render 15 halaman admin + halaman frontend utama.

## Akun Default (password: `password123`)
| Email | Role |
|---|---|
| admin@berita.local | admin |
| editor@berita.local | editor |
| author@berita.local | author |
| reader@berita.local | reader |

Login admin: `/admin/login` · Login publik: `/login`

## Fitur Admin
- Dashboard: kartu terbit/draft/pending/views, tabel antrean review, populer, chart 14 hari (canvas), top penulis.
- Artikel: filter status/q/kategori, paginate 15, CRUD, ownership (author hanya miliknya), publish & schedule (editor+), soft delete/restore, force delete (admin), autosave JSON 30 detik, preview, tag koma (auto-create), redirect otomatis saat slug berubah, notifikasi review via email.
- Kategori (bertingkat), Tag, Media (multi-upload + alt/caption/credit), Komentar (approve/reject/spam + badge pending), Pengguna (admin only, anti hapus diri), Halaman statis, Newsletter (broadcast + queue `SendNewsletterJob`), Iklan (4 slot HTML/AdSense + toggle), Pengaturan (umum/SEO/sosmed/GA4/moderasi), Analitik (hari/minggu/bulan, per penulis, 30 hari), Log aktivitas, Redirect manual.
- Hak akses via middleware `role`: author (`author,editor,admin`), kelola kategori/komentar (`editor,admin`), users/settings/activity (`admin`).

## Struktur Penting
- `routes/web.php` (frontend, milik agen frontend) + `require admin.php` di akhir
- `routes/admin.php` — semua rute admin prefix `admin`, name `admin.`
- `app/Http/Controllers/Auth/AuthController.php`, `app/Http/Controllers/Admin/*`
- `app/Services/ImageService.php` — upload + resize GD best-effort
- `app/Mail/*`, `app/Jobs/*`, `app/Console/Commands/*` (`app:publish-scheduled` tiap menit, `app:aggregate-views` tiap 5 menit)
- `database/seeders/*` — 8 kategori, 10 tag, 25 post, 5 halaman, komentar
- `tests/Feature/{BlogTest,AdminPostTest,CommentTest}.php` — jalan dengan `RefreshDatabase`

## Scheduler & Queue
```bash
php artisan schedule:run
php artisan queue:work
```
`QUEUE_CONNECTION=sync` = broadcast langsung terkirim (cocok dev).

## Deploy Notes
- Set `APP_URL`, `MAIL_*` (gunakan Resend/Mailgun + SPF/DKIM/DMARC), `QUEUE_CONNECTION=redis` + supervisor untuk queue.
- Jalankan `migrate --force`, `storage:link`, `config:cache`, `route:cache`; pasang cron `schedule:run` tiap menit.
- Gambar: disk `public` → pindah ke S3/R2 saat trafik besar; aktifkan Cloudflare + lazy loading.
