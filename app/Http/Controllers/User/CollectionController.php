<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Collection;
use App\Models\Item;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;

class CollectionController extends Controller
{
    /**
     * Display a listing of collections.
     */
    public function index(Request $request)
    {
        $query = Collection::with('user')
            ->withCount('items');

        // Mostrar solo colecciones del usuario o públicas
        if (!Auth::user()?->can('view collections')) {
            $query->where(function ($q) {
                $q->where('user_id', '=', Auth::user()?->id(), 'and')
                  ->orWhere('is_public', '=', true);
            });
        }

        $collections = $query->latest()->paginate(20);

        return Inertia::render('User/Collections/Index', [
            'collections' => $collections,
        ]);
    }

    /**
     * Display user's own collections.
     */
    public function myCollections()
    {
        $collections = Collection::where('user_id', '=', Auth::user()?->id(), 'and')
            ->withCount('items')
            ->latest()
            ->get();

        return Inertia::render('User/Collections/MyCollections', [
            'collections' => $collections,
        ]);
    }

    /**
     * Show the form for creating a new collection.
     */
    public function create()
    {
        return Inertia::render('User/Collections/Create');
    }

    /**
     * Store a newly created collection.
     */
    public function store(\App\Http\Requests\User\Collection\StoreCollectionRequest $request)
    {
        $validated = $request->validated();

        $collection = Collection::create([
            'user_id' => Auth::user()?->id(),
            'name' => $validated['name'],
            'description' => $validated['description'] ?? null,
            'is_public' => $validated['is_public'] ?? false,
        ]);

        return redirect()->route('user.collections.show', $collection)
            ->with('success', 'Colección creada exitosamente.');
    }

    /**
     * Display the specified collection.
     */
    public function show(Collection $collection)
    {
        // Verificar permisos
        if ($collection->user_id !== Auth::user()?->id() && !$collection->is_public) {
            abort(403, 'No tienes permiso para ver esta colección.');
        }

        $collection->load(['user', 'items' => function ($query) {
            $query->with(['uploader', 'categories', 'tags'])
                  ->withPivot('added_at')
                  ->latest('collection_item.added_at');
        }]);

        return Inertia::render('User/Collections/Show', [
            'collection' => $collection,
            'isOwner' => $collection->user_id === Auth::user()?->id(),
        ]);
    }

    /**
     * Show the form for editing the specified collection.
     */
    public function edit(Collection $collection)
    {
        $this->authorize('update', $collection);

        return Inertia::render('User/Collections/Edit', [
            'collection' => $collection,
        ]);
    }

    /**
     * Update the specified collection.
     */
    public function update(\App\Http\Requests\User\Collection\UpdateCollectionRequest $request, Collection $collection)
    {
        $this->authorize('update', $collection);

        $validated = $request->validated();

        $collection->fill($validated)->save();

        return redirect()->route('user.collections.show', $collection)
            ->with('success', 'Colección actualizada exitosamente.');
    }

    /**
     * Remove the specified collection.
     */
    public function destroy(Collection $collection)
    {
        $this->authorize('delete', $collection);

        Collection::destroy($collection->id);

        return redirect()->route('user.collections.index')
            ->with('success', 'Colección eliminada exitosamente.');
    }

    /**
     * Add an item to the collection.
     */
    public function addItem(Request $request, Collection $collection)
    {
        $this->authorize('update', $collection);

        $validated = $request->validate([
            'item_id' => 'required|exists:items,id',
        ]);

        // Verificar si el item ya está en la colección
        if ($collection->hasItem($validated['item_id'])) {
            return back()->with('error', 'El item ya está en esta colección.');
        }

        $collection->items()->attach($validated['item_id'], [
            'added_at' => now(),
        ]);

        return back()->with('success', 'Item agregado a la colección.');
    }

    /**
     * Remove an item from the collection.
     */
    public function removeItem(Collection $collection, Item $item)
    {
        $this->authorize('update', $collection);

        $collection->items()->detach($item->id);

        return back()->with('success', 'Item removido de la colección.');
    }
}
