<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Subscriber;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class SubscriberController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'email' => ['required', 'email', 'max:255'],
            'name' => ['nullable', 'string', 'max:100'],
        ]);

        $subscriber = Subscriber::firstOrNew(['email' => $validated['email']]);
        $subscriber->name = $validated['name'] ?? $subscriber->name;
        $subscriber->token = Str::random(40);
        $subscriber->is_confirmed = false;
        $subscriber->save();

        // Kirim log mail (ganti dengan Mail::send saat SMTP siap)
        Log::info('Newsletter subscribe — kirim email konfirmasi', [
            'email' => $subscriber->email,
            'confirm_url' => url('/newsletter/confirm/' . $subscriber->token),
        ]);

        return back()->with('success', 'Silakan cek email Anda untuk konfirmasi langganan.');
    }

    public function confirm(string $token)
    {
        $subscriber = Subscriber::where('token', $token)->firstOrFail();
        $subscriber->update([
            'is_confirmed' => true,
            'confirmed_at' => now(),
            'unsubscribed_at' => null,
        ]);

        return redirect('/')->with('success', 'Langganan newsletter Anda sudah aktif. Terima kasih!');
    }

    public function unsubscribe(string $token)
    {
        $subscriber = Subscriber::where('token', $token)->firstOrFail();
        $subscriber->update([
            'is_confirmed' => false,
            'unsubscribed_at' => now(),
        ]);

        return redirect('/')->with('success', 'Anda telah berhenti berlangganan newsletter.');
    }
}
