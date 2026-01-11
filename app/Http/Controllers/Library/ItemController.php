<?php

namespace App\Http\Controllers\Library;

use App\Http\Controllers\Controller;
use App\Models\Item;
use App\Models\Category;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class ItemController extends Controller
{
    /**
     * Display a listing of items.
     */
    public function index(Request $request)
    {
        $query = Item::with(['uploader', 'categories', 'tags'])
            ->latest();

        // Filtros
        if ($request->filled('search')) {
            $query->where('title', 'like', '%' . $request->search . '%')
                  ->orWhere('author', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        if ($request->filled('category')) {
            $query->whereHas('categories', function ($q) use ($request) {
                $q->where('categories.id', $request->category);
            });
        }

        $items = $query->paginate(20);

        return Inertia::render('Library/Items/Index', [
            'items' => $items,
            'filters' => $request->only(['search', 'type', 'category']),
        ]);
    }

    /**
     * Show the form for creating a new item.
     */
    public function create()
    {
        $this->authorize('create items');

        return Inertia::render('Library/Items/Create', [
            'categories' => Category::all(),
            'tags' => Tag::all(),
        ]);
    }

    /**
     * Store a newly created item.
     */
    public function store(\App\Http\Requests\Library\Item\StoreItemRequest $request)
    {
        $validated = $request->validated();

        // Subir archivo
        $filePath = $request->file('file')->store('library/files', 'public');
        $fileSize = $request->file('file')->getSize();

        // Subir imagen de portada si existe
        $coverImagePath = null;
        if ($request->hasFile('cover_image')) {
            $coverImagePath = $request->file('cover_image')->store('library/covers', 'public');
        }

        // Crear item
        $item = Item::create([
            'title' => $validated['title'],
            'author' => $validated['author'] ?? null,
            'publisher' => $validated['publisher'] ?? null,
            'publication_year' => $validated['publication_year'] ?? null,
            'isbn' => $validated['isbn'] ?? null,
            'description' => $validated['description'] ?? null,
            'type' => $validated['type'],
            'language' => $validated['language'],
            'pages' => $validated['pages'] ?? null,
            'cover_image' => $coverImagePath,
            'file_path' => $filePath,
            'file_size' => $fileSize,
            'uploaded_by' => auth()->id(),
        ]);

        // Asociar categorías y tags
        if (!empty($validated['categories'])) {
            $item->categories()->attach($validated['categories']);
        }

        if (!empty($validated['tags'])) {
            $item->tags()->attach($validated['tags']);
        }

        return redirect()->route('library.items.show', $item)
            ->with('success', 'Item creado exitosamente.');
    }

    /**
     * Display the specified item.
     */
    public function show(Item $item)
    {
        $item->load(['uploader', 'categories', 'tags', 'ratings.user']);

        return Inertia::render('Library/Items/Show', [
            'item' => $item,
            'averageRating' => $item->ratings()->avg('rating'),
            'totalRatings' => $item->ratings()->count(),
        ]);
    }

    /**
     * Show the form for editing the specified item.
     */
    public function edit(Item $item)
    {
        $this->authorize('edit items');

        $item->load(['categories', 'tags']);

        return Inertia::render('Library/Items/Edit', [
            'item' => $item,
            'categories' => Category::all(),
            'tags' => Tag::all(),
        ]);
    }

    /**
     * Update the specified item.
     */
    public function update(\App\Http\Requests\Library\Item\UpdateItemRequest $request, Item $item)
    {
        $validated = $request->validated();

        // Actualizar archivo si se subió uno nuevo
        if ($request->hasFile('file')) {
            // Eliminar archivo anterior
            Storage::disk('public')->delete($item->file_path);
            
            $filePath = $request->file('file')->store('library/files', 'public');
            $fileSize = $request->file('file')->getSize();
            
            $item->file_path = $filePath;
            $item->file_size = $fileSize;
        }

        // Actualizar imagen de portada si se subió una nueva
        if ($request->hasFile('cover_image')) {
            // Eliminar imagen anterior si existe
            if ($item->cover_image) {
                Storage::disk('public')->delete($item->cover_image);
            }
            
            $item->cover_image = $request->file('cover_image')->store('library/covers', 'public');
        }

        // Actualizar datos
        $item->update([
            'title' => $validated['title'],
            'author' => $validated['author'] ?? null,
            'publisher' => $validated['publisher'] ?? null,
            'publication_year' => $validated['publication_year'] ?? null,
            'isbn' => $validated['isbn'] ?? null,
            'description' => $validated['description'] ?? null,
            'type' => $validated['type'],
            'language' => $validated['language'],
            'pages' => $validated['pages'] ?? null,
        ]);

        // Sincronizar categorías y tags
        if (isset($validated['categories'])) {
            $item->categories()->sync($validated['categories']);
        }

        if (isset($validated['tags'])) {
            $item->tags()->sync($validated['tags']);
        }

        return redirect()->route('library.items.show', $item)
            ->with('success', 'Item actualizado exitosamente.');
    }

    /**
     * Remove the specified item.
     */
    public function destroy(Item $item)
    {
        $this->authorize('delete items');

        // Eliminar archivos
        Storage::disk('public')->delete($item->file_path);
        if ($item->cover_image) {
            Storage::disk('public')->delete($item->cover_image);
        }

        $item->delete();

        return redirect()->route('library.items.index')
            ->with('success', 'Item eliminado exitosamente.');
    }

    /**
     * Download the item file.
     */
    public function download(Item $item)
    {
        $this->authorize('view items');

        return Storage::disk('public')->download($item->file_path, $item->title);
    }
}
