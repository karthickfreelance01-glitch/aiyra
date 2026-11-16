<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Enquiry;
use App\Models\Project;
use Illuminate\Support\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'projects' => Project::count(),
            'ongoing' => Project::where('status', 'ongoing')->count(),
            'completed' => Project::where('status', 'completed')->count(),
            'enquiries' => Enquiry::count(),
            'unread' => Enquiry::unread()->count(),
            'today' => Enquiry::whereDate('created_at', today())->count(),
        ];

        // Enquiries per month, last 6 months
        $chart = ['labels' => [], 'data' => []];
        for ($i = 5; $i >= 0; $i--) {
            $m = Carbon::now()->startOfMonth()->subMonths($i);
            $chart['labels'][] = $m->format('M');
            $chart['data'][] = Enquiry::whereBetween('created_at', [$m, $m->copy()->endOfMonth()])->count();
        }

        $byCategory = Project::selectRaw('category, count(*) as total')->groupBy('category')->pluck('total', 'category');

        $recentEnquiries = Enquiry::latest()->take(6)->get();
        $recentProjects = Project::latest()->take(5)->get();

        return view('admin.dashboard', compact('stats', 'chart', 'byCategory', 'recentEnquiries', 'recentProjects'));
    }
}
