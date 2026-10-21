# Instruksi Verifikasi Template

File ini berisi prompt untuk AI agent yang menjalankan verifikasi setelah fix audit keamanan.

## Prasyarat
- PHP 8.2+, Composer, Node 18+ sudah terinstal.
- Repo sudah di-clone dan berada di root directory.

## Langkah Verifikasi

Jalankan perintah berikut secara berurutan dan laporkan hasilnya:

### 1. Install dependencies
```bash
composer install --no-interaction
npm ci
```

### 2. Setup environment
```bash
cp .env.example .env
php artisan key:generate
touch database/database.sqlite
php artisan migrate --seed
```

### 3. Build assets
```bash
npm run build
```

### 4. Jalankan test
```bash
php artisan test
```

### 5. Security audit
```bash
composer audit
npm audit --omit=dev
```

### 6. Lint check
```bash
npm run lint 2>/dev/null || echo "No lint script"
```

### 7. Smoke test dev server
```bash
php artisan serve &
sleep 3
curl -s -o /dev/null -w "%{http_code}" http://127.0.0.1:8000
curl -s -o /dev/null -w "%{http_code}" http://127.0.0.1:8000/artikel
curl -s -o /dev/null -w "%{http_code}" http://127.0.0.1:8000/admin/login
kill %1
```

## Yang Perlu Dilaporkan
- Apakah semua perintah berhasil tanpa error?
- Hasil `composer audit` dan `npm audit`: ada advisory tersisa?
- Hasil `php artisan test`: ada test yang gagal?
- HTTP status code dari smoke test (harus 200).
- Screenshot atau output error jika ada yang gagal.
