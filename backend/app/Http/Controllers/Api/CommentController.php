<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class CommentController extends Controller
{
    // 1. AMBIL DAFTAR KOMENTAR (Untuk ditampilkan di bawah berita)
    public function index($postId)
    {
        $comments = Comment::with('user')
            ->where('post_id', $postId)
            ->where('is_approved', true) 
            ->latest()
            ->get();

        return response()->json([
            'success' => true,
            'data' => $comments
        ]);
    }

    // 2. SIMPAN KOMENTAR BARU (Dari form frontend)
    public function store(Request $request, $postId)
    {
        // Validasi input
        $validator = Validator::make($request->all(), [
            'body' => 'required|string|max:1000',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // Simpan ke Database
        $comment = Comment::create([
            'post_id' => $postId,
            'user_id' => Auth::id(), 
            'body' => $request->body,
            'is_approved' => false 
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Komentar berhasil dikirim, menunggu moderasi.',
            'data' => $comment
        ], 201);
    }
}