# Blog Berita Laravel — Admin, Auth, Seeder, Test

## Cara Install
```bash
cp .env.example .env
composer install
php artisan key:generate
php artisan storage:link
php artisan migrate --seed
php artisan serve
```

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
