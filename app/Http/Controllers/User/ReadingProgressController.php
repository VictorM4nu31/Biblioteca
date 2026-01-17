<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Item;
use App\Models\ReadingProgress;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;

class ReadingProgressController extends Controller
{
    /**
     * Display user's reading progress.
     */
    public function index()
    {
        $progress = ReadingProgress::where('user_id', '=', Auth::user()?->id(), 'and')
            ->with(['item' => function ($query) {
                $query->with(['uploader', 'categories']);
            }])
            ->latest()
            ->get()
            ->groupBy('status');

        return Inertia::render('User/ReadingProgress/Index', [
            'reading' => $progress->get('reading', collect()),
            'completed' => $progress->get('completed', collect()),
            'wishlist' => $progress->get('wishlist', collect()),
        ]);
    }

    /**
     * Store or update reading progress.
     */
    public function store(Request $request, Item $item)
    {
        $validated = $request->validate([
            'current_page' => 'required|integer|min:0',
            'total_pages' => 'nullable|integer|min:1',
            'status' => 'required|in:reading,completed,wishlist',
        ]);

        $progress = ReadingProgress::updateOrCreate(
            [
                'user_id' => Auth::user()?->id(),
                'item_id' => $item->id,
            ],
            [
                'current_page' => $validated['current_page'],
                'total_pages' => $validated['total_pages'] ?? $item->pages,
                'status' => $validated['status'],
            ]
        );

        return back()->with('success', 'Progreso actualizado exitosamente.');
    }

    /**
     * Update reading progress.
     */
    public function update(Request $request, ReadingProgress $progress)
    {
        // Verificar que el usuario sea el dueño
        if ($progress->user_id !== Auth::user()?->id()) {
            abort(403, 'No puedes editar este progreso.');
        }

        $validated = $request->validate([
            'current_page' => 'required|integer|min:0',
            'total_pages' => 'nullable|integer|min:1',
            'status' => 'required|in:reading,completed,wishlist',
        ]);

        $progress->fill($validated)->save();

        return back()->with('success', 'Progreso actualizado exitosamente.');
    }

    /**
     * Remove reading progress.
     */
    public function destroy(ReadingProgress $progress)
    {
        // Verificar que el usuario sea el dueño
        if ($progress->user_id !== Auth::user()?->id()) {
            abort(403, 'No puedes eliminar este progreso.');
        }

        ReadingProgress::destroy($progress->id);

        return back()->with('success', 'Progreso eliminado exitosamente.');
    }

    /**
     * Add item to wishlist.
     */
    public function addToWishlist(Item $item)
    {
        ReadingProgress::updateOrCreate(
            [
                'user_id' => Auth::user()?->id(),
                'item_id' => $item->id,
            ],
            [
                'current_page' => 0,
                'total_pages' => $item->pages,
                'status' => 'wishlist',
            ]
        );

        return back()->with('success', 'Item agregado a tu lista de deseos.');
    }

    /**
     * Mark item as completed.
     */
    public function markAsCompleted(Item $item)
    {
        $progress = ReadingProgress::where('user_id', '=', Auth::user()?->id(), 'and')
            ->where('item_id', '=', $item->id, 'and')
            ->first();

        if ($progress) {
            $progress->fill([
                'current_page' => $progress->total_pages ?? $item->pages,
                'status' => 'completed',
            ])->save();
        } else {
            ReadingProgress::create([
                'user_id' => Auth::user()?->id(),
                'item_id' => $item->id,
                'current_page' => $item->pages ?? 0,
                'total_pages' => $item->pages,
                'status' => 'completed',
            ]);
        }

        return back()->with('success', 'Item marcado como completado.');
    }
}
