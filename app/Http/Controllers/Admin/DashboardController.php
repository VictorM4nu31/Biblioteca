<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Item;
use App\Models\Category;
use App\Models\Collection;
use App\Models\Rating;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class DashboardController extends Controller
{
    /**
     * Display admin dashboard.
     */
    public function index()
    {
        $this->authorize('manage users');

        // Estadísticas generales
        $stats = [
            'total_users' => User::count(),
            'total_items' => Item::count(),
            'total_categories' => Category::count(),
            'total_collections' => Collection::count(),
            'total_ratings' => Rating::count(),
            'public_collections' => Collection::where('is_public', true)->count(),
        ];

        // Estadísticas por tipo de item
        $itemsByType = Item::select('type', DB::raw('count(*) as total'))
            ->groupBy('type')
            ->get()
            ->pluck('total', 'type');

        // Últimos items subidos
        $recentItems = Item::with(['uploader', 'categories'])
            ->latest()
            ->take(10)
            ->get();

        // Usuarios más activos
        $activeUsers = User::withCount(['uploadedItems', 'ratings', 'collections'])
            ->orderByDesc('uploaded_items_count')
            ->take(10)
            ->get();

        // Categorías más populares
        $popularCategories = Category::withCount('items')
            ->orderByDesc('items_count')
            ->take(10)
            ->get();

        // Items mejor calificados
        $topRatedItems = Item::with(['uploader', 'categories'])
            ->withAvg('ratings', 'rating')
            ->withCount('ratings')
            ->having('ratings_count', '>=', 3)
            ->orderByDesc('ratings_avg_rating')
            ->take(10)
            ->get();

        return Inertia::render('Admin/Dashboard', [
            'stats' => $stats,
            'itemsByType' => $itemsByType,
            'recentItems' => $recentItems,
            'activeUsers' => $activeUsers,
            'popularCategories' => $popularCategories,
            'topRatedItems' => $topRatedItems,
        ]);
    }
}
