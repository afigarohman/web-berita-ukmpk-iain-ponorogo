<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\PostView;
use Illuminate\Http\Request;

class PostController extends Controller
{
    // 1. API UNTUK MENGAMBIL DATA DETAIL BERITA (DIPANGGIL SAAT USER KLIK JUDUL)
    public function show($slug, Request $request)
    {
        // Cari berita berdasarkan SLUG
        $post = Post::where('slug', $slug)->firstOrFail();

        // --- LOGIKA UNIK VIEW ---
        // Ambil IP Address pengunjung
        $ipAddress = $request->ip();
        $userAgent = $request->header('User-Agent'); // Info browser/device

        // Cek di database: Apakah IP ini SUDAH pernah lihat berita ini?
        $hasViewed = PostView::where('post_id', $post->id)
                             ->where('ip_address', $ipAddress)
                             ->exists();

        // Jika BELUM pernah lihat, maka catat!
        if (!$hasViewed) {
            PostView::create([
                'post_id'    => $post->id,
                'ip_address' => $ipAddress,
                'user_agent' => $userAgent,
                // 'user_id' => auth()->id() // Nyalakan ini jika nanti ada fitur login user
            ]);
        }

        // --- KEMBALIKAN DATA KE FRONTEND ---
        return response()->json([
            'success' => true,
            'data'    => $post,
            'views'   => $post->views()->count() // Kirim jumlah view terbaru
        ]);
    }
    
    // 2. API UNTUK LIST BERITA (UNTUK HOMEPAGE)
    public function index()
    {
        $posts = Post::where('is_published', true)
                     ->withCount('views') // Sertakan jumlah view
                     ->latest()
                     ->paginate(9); // Ambil 9 berita per halaman
                     
        return response()->json([
            'success' => true,
            'data'    => $posts
        ]);
    }
}