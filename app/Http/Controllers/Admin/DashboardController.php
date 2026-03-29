<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Addon;
use App\Models\Article;
use App\Models\Purchase;
use App\Models\Project;
use App\Models\Service;
use App\Models\SiteVisit;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        // Monthly revenue for the last 6 months
        $monthlyRevenue = [];
        for ($i = 5; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $monthlyRevenue[] = [
                'month' => $date->format('M'),
                'revenue' => Purchase::where('status', 'completed')
                    ->whereYear('created_at', $date->year)
                    ->whereMonth('created_at', $date->month)
                    ->sum('amount'),
            ];
        }

        $maxRevenue = max(array_column($monthlyRevenue, 'revenue')) ?: 1;

        $stats = [
            'total_users' => User::count(),
            'total_addons' => Addon::count(),
            'total_projects' => Project::count(),
            'total_purchases' => Purchase::count(),
            'total_articles' => Article::count(),
            'total_services' => Service::count(),
            'revenue' => Purchase::where('status', 'completed')->sum('amount'),
            'monthly_revenue' => Purchase::where('status', 'completed')
                ->whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year)
                ->sum('amount'),
            'recent_purchases' => Purchase::with(['user', 'addon'])->latest()->take(10)->get(),
            'recent_users' => User::latest()->take(5)->get(),
            'monthly_chart' => $monthlyRevenue,
            'max_revenue' => $maxRevenue,
            'visits_today' => SiteVisit::where('visited_date', now()->toDateString())->count(),
            'visits_week' => SiteVisit::where('visited_date', '>=', now()->subDays(7)->toDateString())->count(),
            'visits_month' => SiteVisit::where('visited_date', '>=', now()->subDays(30)->toDateString())->count(),
        ];

        return view('admin.dashboard', compact('stats'));
    }
}