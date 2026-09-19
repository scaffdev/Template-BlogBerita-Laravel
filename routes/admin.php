<?php

use App\Http\Controllers\Admin\ActivityLogController;
use App\Http\Controllers\Admin\AdController;
use App\Http\Controllers\Admin\AnalyticsController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\CommentController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\MediaController;
use App\Http\Controllers\Admin\PageController;
use App\Http\Controllers\Admin\PostController;
use App\Http\Controllers\Admin\RedirectController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\SubscriberController;
use App\Http\Controllers\Admin\TagController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Auth\AuthController;
use Illuminate\Support\Facades\Route;

Route::get('/admin/login', [AuthController::class, 'showLogin'])->middleware('guest')->name('admin.login');
Route::post('/admin/login', [AuthController::class, 'login'])->middleware(['guest', 'throttle:10,1'])->name('admin.login.post');

Route::prefix('admin')->name('admin.')->middleware(['auth', 'role:author,editor,admin'])->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('/posts', [PostController::class, 'index'])->name('posts.index');
    Route::get('/posts/create', [PostController::class, 'create'])->name('posts.create');
    Route::post('/posts', [PostController::class, 'store'])->name('posts.store');
    Route::post('/posts/autosave', [PostController::class, 'autosave'])->name('posts.autosave');
    Route::get('/posts/{post}/edit', [PostController::class, 'edit'])->name('posts.edit');
    Route::put('/posts/{post}', [PostController::class, 'update'])->name('posts.update');
    Route::delete('/posts/{post}', [PostController::class, 'destroy'])->name('posts.destroy');
    Route::post('/posts/{id}/restore', [PostController::class, 'restore'])->name('posts.restore');
    Route::delete('/posts/{id}/force', [PostController::class, 'forceDelete'])->name('posts.force');
    Route::post('/posts/{post}/publish', [PostController::class, 'publish'])->name('posts.publish');
    Route::post('/posts/{post}/schedule', [PostController::class, 'schedule'])->name('posts.schedule');
    Route::get('/posts/{post}/preview', [PostController::class, 'preview'])->name('posts.preview');

    Route::resource('/categories', CategoryController::class)->except(['show'])->middleware('role:editor,admin');
    Route::get('/tags', [TagController::class, 'index'])->name('tags.index')->middleware('role:editor,admin');
    Route::post('/tags', [TagController::class, 'store'])->name('tags.store')->middleware('role:editor,admin');
    Route::put('/tags/{tag}', [TagController::class, 'update'])->name('tags.update')->middleware('role:editor,admin');
    Route::delete('/tags/{tag}', [TagController::class, 'destroy'])->name('tags.destroy')->middleware('role:editor,admin');

    Route::get('/media', [MediaController::class, 'index'])->name('media.index');
    Route::post('/media', [MediaController::class, 'store'])->name('media.store');
    Route::put('/media/{medium}', [MediaController::class, 'update'])->name('media.update');
    Route::delete('/media/{medium}', [MediaController::class, 'destroy'])->name('media.destroy');

    Route::get('/comments', [CommentController::class, 'index'])->name('comments.index')->middleware('role:editor,admin');
    Route::post('/comments/{comment}/approve', [CommentController::class, 'approve'])->name('comments.approve')->middleware('role:editor,admin');
    Route::post('/comments/{comment}/reject', [CommentController::class, 'reject'])->name('comments.reject')->middleware('role:editor,admin');
    Route::post('/comments/{comment}/spam', [CommentController::class, 'spam'])->name('comments.spam')->middleware('role:editor,admin');
    Route::delete('/comments/{comment}', [CommentController::class, 'destroy'])->name('comments.destroy')->middleware('role:editor,admin');

    Route::resource('/users', UserController::class)->except(['show'])->middleware('role:admin');
    Route::resource('/pages', PageController::class)->except(['show'])->middleware('role:editor,admin');

    Route::get('/subscribers', [SubscriberController::class, 'index'])->name('subscribers.index')->middleware('role:editor,admin');
    Route::delete('/subscribers/{subscriber}', [SubscriberController::class, 'destroy'])->name('subscribers.destroy')->middleware('role:editor,admin');
    Route::get('/subscribers/broadcast', [SubscriberController::class, 'broadcastForm'])->name('subscribers.broadcast')->middleware('role:editor,admin');
    Route::post('/subscribers/broadcast', [SubscriberController::class, 'send'])->name('subscribers.send')->middleware('role:editor,admin');

    Route::get('/ads', [AdController::class, 'index'])->name('ads.index')->middleware('role:admin');
    Route::post('/ads', [AdController::class, 'update'])->name('ads.update')->middleware('role:admin');

    Route::get('/settings', [SettingController::class, 'edit'])->name('settings.edit')->middleware('role:admin');
    Route::post('/settings', [SettingController::class, 'update'])->name('settings.update')->middleware('role:admin');

    Route::get('/analytics', [AnalyticsController::class, 'index'])->name('analytics.index')->middleware('role:editor,admin');
    Route::get('/activity-log', [ActivityLogController::class, 'index'])->name('activity.index')->middleware('role:admin');

    Route::get('/redirects', [RedirectController::class, 'index'])->name('redirects.index')->middleware('role:editor,admin');
    Route::post('/redirects', [RedirectController::class, 'store'])->name('redirects.store')->middleware('role:editor,admin');
    Route::delete('/redirects/{redirect}', [RedirectController::class, 'destroy'])->name('redirects.destroy')->middleware('role:editor,admin');
});
