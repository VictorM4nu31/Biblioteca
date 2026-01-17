<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Item;
use App\Models\Collection;
use App\Models\Rating;
use App\Models\ReadingProgress;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Display user dashboard.
     */
    public function index()
    {
        $user = Auth::user();

        // Estadísticas del usuario
        $stats = [
            'total_collections' => Collection::where('user_id', '=', $user->id, 'and')->count(),
            'total_ratings' => Rating::where('user_id', '=', $user->id, 'and')->count(),
            'currently_reading' => ReadingProgress::where('user_id', '=', $user->id, 'and')
                ->where('status', '=', 'reading', 'and')
                ->count(),
            'completed' => ReadingProgress::where('user_id', '=', $user->id, 'and')
                ->where('status', '=', 'completed', 'and')
                ->count(),
            'wishlist' => ReadingProgress::where('user_id', '=', $user->id, 'and')
                ->where('status', '=', 'wishlist', 'and')
                ->count(),
        ];

        // Items en lectura
        $currentlyReading = ReadingProgress::where('user_id', '=', $user->id, 'and')
            ->where('status', '=', 'reading', 'and')
            ->with(['item' => function ($query) {
                $query->with(['uploader', 'categories']);
            }])
            ->latest()
            ->take(5)
            ->get();

        // Últimas calificaciones
        $recentRatings = Rating::where('user_id', '=', $user->id, 'and')
            ->with(['item' => function ($query) {
                $query->with(['uploader', 'categories']);
            }])
            ->latest()
            ->take(5)
            ->get();

        // Colecciones del usuario
        $collections = Collection::where('user_id', '=', $user->id, 'and')
            ->withCount('items')
            ->latest()
            ->take(5)
            ->get();

        return Inertia::render('User/Dashboard', [
            'stats' => $stats,
            'currentlyReading' => $currentlyReading,
            'recentRatings' => $recentRatings,
            'collections' => $collections,
        ]);
    }
}
