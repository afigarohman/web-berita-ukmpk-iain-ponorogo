@extends('layouts.admin')

@section('content')
    <div class="max-w-4xl mx-auto">
        <div class="flex justify-between items-center mb-8">
            <h1 class="text-3xl font-bold text-gray-800">Edit Artikel</h1>
            <a href="{{ route('posts.index') }}" class="text-gray-500 hover:text-[#2d4a53]">&larr; Kembali</a>
        </div>

        <div class="bg-white rounded-2xl shadow-lg p-8 border-t-4 border-[#d4a017]">
            <form action="{{ route('posts.update', $post->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT') <div class="mb-6">
                    <label class="block text-sm font-bold text-gray-700 mb-2">Judul Artikel</label>
                    <input type="text" name="title" value="{{ old('title', $post->title) }}" required
                        class="w-full px-4 py-3 rounded-lg bg-gray-50 border border-gray-200 focus:border-[#d4a017] outline-none">
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Kategori</label>
                        <select name="category" required class="w-full px-4 py-3 rounded-lg bg-gray-50 border border-gray-200 outline-none">
                            @foreach(['Geneologi', 'Opini', 'Esai', 'Artikel', 'Berita', 'Resensi', 'Buletin', 'Sastra'] as $cat)
                                <option value="{{ $cat }}" {{ $post->category == $cat ? 'selected' : '' }}>{{ $cat }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Ganti Gambar (Opsional)</label>
                        <input type="file" name="thumbnail" accept="image/*"
                            class="w-full px-4 py-2 rounded-lg bg-gray-50 border border-gray-200 text-sm">
                        @if($post->thumbnail)
                            <p class="text-xs text-gray-500 mt-1">Gambar saat ini: <a href="{{ asset('storage/' . $post->thumbnail) }}" target="_blank" class="text-blue-500 underline">Lihat</a></p>
                        @endif
                    </div>
                </div>

                <div class="mb-8">
                    <label class="block text-sm font-bold text-gray-700 mb-2">Isi Artikel</label>
                    <textarea name="content" rows="10" required
                        class="w-full px-4 py-3 rounded-lg bg-gray-50 border border-gray-200 outline-none">{{ old('content', $post->content) }}</textarea>
                </div>

                <div class="flex justify-end gap-4">
                    <button type="submit" class="px-8 py-3 rounded-xl bg-[#2d4a53] text-white font-bold shadow-lg hover:bg-[#1a2c32] transition">
                        Simpan Perubahan 💾
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection