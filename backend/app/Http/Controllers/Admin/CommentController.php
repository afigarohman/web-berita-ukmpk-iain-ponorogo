<?php

namespace App\Http\Controllers\Admin; 

use App\Http\Controllers\Controller;
use App\Models\Comment;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    // 1. TAMPILKAN SEMUA KOMENTAR
    public function index()
    {
        $comments = Comment::with(['user', 'post']) 
            ->orderBy('is_approved', 'asc') 
            ->latest()
            ->paginate(10);

        return view('admin.comments.index', compact('comments'));
    }

    // 2. SETUJUI KOMENTAR (APPROVE)
    public function approve($id)
    {
        $comment = Comment::findOrFail($id);
        $comment->update(['is_approved' => true]);

        return redirect()->back()->with('success', 'Komentar berhasil disetujui!');
    }

    // 3. HAPUS KOMENTAR
    public function destroy($id)
    {
        $comment = Comment::findOrFail($id);
        $comment->delete();

        return redirect()->back()->with('success', 'Komentar dihapus permanen.');
    }
}