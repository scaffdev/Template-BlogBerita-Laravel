<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use App\Services\ImageService;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function edit()
    {
        $keys = ['site_name', 'site_tagline', 'seo_default_title', 'seo_default_description', 'facebook', 'twitter', 'instagram', 'youtube', 'ga4_id', 'logo', 'favicon', 'site_email', 'site_phone', 'site_address', 'comments_moderation', 'comments_enabled'];
        $settings = [];
        foreach ($keys as $k) {
            $settings[$k] = Setting::get($k, '');
        }

        return view('admin.settings.edit', compact('settings'));
    }

    public function update(Request $request, ImageService $images)
    {
        $data = $request->validate([
            'site_name' => ['required', 'string', 'max:100'],
            'site_tagline' => ['nullable', 'string', 'max:255'],
            'seo_default_title' => ['nullable', 'string', 'max:70'],
            'seo_default_description' => ['nullable', 'string', 'max:160'],
            'facebook' => ['nullable', 'url'],
            'twitter' => ['nullable', 'url'],
            'instagram' => ['nullable', 'url'],
            'youtube' => ['nullable', 'url'],
            'ga4_id' => ['nullable', 'string', 'max:30'],
            'site_email' => ['nullable', 'email', 'max:150'],
            'site_phone' => ['nullable', 'string', 'max:50'],
            'site_address' => ['nullable', 'string', 'max:255'],
            'logo' => ['nullable', 'image', 'max:2048'],
            'favicon' => ['nullable', 'image', 'max:1024'],
            'comments_moderation' => ['nullable', 'boolean'],
            'comments_enabled' => ['nullable', 'boolean'],
        ]);
        foreach (['site_name', 'site_tagline', 'seo_default_title', 'seo_default_description',
            'facebook', 'twitter', 'instagram', 'youtube', 'ga4_id',
            'site_email', 'site_phone', 'site_address'] as $k) {
            Setting::set($k, (string) ($data[$k] ?? ''));
        }
        Setting::set('comments_moderation', $request->boolean('comments_moderation') ? '1' : '0');
        Setting::set('comments_enabled', $request->boolean('comments_enabled') ? '1' : '0');

        if ($request->hasFile('logo')) {
            $old = Setting::get('logo');
            $images->delete($old);
            Setting::set('logo', $images->upload($request->file('logo'), 'settings'));
        }
        if ($request->hasFile('favicon')) {
            $old = Setting::get('favicon');
            $images->delete($old);
            Setting::set('favicon', $images->upload($request->file('favicon'), 'settings'));
        }

        return back()->with('success', 'Pengaturan disimpan.');
    }
}
