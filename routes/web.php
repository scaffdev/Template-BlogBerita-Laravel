<?php

use App\Http\Controllers\ApiController;
use App\Http\Controllers\Frontend\ArchiveController;
use App\Http\Controllers\Frontend\AuthController;
use App\Http\Controllers\Frontend\AuthorController;
use App\Http\Controllers\Frontend\BookmarkController;
use App\Http\Controllers\Frontend\CategoryController;
use App\Http\Controllers\Frontend\CommentController;
use App\Http\Controllers\Frontend\FeedController;
use App\Http\Controllers\Frontend\HomeController;
use App\Http\Controllers\Frontend\LikeController;
use App\Http\Controllers\Frontend\PageController;
use App\Http\Controllers\Frontend\PostController;
use App\Http\Controllers\Frontend\ProfileController;
use App\Http\Controllers\Frontend\SearchController;
use App\Http\Controllers\Frontend\SubscriberController;
use App\Http\Controllers\Frontend\TagController;
use Illuminate\Support\Facades\Route;

// Beranda
Route::get('/', [HomeController::class, 'index'])->name('home');

// Feed / SEO files (harus sebelum catch-all slug)
Route::get('/feed', [FeedController::class, 'feed'])->name('feed');
Route::get('/sitemap.xml', [FeedController::class, 'sitemap'])->name('sitemap');
Route::get('/news-sitemap.xml', [FeedController::class, 'newsSitemap'])->name('news-sitemap');
Route::get('/robots.txt', [FeedController::class, 'robots'])->name('robots');

// Artikel
Route::get('/artikel', [PostController::class, 'index'])->name('posts.index');
Route::get('/artikel/preview/{id}', [PostController::class, 'preview'])->middleware('auth')->whereNumber('id')->name('posts.preview');
Route::get('/artikel/{slug}', [PostController::class, 'show'])->name('posts.show');

// Kategori, Tag, Penulis, Cari, Arsip
Route::get('/kategori/{slug}', [CategoryController::class, 'show'])->name('categories.show');
Route::get('/tag/{slug}', [TagController::class, 'show'])->name('tags.show');
Route::get('/penulis/{username}', [AuthorController::class, 'show'])->name('authors.show');
Route::get('/cari', [SearchController::class, 'index'])->name('search');
Route::get('/arsip/{tahun}/{bulan}', [ArchiveController::class, 'show'])->where(['tahun' => '[0-9]{4}', 'bulan' => '[0-9]{1,2}'])->name('archive.show');

// Komentar & Newsletter
Route::post('/artikel/{slug}/komentar', [CommentController::class, 'store'])->middleware('throttle:10,1')->name('comments.store');
Route::post('/artikel/{slug}/suka', [LikeController::class, 'toggle'])->middleware('auth')->name('posts.like');
Route::post('/artikel/{slug}/simpan', [BookmarkController::class, 'toggle'])->middleware('auth')->name('posts.bookmark');
Route::post('/komentar/{id}/laporkan', [CommentController::class, 'report'])->middleware('throttle:10,1')->name('comments.report');

// Koleksi simpanan + profil (auth)
Route::middleware('auth')->group(function () {
    Route::get('/disimpan', [BookmarkController::class, 'index'])->name('bookmarks.index');
    Route::get('/profil', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profil', [ProfileController::class, 'update'])->name('profile.update');
});

// Autocomplete pencarian (CARI-4) + API publik ringan
Route::get('/cari/saran', [SearchController::class, 'suggest'])->middleware('throttle:60,1')->name('search.suggest');
Route::get('/api/posts', [ApiController::class, 'posts'])->middleware('throttle:60,1')->name('api.posts');
Route::get('/api/meta', [ApiController::class, 'meta'])->middleware('throttle:60,1')->name('api.meta');

// PWA: manifest + service worker
Route::get('/manifest.webmanifest', fn () => response()->file(public_path('manifest.webmanifest'), ['Content-Type' => 'application/manifest+json']))->name('pwa.manifest');
Route::get('/sw.js', fn () => response()->file(public_path('sw.js'), ['Content-Type' => 'application/javascript']))->name('pwa.sw');

Route::post('/newsletter/subscribe', [SubscriberController::class, 'store'])->middleware('throttle:5,1')->name('newsletter.subscribe');
Route::get('/newsletter/confirm/{token}', [SubscriberController::class, 'confirm'])->name('newsletter.confirm');
Route::get('/newsletter/unsubscribe/{token}', [SubscriberController::class, 'unsubscribe'])->name('newsletter.unsubscribe');

// Auth sederhana
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->middleware('throttle:10,1');
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->middleware('throttle:10,1');
    Route::get('/forgot-password', [AuthController::class, 'showForgot'])->name('password.request');
    Route::post('/forgot-password', [AuthController::class, 'forgot'])->middleware('throttle:5,1')->name('password.email');
    Route::get('/reset-password/{token}', [AuthController::class, 'showReset'])->name('password.reset');
    Route::post('/reset-password', [AuthController::class, 'reset'])->middleware('throttle:5,1')->name('password.update');
});
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');

// Halaman statis catch-all — PALING BAWAH agar tidak konflik
Route::get('/{slug}', [PageController::class, 'show'])
    ->where('slug', '^(?!artikel|kategori|tag|penulis|cari|arsip|feed|sitemap\.xml|news-sitemap\.xml|robots\.txt|login|register|forgot-password|logout|disimpan|profil|api|manifest\.webmanifest|sw\.js|admin|up)([a-z0-9\-]+)$')
    ->name('pages.show');

require __DIR__.'/admin.php';
