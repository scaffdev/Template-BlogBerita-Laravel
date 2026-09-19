<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $users = [
            ['name' => 'Administrator', 'username' => 'admin', 'email' => 'admin@berita.local', 'role' => 'admin'],
            ['name' => 'Editor Utama', 'username' => 'editor', 'email' => 'editor@berita.local', 'role' => 'editor'],
            ['name' => 'Penulis Satu', 'username' => 'penulis', 'email' => 'author@berita.local', 'role' => 'author'],
            ['name' => 'Pembaca Setia', 'username' => 'pembaca', 'email' => 'reader@berita.local', 'role' => 'reader'],
        ];
        foreach ($users as $u) {
            User::firstOrCreate(['email' => $u['email']], [
                'name' => $u['name'],
                'username' => $u['username'],
                'password' => Hash::make('password123'),
                'role' => $u['role'],
                'bio' => 'Akun '.$u['role'].' BeritaKita.',
                'email_verified_at' => now(),
            ]);
        }
    }
}
