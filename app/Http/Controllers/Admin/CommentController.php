<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function index(Request $request)
    {
        $q = Comment::with(['post:id,title,slug', 'user:id,name'])->latest();
        if ($request->filled('status')) {
            $q->where('status', $request->string('status'));
        }
        if ($request->filled('q')) {
            $q->where('content', 'like', '%' . $request->string('q') . '%');
        }
        $comments = $q->paginate(20)->withQueryString();
        $pendingCount = Comment::where('status', 'pending')->count();

        return view('admin.comments.index', compact('comments', 'pendingCount'));
    }

    public function approve(Comment $comment)
    {
        $comment->update(['status' => 'approved']);

        return back()->with('success', 'Komentar disetujui.');
    }

    public function reject(Comment $comment)
    {
        $comment->update(['status' => 'rejected']);

        return back()->with('success', 'Komentar ditolak.');
    }

    public function spam(Comment $comment)
    {
        $comment->update(['status' => 'spam']);

        return back()->with('success', 'Komentar ditandai spam.');
    }

    public function destroy(Comment $comment)
    {
        $comment->delete();

        return back()->with('success', 'Komentar dihapus.');
    }
}
