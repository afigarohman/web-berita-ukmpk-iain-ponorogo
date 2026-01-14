<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - UKM PK</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Poppins', sans-serif; }
        /* Style untuk menu yang sedang aktif */
        .sidebar-active {
            background-color: rgba(255, 255, 255, 0.1);
            border-left: 4px solid #d4a017; /* Garis Emas */
            color: white;
        }
    </style>
</head>
<body class="bg-gray-100">

    <div class="flex min-h-screen">
        
        <aside class="w-64 bg-[#2d4a53] text-white fixed h-full shadow-xl z-50 overflow-y-auto">
            
            <div class="p-6 text-center border-b border-gray-600">
                <div class="w-20 h-20 mx-auto rounded-full bg-gray-300 overflow-hidden border-4 border-[#d4a017]">
                    <img src="https://ui-avatars.com/api/?name={{ Auth::user()->name ?? 'Admin' }}&background=d4a017&color=fff" alt="Admin">
                </div>
                <h3 class="mt-3 font-bold text-lg">{{ Auth::user()->name ?? 'Administrator' }}</h3>
                <p class="text-xs text-gray-300">{{ Auth::user()->email ?? 'email@admin.com' }}</p>
            </div>

            <nav class="mt-6 px-4 space-y-2 pb-20">
                
                <a href="{{ route('dashboard') }}" class="flex items-center px-4 py-3 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('dashboard') ? 'sidebar-active' : 'hover:bg-gray-700' }}">
                    <span class="mr-3 text-[#d4a017]">🏠</span> Dashboard
                </a>
                
                <p class="px-4 text-xs font-semibold text-gray-400 uppercase mt-4 mb-2">Manajemen Konten</p>
                
                <a href="{{ route('posts.index', ['category' => 'Berita']) }}" 
                   class="flex items-center px-4 py-3 text-sm font-medium rounded-lg hover:bg-gray-700 transition-colors {{ request()->fullUrlIs('*category=Berita*') ? 'sidebar-active' : '' }}">
                    <span class="mr-3 text-[#d4a017]">📰</span> Berita
                </a>

                <a href="{{ route('posts.index', ['category' => 'Opini']) }}" 
                   class="flex items-center px-4 py-3 text-sm font-medium rounded-lg hover:bg-gray-700 transition-colors {{ request()->fullUrlIs('*category=Opini*') ? 'sidebar-active' : '' }}">
                    <span class="mr-3 text-[#d4a017]">🗣️</span> Opini
                </a>

                <a href="{{ route('posts.index', ['category' => 'Esai']) }}" 
                   class="flex items-center px-4 py-3 text-sm font-medium rounded-lg hover:bg-gray-700 transition-colors {{ request()->fullUrlIs('*category=Esai*') ? 'sidebar-active' : '' }}">
                    <span class="mr-3 text-[#d4a017]">✍️</span> Esai
                </a>

                <a href="{{ route('posts.index', ['category' => 'Artikel']) }}" 
                   class="flex items-center px-4 py-3 text-sm font-medium rounded-lg hover:bg-gray-700 transition-colors {{ request()->fullUrlIs('*category=Artikel*') ? 'sidebar-active' : '' }}">
                    <span class="mr-3 text-[#d4a017]">📝</span> Artikel
                </a>

                <a href="{{ route('posts.index', ['category' => 'Geneologi']) }}" 
                   class="flex items-center px-4 py-3 text-sm font-medium rounded-lg hover:bg-gray-700 transition-colors {{ request()->fullUrlIs('*category=Geneologi*') ? 'sidebar-active' : '' }}">
                    <span class="mr-3 text-[#d4a017]">🧬</span> Geneologi
                </a>

                <a href="{{ route('posts.index', ['category' => 'Resensi']) }}" 
                   class="flex items-center px-4 py-3 text-sm font-medium rounded-lg hover:bg-gray-700 transition-colors {{ request()->fullUrlIs('*category=Resensi*') ? 'sidebar-active' : '' }}">
                    <span class="mr-3 text-[#d4a017]">🔍</span> Resensi
                </a>

                <a href="{{ route('posts.index', ['category' => 'Buletin']) }}" 
                   class="flex items-center px-4 py-3 text-sm font-medium rounded-lg hover:bg-gray-700 transition-colors {{ request()->fullUrlIs('*category=Buletin*') ? 'sidebar-active' : '' }}">
                    <span class="mr-3 text-[#d4a017]">📢</span> Buletin
                </a>

                <a href="{{ route('posts.index', ['category' => 'Sastra']) }}" 
                   class="flex items-center px-4 py-3 text-sm font-medium rounded-lg hover:bg-gray-700 transition-colors {{ request()->fullUrlIs('*category=Sastra*') ? 'sidebar-active' : '' }}">
                    <span class="mr-3 text-[#d4a017]">📚</span> Sastra
                </a>

                <form method="POST" action="{{ route('logout') }}" class="mt-10 mb-6">
                    @csrf
                    <button type="submit" class="flex w-full items-center px-4 py-3 text-sm font-medium text-red-300 hover:bg-red-900 rounded-lg transition-colors">
                        <span class="mr-3">🚪</span> Logout
                    </button>
                </form>
            </nav>
        </aside>

        <main class="flex-1 ml-64 p-8">
            @yield('content')
        </main>
    </div>

</body>
</html>