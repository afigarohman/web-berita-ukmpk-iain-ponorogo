<?php

namespace App\Http\Controllers;

use App\Models\Post; // Pastikan Model Post di-import
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        // Menghitung Data untuk ditampilkan di Kartu
        $totalPosts = Post::count();
        $publishedPosts = Post::where('is_published', true)->count();
        $draftPosts = Post::where('is_published', false)->count();

        // Mengambil 5 berita terbaru untuk tabel
        $recentPosts = Post::latest()->take(5)->get();

        return view('dashboard', compact('totalPosts', 'publishedPosts', 'draftPosts', 'recentPosts'));
    }
}