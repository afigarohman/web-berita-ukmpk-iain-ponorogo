<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Post;

class PublicPostController extends Controller
{
    // KHUSUS FRONTEND: Return JSON
    public function index(Request $request)
    {
        $category = $request->query('category');
        $query = Post::query();

        // Jika ada filter kategori, pakai. Jika tidak (Home), ambil semua.
        if ($category && $category !== 'Berita') { 
             // Catatan: Jika 'Berita' = Semua, maka abaikan filter. 
             // Tapi jika 'Berita' adalah kategori spesifik di DB, biarkan filter ini.
             $query->where('category', $category);
        }

        $posts = $query->latest()->paginate(10);

        return response()->json([
            'success' => true,
            'data'    => $posts
        ]);
    }

    public function show($slug)
    {
        $post = Post::where('slug', $slug)->first();
        if (!$post) return response()->json(['message' => 'Not Found'], 404);
        return response()->json(['success' => true, 'data' => $post]);
    }
}