@extends('layouts.admin')

@section('content')
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

<div class="min-h-screen">
    
    <div class="flex flex-col md:flex-row justify-between items-center mb-8">
        <div>
            <h1 class="text-3xl font-bold text-slate-800">Selamat Datang Admin</h1>
        </div>
        
        <div class="mt-4 md:mt-0 relative">
            <input type="text" placeholder="Cari data..." class="pl-10 pr-4 py-2 rounded-full border border-slate-200 focus:outline-none focus:border-[#d4a017] focus:ring-1 focus:ring-[#d4a017] w-64 text-sm transition shadow-sm">
            <svg class="w-4 h-4 text-slate-400 absolute left-3 top-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100 flex items-center justify-between hover:shadow-md transition">
            <div>
                <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Total Artikel</p>
                <h2 class="text-3xl font-bold text-slate-800 mt-1">{{ $totalPosts }}</h2>
                <span class="text-[10px] text-green-500 font-bold bg-green-50 px-2 py-1 rounded-full mt-2 inline-block">+ Data Terbaru</span>
            </div>
            <div class="w-12 h-12 bg-blue-50 rounded-full flex items-center justify-center text-blue-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"></path></svg>
            </div>
        </div>

        <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100 flex items-center justify-between hover:shadow-md transition">
            <div>
                <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Tayang</p>
                <h2 class="text-3xl font-bold text-slate-800 mt-1">{{ $publishedPosts }}</h2>
                <span class="text-[10px] text-slate-400 mt-2 inline-block">Siap dibaca publik</span>
            </div>
            <div class="w-12 h-12 bg-teal-50 rounded-full flex items-center justify-center text-teal-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>
        </div>

        <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100 flex items-center justify-between hover:shadow-md transition">
            <div>
                <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Draft</p>
                <h2 class="text-3xl font-bold text-slate-800 mt-1">{{ $draftPosts }}</h2>
                <span class="text-[10px] text-orange-500 font-bold bg-orange-50 px-2 py-1 rounded-full mt-2 inline-block">Perlu Revisi</span>
            </div>
            <div class="w-12 h-12 bg-orange-50 rounded-full flex items-center justify-center text-orange-500">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
            </div>
        </div>

        <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100 flex items-center justify-between hover:shadow-md transition">
            <div>
                <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Kategori Aktif</p>
                <h2 class="text-3xl font-bold text-slate-800 mt-1">{{ $totalCategories }}</h2>
                <span class="text-[10px] text-slate-400 mt-2 inline-block">Topik berbeda</span>
            </div>
            <div class="w-12 h-12 bg-purple-50 rounded-full flex items-center justify-center text-purple-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path></svg>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
        
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100">
            <div class="flex justify-between items-center mb-6">
                <h3 class="font-bold text-slate-700">Sebaran Kategori</h3>
            </div>
            <div id="chartCategory" class="flex justify-center"></div>
            <p class="text-center text-xs text-slate-400 mt-4">Distribusi konten berdasarkan topik</p>
        </div>

        <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100 lg:col-span-2">
            <div class="flex flex-col sm:flex-row justify-between items-center mb-6">
                <h3 class="font-bold text-slate-700 mb-4 sm:mb-0">Statistik Pengunjung</h3>
                
                <div class="flex bg-slate-100 p-1 rounded-lg">
                    <button onclick="updateChart('daily')" id="btn-daily" class="px-4 py-1 text-xs font-bold rounded-md transition text-slate-500 hover:bg-white hover:shadow-sm">
                        Harian
                    </button>
                    <button onclick="updateChart('weekly')" id="btn-weekly" class="px-4 py-1 text-xs font-bold rounded-md transition bg-white shadow-sm text-[#2d4a53]">
                        Mingguan
                    </button>
                    <button onclick="updateChart('monthly')" id="btn-monthly" class="px-4 py-1 text-xs font-bold rounded-md transition text-slate-500 hover:bg-white hover:shadow-sm">
                        Bulanan
                    </button>
                </div>
            </div>
            <div id="chartVisitors"></div>
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6">
        <div class="flex justify-between items-center mb-6">
            <h3 class="font-bold text-lg text-slate-700">Berita Terbaru</h3>
            <a href="{{ route('posts.index') }}" class="text-sm text-[#d4a017] font-bold hover:underline">Lihat Semua</a>
        </div>
        
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="text-slate-400 text-xs uppercase tracking-wider border-b border-slate-100">
                        <th class="py-3 font-semibold">Judul Artikel</th>
                        <th class="py-3 font-semibold">Kategori</th>
                        <th class="py-3 font-semibold">Penulis</th>
                        <th class="py-3 font-semibold">Status</th>
                        <th class="py-3 font-semibold text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="text-sm text-slate-600">
                    @forelse($recentPosts as $post)
                    <tr class="hover:bg-slate-50 transition border-b border-slate-50 last:border-0">
                        <td class="py-4 pr-4 font-medium text-slate-800">
                            {{ Str::limit($post->title, 50) }}
                        </td>
                        <td class="py-4">
                            <span class="px-3 py-1 rounded-full text-xs font-bold 
                                {{ $post->category == 'Berita' ? 'bg-blue-50 text-blue-600' : 
                                  ($post->category == 'Geneologi' ? 'bg-purple-50 text-purple-600' : 'bg-slate-100 text-slate-500') }}">
                                {{ $post->category }}
                            </span>
                        </td>
                        <td class="py-4 flex items-center gap-2">
                            <div class="w-6 h-6 rounded-full bg-slate-200 overflow-hidden">
                                <img src="https://ui-avatars.com/api/?name={{ $post->author }}&background=random" alt="">
                            </div>
                            {{ $post->author }}
                        </td>
                        <td class="py-4">
                            @if($post->is_published)
                                <span class="text-teal-600 font-bold text-xs flex items-center gap-1">
                                    <span class="w-2 h-2 rounded-full bg-teal-500"></span> Tayang
                                </span>
                            @else
                                <span class="text-orange-500 font-bold text-xs flex items-center gap-1">
                                    <span class="w-2 h-2 rounded-full bg-orange-500"></span> Draft
                                </span>
                            @endif
                        </td>
                        <td class="py-4 text-right">
                            <a href="{{ route('posts.edit', $post->id) }}" class="text-slate-400 hover:text-[#d4a017] transition">
                                <svg class="w-5 h-5 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center py-8 text-slate-400 italic">Belum ada data berita.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>

<script>
    var optionsCategory = {
        series: @json($categoryData ?? []), 
        chart: {
            type: 'donut',
            height: 300,
            fontFamily: 'Poppins, sans-serif'
        },
        labels: @json($categoryLabels ?? []),
        colors: ['#2d4a53', '#d4a017', '#14b8a6', '#f59e0b', '#6366f1'],
        plotOptions: { pie: { donut: { size: '65%' } } },
        dataLabels: { enabled: false },
        legend: { position: 'bottom' },
        noData: { text: 'Belum ada data kategori' }
    };

    var chartCategory = new ApexCharts(document.querySelector("#chartCategory"), optionsCategory);
    chartCategory.render();


    var initialLabels = @json($visitorLabels ?? []); 
    var initialData = @json($visitorData ?? []);

    var optionsVisitors = {
        series: [{
            name: 'Pengunjung',
            data: initialData
        }],
        chart: {
            type: 'area',
            height: 320,
            toolbar: { show: false },
            fontFamily: 'Poppins, sans-serif',
            animations: { enabled: true } 
        },
        colors: ['#2d4a53'],
        fill: {
            type: 'gradient',
            gradient: {
                shadeIntensity: 1,
                opacityFrom: 0.7,
                opacityTo: 0.1,
                stops: [0, 90, 100]
            }
        },
        dataLabels: { enabled: false },
        stroke: { curve: 'smooth', width: 3 },
        xaxis: {
            categories: initialLabels,
            axisBorder: { show: false },
            axisTicks: { show: false }
        },
        yaxis: { show: false },
        grid: {
            show: true,
            strokeDashArray: 4,
            padding: { top: 0, right: 0, bottom: 0, left: 10 } 
        },
        tooltip: {
            y: { formatter: function (val) { return val + " Orang" } }
        }
    };

    var chartVisitors = new ApexCharts(document.querySelector("#chartVisitors"), optionsVisitors);
    chartVisitors.render();

    function updateChart(filter) {
        
        document.querySelectorAll('button[id^="btn-"]').forEach(btn => {
            btn.className = "px-4 py-1 text-xs font-bold rounded-md transition text-slate-500 hover:bg-white hover:shadow-sm";
        });
        
        const activeBtn = document.getElementById('btn-' + filter);
        activeBtn.className = "px-4 py-1 text-xs font-bold rounded-md transition bg-white shadow-sm text-[#2d4a53]";

        fetch("{{ route('dashboard.chart') }}?filter=" + filter)
            .then(response => response.json())
            .then(data => {
                chartVisitors.updateOptions({
                    xaxis: { categories: data.labels }
                });
                chartVisitors.updateSeries([{
                    data: data.data
                }]);
            })
            .catch(error => {
                console.error('Error fetching chart data:', error);
                alert('Gagal mengambil data grafik. Pastikan server berjalan.');
            });
    }
</script>
@endsection