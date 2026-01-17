<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Rating;
use Illuminate\Http\Request;
use Inertia\Inertia;

class RatingModerationController extends Controller
{
    /**
     * Display a listing of ratings for moderation.
     */
    public function index(Request $request)
    {
        $this->authorize('moderate ratings');

        $query = Rating::with(['user', 'item'])
            ->latest();

        // Filtros
        if ($request->filled('search')) {
            $query->whereHas('item', function ($q) use ($request) {
                $q->where('title', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->filled('rating')) {
            $query->where('rating', $request->rating);
        }

        if ($request->filled('with_review')) {
            $query->whereNotNull('review')->where('review', '!=', '');
        }

        $ratings = $query->paginate(20);

        return Inertia::render('Admin/Ratings/Index', [
            'ratings' => $ratings,
            'filters' => $request->only(['search', 'rating', 'with_review']),
        ]);
    }

    /**
     * Remove the specified rating (moderation).
     */
    public function destroy(Rating $rating)
    {
        $this->authorize('moderate ratings');

        Rating::destroy($rating->id);

        return back()->with('success', 'Calificación eliminada exitosamente.');
    }
}
