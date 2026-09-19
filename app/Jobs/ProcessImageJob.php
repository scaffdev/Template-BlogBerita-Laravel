<?php

namespace App\Jobs;

use App\Models\Media;
use App\Services\ImageService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ProcessImageJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(public int $mediaId)
    {
    }

    public function handle(ImageService $images): void
    {
        $media = Media::find($this->mediaId);
        if (! $media) {
            return;
        }
        // Placeholder agregasi: pastikan file ada; resize berat sudah di ImageService saat upload.
        // Job ini menjadi titik ekstensi (WebP/thumbnail) tanpa memblokir request.
    }
}
