<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Services\ImageService;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function edit()
    {
        return view('frontend.profile.edit', ['user' => auth()->user()]);
    }

    public function update(Request $request, ImageService $images)
    {
        $user = auth()->user();
        $data = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'username' => ['required', 'string', 'max:50', 'alpha_dash', 'unique:users,username,'.$user->id],
            'bio' => ['nullable', 'string', 'max:500'],
            'avatar' => ['nullable', 'image', 'max:1024'],
            'twitter' => ['nullable', 'string', 'max:255'],
            'instagram' => ['nullable', 'string', 'max:255'],
            'facebook' => ['nullable', 'string', 'max:255'],
            'youtube' => ['nullable', 'string', 'max:255'],
        ]);

        if ($request->hasFile('avatar')) {
            $images->delete($user->avatar);
            $user->avatar = $images->upload($request->file('avatar'), 'avatars');
        }

        $social = $user->social_links ?? [];
        foreach (['twitter', 'instagram', 'facebook', 'youtube'] as $k) {
            if (! empty($data[$k])) {
                $social[$k] = $data[$k];
            } else {
                unset($social[$k]);
            }
            unset($data[$k]);
        }
        unset($data['avatar']);
        $data['social_links'] = $social ?: null;

        $user->update($data);

        return back()->with('success', 'Profil berhasil diperbarui.');
    }
}
