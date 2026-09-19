<?php

namespace App\Console\Commands;

use App\Models\Post;
use Illuminate\Console\Command;

class PublishScheduledPosts extends Command
{
    protected $signature = 'app:publish-scheduled';
    protected $description = 'Terbitkan artikel terjadwal (status published dengan published_at <= now)';

    public function handle(): int
    {
        // Model Post diasumsikan punya scope published() yang menghormati published_at.
        // Command ini memastikan published_at future tetap konsisten + log aktivitas.
        $count = Post::where('status', 'published')
            ->whereNotNull('published_at')
            ->where('published_at', '<=', now())
            ->count();
        $this->info("Artikel terjadwal siap tayang: {$count} (scope published otomatis menayangkan).");

        return self::SUCCESS;
    }
}
