<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Str; 

class PostController extends Controller
{
    public function index(Request $request)
{
    $category = $request->query('category');
    $query = Post::query()->withCount('views'); 

    if ($category) {
        $query->where('category', $category);
    }

    $posts = $query->latest()->paginate(10);

    return view('posts.index', compact('posts', 'category'));
}

    public function create()
    {
        return view('posts.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'content' => 'required',
            'category' => 'required',
            'thumbnail' => 'image|file|max:2048',
        ]);

        $imagePath = null;
        if ($request->file('thumbnail')) {
            $imagePath = $request->file('thumbnail')->store('post-images', 'public');
        }

        Post::create([
            'title' => $request->title,
            'slug' => Str::slug($request->title) . '-' . time(),
            'content' => $request->content,
            'category' => $request->category,
            'author' => auth()->user()->name,
            'thumbnail' => $imagePath,
            'is_published' => true,
        ]); 

        return redirect()->route('posts.index')->with('success', 'Berita berhasil ditambahkan!');
    } 

    public function edit($id)
    {
        $post = Post::findOrFail($id);
        return view('posts.edit', compact('post'));
    }

    public function update(Request $request, $id)
    {
        $post = Post::findOrFail($id);

        $request->validate([
            'title' => 'required',
            'content' => 'required',
            'category' => 'required',
            'thumbnail' => 'image|file|max:2048', 
        ]);

        $data = [
            'title' => $request->title,
            'category' => $request->category,
            'content' => $request->content,
            'slug' => Str::slug($request->title) . '-' . time(),
        ];

        if ($request->hasFile('thumbnail')) {
            $data['thumbnail'] = $request->file('thumbnail')->store('post-images', 'public');
        }

        $post->update($data);

        return redirect()->route('posts.index')->with('success', 'Berita berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $post = Post::findOrFail($id);
        $post->delete();

        return redirect()->route('posts.index')->with('success', 'Berita berhasil dihapus!');
    }
}