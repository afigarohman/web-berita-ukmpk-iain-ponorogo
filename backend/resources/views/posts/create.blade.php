@extends('layouts.admin')

@section('content')
    <div class="max-w-5xl mx-auto">
        <div class="flex justify-between items-center mb-8">
            <div>
                <h1 class="text-3xl font-bold text-gray-800">Tulis Berita Baru</h1>
                <p class="text-gray-500 text-sm mt-1">Desain artikelmu seindah mungkin layaknya di Microsoft Word.</p>
            </div>
            <a href="{{ route('posts.index') }}" class="text-gray-500 hover:text-[#2d4a53] transition flex items-center gap-2">
                &larr; Kembali
            </a>
        </div>

        <div class="bg-white rounded-2xl shadow-lg p-8 border-t-4 border-[#d4a017]">
            <form action="{{ route('posts.store') }}" method="POST" enctype="multipart/form-data">
                @csrf 
                
                <div class="grid grid-cols-1 md:grid-cols-12 gap-6 mb-6">
                    
                    <div class="md:col-span-6">
                        <label for="title" class="block text-sm font-bold text-gray-700 mb-2">Judul Artikel</label>
                        <input type="text" name="title" id="title" required
                            class="w-full px-4 py-3 rounded-lg bg-gray-50 border border-gray-200 focus:border-[#d4a017] focus:ring-2 focus:ring-yellow-100 outline-none transition"
                            placeholder="Judul yang menarik...">
                    </div>

                    <div class="md:col-span-3">
                        <label for="category" class="block text-sm font-bold text-gray-700 mb-2">Kategori</label>
                        <select name="category" id="category" required
                            class="w-full px-4 py-3 rounded-lg bg-gray-50 border border-gray-200 focus:border-[#d4a017] focus:ring-2 focus:ring-yellow-100 outline-none cursor-pointer">
                            <option value="" disabled selected>-- Pilih --</option>
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

                    <div class="md:col-span-3 flex items-end">
                        <div class="w-full bg-yellow-50 border border-yellow-200 rounded-lg p-3 flex items-center justify-between h-[50px]">
                            <label for="is_popular" class="text-sm font-bold text-gray-700 cursor-pointer select-none">
                                🔥 Populer?
                            </label>
                            <div class="relative inline-block w-12 mr-2 align-middle select-none transition duration-200 ease-in">
                                <input type="checkbox" name="is_popular" id="is_popular" class="toggle-checkbox absolute block w-6 h-6 rounded-full bg-white border-4 appearance-none cursor-pointer"/>
                                <label for="is_popular" class="toggle-label block overflow-hidden h-6 rounded-full bg-gray-300 cursor-pointer"></label>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mb-6 p-4 bg-gray-50 rounded-xl border border-gray-200">
                    <label class="block text-sm font-bold text-gray-800 mb-2">Cover / Thumbnail Depan</label>
                    <div class="flex flex-col md:flex-row gap-4 items-center">
                        <div class="w-full md:w-1/2">
                            <input type="file" name="thumbnail" accept="image/*" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-[#2d4a53] file:text-white hover:file:bg-[#1a2c32]">
                            <p class="text-xs text-gray-500 mt-1">*Upload dari Laptop</p>
                        </div>
                        <div class="hidden md:block text-gray-400 font-bold">OR</div>
                        <div class="w-full md:w-1/2">
                            <input type="text" name="image_url" placeholder="Paste link gambar (https://...) di sini" class="w-full px-4 py-2 rounded-lg border border-gray-300 text-sm focus:outline-none focus:border-[#d4a017]">
                            <p class="text-xs text-gray-500 mt-1">*Ambil dari Internet</p>
                        </div>
                    </div>
                </div>

                <div class="mb-8">
                    <label for="content" class="block text-sm font-bold text-gray-700 mb-2">Isi Artikel (Desain Bebas)</label>
                    
                    <textarea name="content" id="editor" rows="20">
                        <p>Mulai tulis ceritamu di sini...</p>
                    </textarea>
                </div>

                <div class="flex justify-end items-center gap-4 pt-4 border-t border-gray-100">
                    <button type="reset" class="px-6 py-3 rounded-xl text-gray-500 hover:bg-gray-100 transition font-medium">Reset</button>
                    <button type="submit" class="px-8 py-3 rounded-xl bg-[#2d4a53] text-white font-bold shadow-lg hover:bg-[#1a2c32] hover:shadow-xl transition transform hover:-translate-y-1">
                        Publikasikan Sekarang 🚀
                    </button>
                </div>

            </form>
        </div>
    </div>

    <script src="https://cdn.ckeditor.com/ckeditor5/39.0.1/classic/ckeditor.js"></script>
    
    <style>
        /* CKEditor Custom Height */
        .ck-editor__editable_inline {
            min-height: 400px;
            padding: 2rem !important;
        }
        .ck-content .image {
            margin: 1em 0;
        }
        .ck-content .image img {
            display: block;
            margin: 0 auto;
            max-width: 100%;
            min-width: 50px;
        }

        /* Toggle Switch CSS */
        .toggle-checkbox:checked {
            right: 0;
            border-color: #d4a017;
        }
        .toggle-checkbox:checked + .toggle-label {
            background-color: #d4a017;
        }
        .toggle-checkbox {
            right: 50%;
            transition: all 0.3s;
        }
    </style>
    
    <script>
        // Adapter untuk Upload Gambar Drag & Drop (Base64)
        class MyUploadAdapter {
            constructor(loader) {
                this.loader = loader;
            }

            upload() {
                return this.loader.file
                    .then(file => new Promise((resolve, reject) => {
                        const reader = new FileReader();
                        reader.onload = () => {
                            resolve({ default: reader.result });
                        };
                        reader.onerror = error => reject(error);
                        reader.readAsDataURL(file);
                    }));
            }

            abort() {
                // Handle abort
            }
        }

        function MyCustomUploadAdapterPlugin(editor) {
            editor.plugins.get('FileRepository').createUploadAdapter = (loader) => {
                return new MyUploadAdapter(loader);
            };
        }

        ClassicEditor
            .create(document.querySelector('#editor'), {
                extraPlugins: [MyCustomUploadAdapterPlugin], 
                toolbar: {
                    items: [
                        'heading', '|',
                        'bold', 'italic', 'underline', 'strikethrough', '|',
                        'fontColor', 'fontBackgroundColor', '|',
                        'bulletedList', 'numberedList', '|',
                        'outdent', 'indent', '|',
                        'imageUpload', 'blockQuote', 'insertTable', 'mediaEmbed', 'link', '|', 
                        'undo', 'redo'
                    ]
                },
                image: {
                    toolbar: [
                        'imageTextAlternative', 'toggleImageCaption', 'imageStyle:inline',
                        'imageStyle:block', 'imageStyle:side'
                    ]
                },
                table: {
                    contentToolbar: [
                        'tableColumn', 'tableRow', 'mergeTableCells'
                    ]
                }
            })
            .catch(error => {
                console.error(error);
            });
    </script>
@endsection