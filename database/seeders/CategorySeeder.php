<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $order = 1;
        $make = function ($name, $parentId = null, $desc = '') use (&$order) {
            return Category::firstOrCreate(['slug' => Str::slug($name)], [
                'name' => $name,
                'parent_id' => $parentId,
                'description' => $desc ?: 'Berita '.$name.' terkini.',
                'sort_order' => $order++,
            ]);
        };
        $nasional = $make('Nasional');
        $make('Politik');
        $make('Ekonomi');
        $make('Teknologi');
        $olahraga = $make('Olahraga');
        $make('Sepak Bola', $olahraga->id);
        $make('Hiburan');
        $make('Gaya Hidup');
    }
}
