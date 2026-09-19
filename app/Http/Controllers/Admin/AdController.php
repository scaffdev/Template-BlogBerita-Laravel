<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;

class AdController extends Controller
{
    public const SLOTS = ['ad_header', 'ad_sidebar', 'ad_inarticle', 'ad_footer'];

    public function index()
    {
        $ads = [];
        foreach (self::SLOTS as $slot) {
            $ads[$slot] = Setting::get($slot, '');
            $ads[$slot . '_enabled'] = Setting::get($slot . '_enabled', '0');
        }

        return view('admin.ads.index', compact('ads'));
    }

    public function update(Request $request)
    {
        foreach (self::SLOTS as $slot) {
            Setting::set($slot, (string) $request->input($slot, ''));
            Setting::set($slot . '_enabled', $request->boolean($slot . '_enabled') ? '1' : '0');
        }

        return back()->with('success', 'Slot iklan diperbarui.');
    }
}
