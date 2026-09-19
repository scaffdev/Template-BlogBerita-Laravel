<?php

namespace App\Services;

class ContentSanitizer
{
    protected string $allowed = '<p><a><b><strong><i><em><u><h2><h3><h4><blockquote><ul><ol><li><img><figure><figcaption><table><thead><tbody><tr><th><td><pre><code><hr><br>';

    public function clean(?string $html): string
    {
        if ($html === null || $html === '') {
            return '';
        }

        // Hapus <script>, <style>, <iframe> non-youtube dulu (simpan youtube embed)
        $youtubePlaceholders = [];
        $html = preg_replace_callback(
            '#<iframe\b[^>]*>.*?</iframe>#is',
            function ($m) use (&$youtubePlaceholders) {
                $tag = $m[0];
                if (preg_match('#src=["\']([^"\']+)["\']#i', $tag, $sm)) {
                    $src = $sm[1];
                    if (str_contains($src, 'youtube-nocookie') || str_contains($src, 'youtube.com/embed') || str_contains($src, 'youtu.be')) {
                        $key = '###YOUTUBE' . count($youtubePlaceholders) . '###';
                        // Bersihkan atribut iframe youtube: hanya src, width, height, allowfullscreen, frameborder, title
                        $youtubePlaceholders[$key] = '<iframe src="' . htmlspecialchars($src, ENT_QUOTES) . '" width="560" height="315" frameborder="0" allowfullscreen></iframe>';

                        return $key;
                    }
                }

                return '';
            },
            $html
        );

        // Hapus script/style/object/embed secara eksplisit
        $html = preg_replace('#<(script|style|object|embed|link|meta)[^>]*>.*?</\1\s*>#is', '', $html ?? '');
        $html = preg_replace('#<(script|style)[^>]*/?>#i', '', $html ?? '');

        // strip_tags dengan allowlist
        $html = strip_tags($html, $this->allowed);

        // Hapus event handler on*= dan javascript: URIs
        $html = preg_replace('#\s+on\w+\s*=\s*("[^"]*"|\'[^\']*\'|[^\s>]+)#i', '', $html);
        $html = preg_replace('#(href|src|xlink:href)\s*=\s*([\'"]?)\s*javascript:[^"\'>\s]*\2#i', '$1="#"', $html);
        $html = preg_replace('#(href|src)\s*=\s*([\'"]?)\s*data:text/html[^"\'>\s]*\2#i', '$1="#"', $html);

        // Kembalikan youtube embed
        foreach ($youtubePlaceholders as $key => $iframe) {
            $html = str_replace($key, $iframe, $html);
        }

        return trim($html);
    }
}
