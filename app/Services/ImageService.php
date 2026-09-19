<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ImageService
{
    /**
     * Simpan original + thumbnail 300px + medium 800px pakai GD.
     * Return ['original'=>path,'thumbnail'=>..., 'medium'=>...] (path relatif storage public).
     */
    public function store(UploadedFile $file, string $dir = 'posts'): array
    {
        $disk = Storage::disk('public');
        $ext = strtolower($file->getClientOriginalExtension() ?: 'jpg');
        $basename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $name = Str::slug($basename) . '-' . Str::random(8);
        $datePath = trim($dir, '/') . '/' . date('Y/m');

        $originalPath = $datePath . '/' . $name . '.' . $ext;

        // Simpan original dulu
        $disk->putFileAs(dirname($originalPath), $file, basename($originalPath));

        $fullOriginal = $disk->path($originalPath);
        $thumbPath = $datePath . '/' . $name . '-thumb.' . $ext;
        $mediumPath = $datePath . '/' . $name . '-medium.' . $ext;

        try {
            $this->resizeWithGd($fullOriginal, $disk->path($thumbPath), 300);
            $this->resizeWithGd($fullOriginal, $disk->path($mediumPath), 800);

            // Konversi webp jika support
            if (function_exists('imagewebp')) {
                $webpOriginal = $datePath . '/' . $name . '.webp';
                if ($this->convertToWebp($fullOriginal, $disk->path($webpOriginal))) {
                    $originalPath = $webpOriginal;
                }
            }
        } catch (\Throwable $e) {
            // Fallback: copy saja
            try {
                $disk->copy($originalPath, $thumbPath);
                $disk->copy($originalPath, $mediumPath);
            } catch (\Throwable $ignored) {
                $thumbPath = $originalPath;
                $mediumPath = $originalPath;
            }
        }

        return [
            'original' => $originalPath,
            'thumbnail' => $thumbPath ?? $originalPath,
            'medium' => $mediumPath ?? $originalPath,
        ];
    }

    protected function resizeWithGd(string $src, string $dest, int $maxWidth): void
    {
        if (! function_exists('imagecreatefromstring')) {
            throw new \RuntimeException('GD tidak tersedia.');
        }

        @mkdir(dirname($dest), 0755, true);

        $data = @file_get_contents($src);
        if ($data === false) {
            throw new \RuntimeException('Gagal membaca file sumber.');
        }

        $img = @imagecreatefromstring($data);
        if ($img === false) {
            throw new \RuntimeException('Gagal decode gambar (GD).');
        }

        $w = imagesx($img);
        $h = imagesy($img);

        if ($w <= $maxWidth) {
            // Tidak perlu resize, copy saja
            copy($src, $dest);
            imagedestroy($img);

            return;
        }

        $newW = $maxWidth;
        $newH = (int) round($h * ($newW / $w));

        $out = imagecreatetruecolor($newW, $newH);
        // Pertahankan transparansi PNG/WebP
        imagealphablending($out, false);
        imagesavealpha($out, true);

        imagecopyresampled($out, $img, 0, 0, 0, 0, $newW, $newH, $w, $h);

        $ext = strtolower(pathinfo($dest, PATHINFO_EXTENSION));
        match ($ext) {
            'png' => imagepng($out, $dest, 6),
            'webp' => function_exists('imagewebp') ? imagewebp($out, $dest, 82) : imagejpeg($out, $dest, 82),
            'gif' => imagegif($out, $dest),
            default => imagejpeg($out, $dest, 82),
        };

        imagedestroy($img);
        imagedestroy($out);
    }

    protected function convertToWebp(string $src, string $dest): bool
    {
        $data = @file_get_contents($src);
        if ($data === false) {
            return false;
        }
        $img = @imagecreatefromstring($data);
        if ($img === false) {
            return false;
        }
        @mkdir(dirname($dest), 0755, true);
        $ok = @imagewebp($img, $dest, 82);
        imagedestroy($img);

        return (bool) $ok;
    }
}
