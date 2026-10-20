<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    public function run(): void
    {
        $defaults = [
            'site_name' => 'BeritaKita',
            'site_tagline' => 'Cepat, Akurat, Terpercaya',
            'seo_default_title' => 'BeritaKita — Berita Terkini Indonesia',
            'seo_default_description' => 'BeritaKita menyajikan berita terkini nasional, politik, ekonomi, teknologi, dan olahraga.',
            'facebook' => 'https://facebook.com/beritakita',
            'twitter' => 'https://x.com/beritakita',
            'instagram' => 'https://instagram.com/beritakita',
            'youtube' => 'https://youtube.com/@beritakita',
            'site_email' => 'redaksi@beritakita.id',
            'site_phone' => '(021) 555-0100',
            'site_address' => 'Jl. Merdeka No. 45, Jakarta Pusat 10110',
            'ga4_id' => '',
            'comments_enabled' => '1',
            'comments_moderation' => '1',
            'ad_header' => '',
            'ad_header_enabled' => '0',
            'ad_sidebar' => '',
            'ad_sidebar_enabled' => '0',
            'ad_inarticle' => '',
            'ad_inarticle_enabled' => '0',
            'ad_footer' => '',
            'ad_footer_enabled' => '0',
        ];
        foreach ($defaults as $key => $value) {
            try {
                Setting::set($key, $value);
            } catch (\Throwable $e) {
                Setting::updateOrCreate(['key' => $key], ['value' => $value]);
            }
        }
    }
}
