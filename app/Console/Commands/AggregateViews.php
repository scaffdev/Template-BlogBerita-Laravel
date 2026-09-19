<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class AggregateViews extends Command
{
    protected $signature = 'app:aggregate-views';
    protected $description = 'Agregasi views (flush counter Redis ke database bila tersedia)';

    public function handle(): int
    {
        try {
            $redis = app('redis');
            $keys = $redis->keys('post:views:*');
            $flushed = 0;
            foreach ($keys as $key) {
                $id = (int) str_replace('post:views:', '', $key);
                $inc = (int) $redis->get($key);
                if ($id && $inc > 0) {
                    DB::table('posts')->where('id', $id)->increment('views_count', $inc);
                    $redis->del($key);
                    $flushed++;
                }
            }
            // Segarkan cache populer
            Cache::forget('popular_posts');
            $this->info("Flush {$flushed} counter views dari Redis.");
        } catch (\Throwable $e) {
            $this->info('Redis tidak tersedia, lewati agregasi.');
        }

        return self::SUCCESS;
    }
}
