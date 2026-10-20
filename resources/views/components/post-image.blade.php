@props(['post', 'class' => '', 'sizes' => '(min-width: 1024px) 400px, 100vw', 'eager' => false, 'alt' => null])
@php
    $path = $post->featured_image ?? null;
    $isExternal = $path && str_starts_with($path, 'http');
    $url = $path ? ($isExternal ? $path : asset('storage/' . ltrim($path, '/'))) : null;

    // Srcset hanya untuk gambar lokal yang punya turunan thumb/medium hasil ImageService.
    $srcset = null;
    if ($path && ! $isExternal) {
        $ext = pathinfo($path, PATHINFO_EXTENSION);
        $base = preg_replace('/\.(jpe?g|png|gif|webp)$/i', '', $path);
        $candidates = [];
        foreach (['-thumb' => 300, '-medium' => 800] as $suffix => $width) {
            $candidate = $base . $suffix . '.' . $ext;
            try {
                if (\Illuminate\Support\Facades\Storage::disk('public')->exists($candidate)) {
                    $candidates[] = asset('storage/' . ltrim($candidate, '/')) . ' ' . $width . 'w';
                }
            } catch (\Throwable $e) {
            }
        }
        if ($candidates) {
            $srcset = implode(', ', $candidates) . ', ' . $url . ' 1600w';
        }
    }
@endphp
@if($url)
    <img
        src="{{ $url }}"
        @if($srcset) srcset="{{ $srcset }}" sizes="{{ $sizes }}" @endif
        alt="{{ $alt ?? $post->title }}"
        @if($eager) fetchpriority="high" @else loading="lazy" decoding="async" @endif
        class="{{ $class }}"
    >
@endif
