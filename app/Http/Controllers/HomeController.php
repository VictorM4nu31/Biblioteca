<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Category;
use App\Models\Collection;
use App\Models\Rating;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class HomeController extends Controller
{
    /**
     * Display the home page.
     */
    public function index()
    {
        // Items destacados (mejor calificados)
        $featuredItems = Item::with(['uploader', 'categories', 'tags'])
            ->withAvg('ratings', 'rating')
            ->withCount('ratings')
            ->having('ratings_count', '>=', 3)
            ->orderByDesc('ratings_avg_rating')
            ->take(6)
            ->get();

        // Últimos items agregados
        $recentItems = Item::with(['uploader', 'categories', 'tags'])
            ->latest()
            ->take(12)
            ->get();

        // Categorías populares
        $popularCategories = Category::withCount('items')
            ->having('items_count', '>', 0)
            ->orderByDesc('items_count')
            ->take(8)
            ->get();

        // Colecciones públicas destacadas
        $publicCollections = Collection::where('is_public', true)
            ->with('user')
            ->withCount('items')
            ->having('items_count', '>', 0)
            ->latest()
            ->take(6)
            ->get();

        // Estadísticas generales
        $stats = [
            'total_items' => Item::count(),
            'total_books' => Item::where('type', 'book')->count(),
            'total_comics' => Item::where('type', 'comic')->count(),
            'total_magazines' => Item::where('type', 'magazine')->count(),
            'total_categories' => Category::count(),
        ];

        return Inertia::render('Home', [
            'featuredItems' => $featuredItems,
            'recentItems' => $recentItems,
            'popularCategories' => $popularCategories,
            'publicCollections' => $publicCollections,
            'stats' => $stats,
        ]);
    }

    /**
     * Display the welcome page for guests.
     */
    public function welcome()
    {
        if (auth()->check()) {
            return redirect()->route('home');
        }

        return Inertia::render('Welcome', [
            'stats' => [
                'total_items' => Item::count(),
                'total_categories' => Category::count(),
            ],
        ]);
    }
}
