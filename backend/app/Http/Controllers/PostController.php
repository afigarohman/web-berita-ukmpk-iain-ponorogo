<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage; 

class PostController extends Controller
{
    public function index(Request $request)
    {
        $category = $request->query('category');
        $query = Post::query(); 
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
            'title'     => 'required',
            'content'   => 'required',
            'category'  => 'required',
            'thumbnail' => 'nullable|image|file|max:2048', 
            'image_url' => 'nullable|url', 
        ]);

        $imagePath = null;

        if ($request->hasFile('thumbnail')) {
            $imagePath = $request->file('thumbnail')->store('post-images', 'public');
        } 
        elseif ($request->filled('image_url')) {
            try {
                $contents = file_get_contents($request->image_url);
                $filename = 'post-images/' . time() . '_downloaded.jpg';
                Storage::disk('public')->put($filename, $contents);
                $imagePath = $filename;
            } catch (\Exception $e) {
                return back()->withInput()->withErrors(['image_url' => 'Gagal mengunduh gambar. Pastikan link benar atau coba upload manual.']);
            }
        }

        Post::create([
            'title'        => $request->title,
            'slug'         => Str::slug($request->title) . '-' . time(),
            'content'      => $request->content,
            'category'     => $request->category,
            'author'       => auth()->user()->name, 
            'thumbnail'    => $imagePath,
            'is_published' => true,
            'is_popular'   => $request->has('is_popular'),
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
            'title'     => 'required',
            'content'   => 'required',
            'category'  => 'required',
            'thumbnail' => 'nullable|image|file|max:2048',
            'image_url' => 'nullable|url',
        ]);

        $data = [
            'title'    => $request->title,
            'category' => $request->category,
            'content'  => $request->content,
            'slug'     => Str::slug($request->title) . '-' . time(),
        ];

        if ($request->hasFile('thumbnail')) {
            if ($post->thumbnail) {
                Storage::disk('public')->delete($post->thumbnail);
            }
            $data['thumbnail'] = $request->file('thumbnail')->store('post-images', 'public');
        }
        elseif ($request->filled('image_url')) {
            try {
                if ($post->thumbnail) {
                    Storage::disk('public')->delete($post->thumbnail);
                }
                
                $contents = file_get_contents($request->image_url);
                $filename = 'post-images/' . time() . '_downloaded.jpg';
                Storage::disk('public')->put($filename, $contents);
                
                $data['thumbnail'] = $filename;
            } catch (\Exception $e) {
                return back()->withInput()->withErrors(['image_url' => 'Gagal mengunduh gambar URL saat update.']);
            }
        }

        $post->update($data);

        return redirect()->route('posts.index')->with('success', 'Berita berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $post = Post::findOrFail($id);
        
        if ($post->thumbnail) {
            Storage::disk('public')->delete($post->thumbnail);
        }
        
        $post->delete();

        return redirect()->route('posts.index')->with('success', 'Berita berhasil dihapus!');
    }
}