@extends('layouts.admin')

@section('content')
    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">Dashboard Overview</h1>
            <p class="text-gray-500">Selamat datang kembali, {{ Auth::user()->name }}!</p>
        </div>
        <a href="{{ route('posts.create') }}" class="bg-[#d4a017] text-white px-6 py-2 rounded-full font-semibold shadow-lg hover:bg-yellow-600 transition">
    + Tulis Berita Baru
</a>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-[#2d4a53] rounded-2xl p-6 text-white shadow-lg relative overflow-hidden group hover:scale-105 transition-transform duration-300">
            <div class="relative z-10">
                <p class="text-gray-300 text-sm">Total Artikel</p>
                <h2 class="text-4xl font-bold mt-2">{{ $totalPosts }}</h2>
                <p class="text-xs mt-4 text-green-300">↑ Terus meningkat</p>
            </div>
            <div class="absolute -right-5 -bottom-5 w-24 h-24 bg-[#d4a017] rounded-full opacity-20"></div>
        </div>

        <div class="bg-white rounded-2xl p-6 shadow-lg border-l-4 border-[#2d4a53] hover:shadow-xl transition">
            <p class="text-gray-500 text-sm">Artikel Tayang</p>
            <h2 class="text-4xl font-bold text-[#2d4a53] mt-2">{{ $publishedPosts }}</h2>
            <p class="text-xs mt-4 text-gray-400">Siap dibaca publik</p>
        </div>

        <div class="bg-white rounded-2xl p-6 shadow-lg border-l-4 border-yellow-500 hover:shadow-xl transition">
            <p class="text-gray-500 text-sm">Draft (Belum Publish)</p>
            <h2 class="text-4xl font-bold text-yellow-600 mt-2">{{ $draftPosts }}</h2>
            <p class="text-xs mt-4 text-gray-400">Perlu diselesaikan</p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        <div class="lg:col-span-2 bg-white rounded-2xl shadow-sm p-6">
            <h3 class="font-bold text-gray-800 text-xl mb-4">Postingan Terbaru</h3>
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="text-gray-400 text-sm border-b border-gray-100">
                            <th class="py-3">Judul</th>
                            <th class="py-3">Kategori</th>
                            <th class="py-3">Status</th>
                            <th class="py-3">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="text-gray-600 text-sm">
                        @forelse($recentPosts as $post)
                        <tr class="hover:bg-gray-50 transition border-b border-gray-50 last:border-0">
                            <td class="py-4 font-medium text-gray-800">{{ Str::limit($post->title, 40) }}</td>
                            <td class="py-4"><span class="px-3 py-1 bg-gray-100 rounded-full text-xs">{{ $post->category }}</span></td>
                            <td class="py-4">
                                @if($post->is_published)
                                    <span class="text-green-600 font-bold text-xs">● Tayang</span>
                                @else
                                    <span class="text-yellow-600 font-bold text-xs">● Draft</span>
                                @endif
                            </td>
                            <td class="py-4">
                                <a href="#" class="text-[#2d4a53] hover:text-yellow-600 font-medium">Edit</a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="text-center py-6 text-gray-400">Belum ada berita.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-sm p-6 flex flex-col justify-between">
            <h3 class="font-bold text-gray-800 text-xl mb-4">Statistik Kunjungan</h3>
            <div class="flex-1 flex items-end justify-between space-x-2 h-40 px-2">
                <div class="w-full bg-gray-100 rounded-t-lg h-[40%] hover:bg-[#2d4a53] transition"></div>
                <div class="w-full bg-gray-100 rounded-t-lg h-[70%] hover:bg-[#2d4a53] transition"></div>
                <div class="w-full bg-gray-100 rounded-t-lg h-[50%] hover:bg-[#2d4a53] transition"></div>
                <div class="w-full bg-gray-100 rounded-t-lg h-[90%] bg-[#d4a017]"></div> <div class="w-full bg-gray-100 rounded-t-lg h-[60%] hover:bg-[#2d4a53] transition"></div>
            </div>
            <p class="text-center text-xs text-gray-400 mt-4">Data kunjungan minggu ini</p>
        </div>
    </div>
@endsection