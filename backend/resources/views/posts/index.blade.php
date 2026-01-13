@extends('layouts.admin')

@section('content')
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-gray-800">
            {{ $category ? 'Kategori: ' . $category : 'Semua Postingan' }}
        </h1>
        <a href="{{ route('posts.create') }}" class="bg-[#d4a017] text-white px-6 py-2 rounded-full font-semibold shadow hover:bg-yellow-600 transition">
            + Tambah Baru
        </a>
    </div>

    <div class="bg-white rounded-2xl shadow-sm p-6">
        @if(session('success'))
            <div class="bg-green-100 text-green-800 p-4 rounded-lg mb-4">
                {{ session('success') }}
            </div>
        @endif

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="text-gray-400 text-sm border-b border-gray-100">
                        <th class="py-3 px-2">Gambar</th>
                        <th class="py-3">Judul</th>
                        <th class="py-3">Kategori</th>
                        <th class="py-3">Penulis</th>
                        <th class="py-3">Tanggal</th>
                        <th class="py-3">Dilihat</th>
                        <th class="py-3">Aksi</th> </tr>
                </thead>
                <tbody class="text-gray-600 text-sm">
                    {{-- Loop data dimulai di sini --}}
                    @forelse($posts as $post)
                    <tr class="hover:bg-gray-50 transition border-b border-gray-50 last:border-0">
                        <td class="py-3 px-2">
                            @if($post->thumbnail)
                                <img src="{{ asset('storage/' . $post->thumbnail) }}" class="w-12 h-12 object-cover rounded-lg">
                            @else
                                <div class="w-12 h-12 bg-gray-200 rounded-lg"></div>
                            @endif
                        </td>
                        <td class="py-3 font-medium text-gray-800">{{ $post->title }}</td>
                        <td class="py-3"><span class="px-3 py-1 bg-gray-100 rounded-full text-xs">{{ $post->category }}</span></td>
                        <td class="py-3">{{ $post->author }}</td>
                        <td class="py-3">{{ $post->created_at->format('d M Y') }}</td>
                        <td class="py-3">
    <div class="flex items-center gap-1 text-gray-600 font-semibold bg-gray-100 px-3 py-1 rounded-full w-max text-xs">
        👁️ {{ $post->views_count }}x
    </div>
</td>
                        
                        {{-- Tombol Edit & Hapus ada DI DALAM sini --}}
                        <td class="py-3 flex items-center gap-2">
                            <a href="{{ route('posts.edit', $post->id) }}" class="text-[#2d4a53] hover:text-[#d4a017] font-medium transition" title="Edit">
                                ✏️ Edit
                            </a>

                            <form action="{{ route('posts.destroy', $post->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus berita ini?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-500 hover:text-red-700 font-medium transition ml-2" title="Hapus">
                                    🗑️ Hapus
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-10 text-gray-400">Belum ada data di kategori ini.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
            
            <div class="mt-4">
                {{ $posts->links() }}
            </div>
        </div>
    </div>
@endsection