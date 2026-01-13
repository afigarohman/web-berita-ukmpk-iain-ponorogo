@extends('layouts.admin')

@section('content')
    <div class="max-w-4xl mx-auto">
        <div class="flex justify-between items-center mb-8">
            <div>
                <h1 class="text-3xl font-bold text-gray-800">Tulis Berita Baru</h1>
                <p class="text-gray-500 text-sm mt-1">Isi formulir di bawah untuk mempublikasikan artikel.</p>
            </div>
            <a href="{{ route('posts.index') }}" class="text-gray-500 hover:text-[#2d4a53] transition flex items-center gap-2">
                &larr; Kembali
            </a>
        </div>

        <div class="bg-white rounded-2xl shadow-lg p-8 border-t-4 border-[#d4a017]">
            <form action="{{ route('posts.store') }}" method="POST" enctype="multipart/form-data">
                @csrf <div class="mb-6">
                    <label for="title" class="block text-sm font-bold text-gray-700 mb-2">Judul Artikel</label>
                    <input type="text" name="title" id="title" required
                        class="w-full px-4 py-3 rounded-lg bg-gray-50 border border-gray-200 focus:border-[#d4a017] focus:ring-2 focus:ring-yellow-100 outline-none transition"
                        placeholder="Contoh: Kegiatan KKN di Desa Muneng...">
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <label for="category" class="block text-sm font-bold text-gray-700 mb-2">Kategori</label>
                        <select name="category" id="category" required
                            class="w-full px-4 py-3 rounded-lg bg-gray-50 border border-gray-200 focus:border-[#d4a017] outline-none cursor-pointer">
                            <option value="" disabled selected>-- Pilih Kategori --</option>
                            <option value="Geneologi">Geneologi</option>
                            <option value="Opini">Opini</option>
                            <option value="Esai">Esai</option>
                            <option value="Artikel">Artikel</option>
                            <option value="Berita">Berita</option>
                            <option value="Resensi">Resensi</option>
                            <option value="Buletin">Buletin</option>
                            <option value="Sastra">Sastra</option>
                        </select>
                    </div>

                    <div>
                        <label for="thumbnail" class="block text-sm font-bold text-gray-700 mb-2">Gambar Utama</label>
                        <input type="file" name="thumbnail" id="thumbnail" accept="image/*"
                            class="w-full px-4 py-2 rounded-lg bg-gray-50 border border-gray-200 text-sm focus:border-[#d4a017] file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-yellow-50 file:text-yellow-700 hover:file:bg-yellow-100">
                        <p class="text-xs text-gray-400 mt-1">*Format: JPG, PNG, JPEG (Maks. 2MB)</p>
                    </div>
                </div>

                <div class="mb-8">
                    <label for="content" class="block text-sm font-bold text-gray-700 mb-2">Isi Artikel</label>
                    <textarea name="content" id="content" rows="10" required
                        class="w-full px-4 py-3 rounded-lg bg-gray-50 border border-gray-200 focus:border-[#d4a017] focus:ring-2 focus:ring-yellow-100 outline-none transition"
                        placeholder="Tulis isi berita di sini..."></textarea>
                </div>

                <div class="flex justify-end items-center gap-4">
                    <button type="reset" class="px-6 py-3 rounded-xl text-gray-500 hover:bg-gray-100 transition font-medium">
                        Reset
                    </button>
                    <button type="submit" class="px-8 py-3 rounded-xl bg-[#2d4a53] text-white font-bold shadow-lg hover:bg-[#1a2c32] hover:shadow-xl transition transform hover:-translate-y-1">
                        Publikasikan Sekarang 🚀
                    </button>
                </div>

            </form>
        </div>
    </div>
@endsection