<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Item;
use App\Models\Rating;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;

class RatingController extends Controller
{
    /**
     * Store a newly created rating.
     */
    public function store(Request $request, Item $item)
    {
        $this->authorize('create ratings');

        $validated = $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'review' => 'nullable|string|max:1000',
        ]);

        // Verificar si el usuario ya calificó este item
        $existingRating = Rating::where('user_id', '=', Auth::user()?->id(), 'and')
            ->where('item_id', '=', $item->id, 'and')
            ->first();

        if ($existingRating) {
            return back()->with('error', 'Ya has calificado este item. Puedes editar tu calificación.');
        }

        Rating::create([
            'user_id' => Auth::user()?->id(),
            'item_id' => $item->id,
            'rating' => $validated['rating'],
            'review' => $validated['review'] ?? null,
        ]);

        return back()->with('success', 'Calificación agregada exitosamente.');
    }

    /**
     * Update the specified rating.
     */
    public function update(Request $request, Rating $rating)
    {
        // Verificar que el usuario sea el dueño
        if ($rating->user_id !== Auth::user()?->id()) {
            abort(403, 'No puedes editar esta calificación.');
        }

        $this->authorize('edit own ratings');

        $validated = $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'review' => 'nullable|string|max:1000',
        ]);

        $rating->fill($validated)->save();

        return back()->with('success', 'Calificación actualizada exitosamente.');
    }

    /**
     * Remove the specified rating.
     */
    public function destroy(Rating $rating)
    {
        // Verificar que el usuario sea el dueño o tenga permiso de moderador
        if ($rating->user_id !== Auth::user()?->id() && !Auth::user()?->can('moderate ratings')) {
            abort(403, 'No puedes eliminar esta calificación.');
        }

        $this->authorize('delete own ratings');

        Rating::destroy($rating->id);

        return back()->with('success', 'Calificación eliminada exitosamente.');
    }

    /**
     * Display user's ratings.
     */
    public function myRatings()
    {
        $ratings = Rating::where('user_id', '=', Auth::user()?->id(), 'and')
            ->with(['item' => function ($query) {
                $query->with(['uploader', 'categories']);
            }])
            ->latest()
            ->paginate(20);

        return Inertia::render('User/Ratings/MyRatings', [
            'ratings' => $ratings,
        ]);
    }
}
