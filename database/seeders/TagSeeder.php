<?php

namespace Database\Seeders;

use App\Models\Tag;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class TagSeeder extends Seeder
{
    public function run(): void
    {
        foreach (['Pemilu', 'Ekonomi Digital', 'AI', 'Timnas Indonesia', 'Liga 1', 'Kuliner', 'Wisata', 'Kesehatan', 'Pendidikan', 'UMKM'] as $name) {
            Tag::firstOrCreate(['slug' => Str::slug($name)], ['name' => $name]);
        }
    }
}
