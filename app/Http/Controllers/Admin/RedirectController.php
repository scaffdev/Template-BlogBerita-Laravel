<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Redirect;
use Illuminate\Http\Request;

class RedirectController extends Controller
{
    public function index()
    {
        $redirects = Redirect::latest()->paginate(50);

        return view('admin.redirects.index', compact('redirects'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'from_slug' => ['required', 'string', 'max:255', 'unique:redirects,from_slug'],
            'to_slug' => ['required', 'string', 'max:255'],
            'status_code' => ['nullable', 'in:301,302'],
        ]);
        $data['from_slug'] = ltrim($data['from_slug'], '/');
        $data['to_slug'] = ltrim($data['to_slug'], '/');
        $data['status_code'] ??= 301;
        Redirect::create($data);

        return back()->with('success', 'Redirect dibuat.');
    }

    public function destroy(Redirect $redirect)
    {
        $redirect->delete();

        return back()->with('success', 'Redirect dihapus.');
    }
}
