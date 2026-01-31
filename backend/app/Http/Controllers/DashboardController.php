<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $totalPosts = Post::count();
        $publishedPosts = Post::where('is_published', true)->count();
        $draftPosts = Post::where('is_published', false)->count();
        $totalCategories = Post::distinct('category')->count('category');

        $categories = Post::select('category', DB::raw('count(*) as total'))
            ->groupBy('category')
            ->get();
        
        $categoryLabels = $categories->pluck('category');
        $categoryData = $categories->pluck('total');

        $visits = DB::table('visits')
            ->select(DB::raw('DATE(visit_date) as date'), DB::raw('count(*) as total'))
            ->where('visit_date', '>=', Carbon::now()->subDays(6))
            ->groupBy('date')
            ->orderBy('date', 'asc')
            ->get();

        $visitorLabels = [];
        $visitorData = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i)->format('Y-m-d');
            $dayName = Carbon::now()->subDays($i)->format('D');
            
            $visitor = $visits->firstWhere('date', $date);
            
            $visitorLabels[] = $dayName;
            $visitorData[] = $visitor ? $visitor->total : 0;
        }

        $recentPosts = Post::latest()->take(5)->get();

        return view('dashboard', compact(
            'totalPosts', 
            'publishedPosts', 
            'draftPosts', 
            'totalCategories',
            'recentPosts',
            'categoryLabels',
            'categoryData',
            'visitorLabels',
            'visitorData'
        ));
    }

    public function getVisitorChartData(Request $request)
    {
        $filter = $request->query('filter', 'weekly');
        $labels = [];
        $data = [];

        if ($filter == 'daily') {
            $visits = DB::table('visits')
                ->select(DB::raw('HOUR(created_at) as hour'), DB::raw('count(*) as total'))
                ->whereDate('created_at', Carbon::today())
                ->groupBy('hour')
                ->pluck('total', 'hour');

            for ($i = 0; $i < 24; $i++) {
                $labels[] = str_pad($i, 2, '0', STR_PAD_LEFT) . ':00';
                $data[] = $visits[$i] ?? 0;
            }

        } elseif ($filter == 'monthly') {
            $startOfMonth = Carbon::now()->startOfMonth();
            $endOfMonth = Carbon::now()->endOfMonth();

            $visits = DB::table('visits')
                ->select(DB::raw('DATE(visit_date) as date'), DB::raw('count(*) as total'))
                ->whereBetween('visit_date', [$startOfMonth, $endOfMonth])
                ->groupBy('date')
                ->pluck('total', 'date');

            $current = $startOfMonth->copy();
            while ($current <= $endOfMonth) {
                $dateStr = $current->format('Y-m-d');
                $labels[] = $current->format('d M');
                $data[] = $visits[$dateStr] ?? 0;
                $current->addDay();
            }

        } else {
            $visits = DB::table('visits')
                ->select(DB::raw('DATE(visit_date) as date'), DB::raw('count(*) as total'))
                ->where('visit_date', '>=', Carbon::now()->subDays(6))
                ->groupBy('date')
                ->pluck('total', 'date');

            for ($i = 6; $i >= 0; $i--) {
                $date = Carbon::now()->subDays($i);
                $dateStr = $date->format('Y-m-d');
                $labels[] = $date->format('D');
                $data[] = $visits[$dateStr] ?? 0;
            }
        }

        return response()->json([
            'labels' => $labels,
            'data' => $data
        ]);
    }
}