@extends('layouts.admin')
@section('title', 'Pengaturan')
@section('content')
<h1 class="text-xl font-bold mb-4">Pengaturan Situs</h1>
<form method="POST" action="{{ route('admin.settings.update') }}" enctype="multipart/form-data" class="bg-white rounded shadow p-4 max-w-3xl space-y-4 text-sm">
@csrf
<fieldset><legend class="font-bold mb-2">Umum</legend>
<label class="block mb-2">Nama situs<input name="site_name" value="{{ old('site_name', $settings['site_name']) }}" required class="mt-1 w-full border rounded px-3 py-2"></label>
<label class="block mb-2">Tagline<input name="site_tagline" value="{{ old('site_tagline', $settings['site_tagline']) }}" class="mt-1 w-full border rounded px-3 py-2"></label>
<div class="grid md:grid-cols-2 gap-2">
<label class="block">Logo<input type="file" name="logo" accept="image/*" class="mt-1 w-full"></label>
<label class="block">Favicon<input type="file" name="favicon" accept="image/*" class="mt-1 w-full"></label>
</div>
<label class="flex items-center gap-2 mt-2"><input type="checkbox" name="comments_enabled" value="1" @checked(($settings['comments_enabled'] ?? '1')==='1')> Komentar aktif global</label>
<label class="flex items-center gap-2"><input type="checkbox" name="comments_moderation" value="1" @checked(($settings['comments_moderation'] ?? '1')==='1')> Moderasi komentar (antrean sebelum tampil)</label>
</fieldset>
<fieldset><legend class="font-bold mb-2">SEO</legend>
<label class="block mb-2">Judul default<input name="seo_default_title" value="{{ old('seo_default_title', $settings['seo_default_title']) }}" class="mt-1 w-full border rounded px-3 py-2"></label>
<label class="block mb-2">Deskripsi default<textarea name="seo_default_description" rows="2" class="mt-1 w-full border rounded px-3 py-2">{{ old('seo_default_description', $settings['seo_default_description']) }}</textarea></label>
<label class="block">GA4 Measurement ID<input name="ga4_id" value="{{ old('ga4_id', $settings['ga4_id']) }}" placeholder="G-XXXXXXX" class="mt-1 w-full border rounded px-3 py-2 font-mono"></label>
</fieldset>
<fieldset><legend class="font-bold mb-2">Kontak Redaksi</legend>
<div class="grid md:grid-cols-2 gap-2">
<label class="block">Email redaksi<input type="email" name="site_email" value="{{ old('site_email', $settings['site_email']) }}" class="mt-1 w-full border rounded px-3 py-2" placeholder="redaksi@domain.id"></label>
<label class="block">Telepon / WhatsApp<input name="site_phone" value="{{ old('site_phone', $settings['site_phone']) }}" class="mt-1 w-full border rounded px-3 py-2" placeholder="(021) 555-0100"></label>
</div>
<label class="block mt-2">Alamat kantor<input name="site_address" value="{{ old('site_address', $settings['site_address']) }}" class="mt-1 w-full border rounded px-3 py-2" placeholder="Jl. Contoh No. 1, Jakarta"></label>
</fieldset>
<fieldset><legend class="font-bold mb-2">Media Sosial</legend>
<div class="grid md:grid-cols-2 gap-2">
<label class="block">Facebook<input name="facebook" value="{{ old('facebook', $settings['facebook']) }}" class="mt-1 w-full border rounded px-3 py-2"></label>
<label class="block">Twitter/X<input name="twitter" value="{{ old('twitter', $settings['twitter']) }}" class="mt-1 w-full border rounded px-3 py-2"></label>
<label class="block">Instagram<input name="instagram" value="{{ old('instagram', $settings['instagram']) }}" class="mt-1 w-full border rounded px-3 py-2"></label>
<label class="block">YouTube<input name="youtube" value="{{ old('youtube', $settings['youtube']) }}" class="mt-1 w-full border rounded px-3 py-2"></label>
</div>
</fieldset>
<p class="text-xs text-gray-500">Slot iklan dikelola di menu <a class="text-red-700" href="{{ route('admin.ads.index') }}">Iklan</a>.</p>
<button class="bg-red-600 text-white px-4 py-2 rounded">Simpan Pengaturan</button>
</form>
@endsection
