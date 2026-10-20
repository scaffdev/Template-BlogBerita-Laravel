<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class BackupDatabase extends Command
{
    protected $signature = 'app:backup-db {--keep=7 : jumlah backup terbaru yang disimpan}';

    protected $description = 'Backup database sqlite/mysql ke storage/app/backups (PRD 10.2 & 10.5)';

    public function handle(): int
    {
        $dir = storage_path('app/backups');
        @mkdir($dir, 0755, true);

        $conn = config('database.default');
        $file = $dir.'/backup-'.date('Ymd-His').'.sql';

        try {
            if ($conn === 'sqlite') {
                $src = config('database.connections.sqlite.database');
                $dest = $dir.'/backup-'.date('Ymd-His').'.sqlite';
                if (is_file($src)) {
                    copy($src, $dest);
                    $this->info("Backup sqlite tersimpan: {$dest}");
                } else {
                    $this->warn('File sqlite tidak ditemukan.');
                }
            } else {
                $this->call('schema:dump', ['--database' => $conn, '--path' => str_replace(storage_path(), '', $file)]);
                $this->info("Schema dump tersimpan: {$file}");
            }

            // Rotasi: simpan N terbaru
            $keep = max(1, (int) $this->option('keep'));
            $files = collect(glob($dir.'/backup-*'))->sort()->values();
            while ($files->count() > $keep) {
                @unlink($files->shift());
            }
        } catch (\Throwable $e) {
            $this->error('Backup gagal: '.$e->getMessage());

            return 1;
        }

        return 0;
    }
}
