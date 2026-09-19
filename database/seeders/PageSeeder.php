<?php

namespace Database\Seeders;

use App\Models\Page;
use Illuminate\Database\Seeder;

class PageSeeder extends Seeder
{
    public function run(): void
    {
        $pages = [
            'tentang' => ['Tentang Kami', '<p>BeritaKita adalah media berita Indonesia yang menyajikan informasi cepat, akurat, dan terpercaya.</p>'],
            'kontak' => ['Kontak', '<p>Hubungi redaksi: redaksi@beritakita.id</p>'],
            'redaksi' => ['Redaksi', '<p>Susunan redaksi BeritaKita: Pemimpin Redaksi, Editor, dan Reporter.</p>'],
            'kebijakan-privasi' => ['Kebijakan Privasi', '<p>Kami menghormati privasi pembaca dan melindungi data pribadi sesuai peraturan yang berlaku.</p>'],
            'pedoman-media-siber' => ['Pedoman Media Siber', '<p>Kami berpedoman pada Pedoman Media Siber Dewan Pers dalam setiap pemberitaan.</p>'],
        ];
        foreach ($pages as $slug => [$title, $content]) {
            Page::firstOrCreate(['slug' => $slug], ['title' => $title, 'content' => $content, 'status' => 'published']);
        }
    }
}
