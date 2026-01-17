<?php

namespace App\Http\Controllers\Library;

use App\Http\Controllers\Controller;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Inertia\Inertia;

class TagController extends Controller
{
    /**
     * Display a listing of tags.
     */
    public function index()
    {
        $tags = Tag::withCount('items')
            ->orderBy('name', 'asc')
            ->get();

        return Inertia::render('Library/Tags/Index', [
            'tags' => $tags,
        ]);
    }

    /**
     * Store a newly created tag.
     */
    public function store(Request $request)
    {
        $this->authorize('manage categories'); // Usar mismo permiso que categorías

        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:tags,name',
            'slug' => 'nullable|string|max:255|unique:tags,slug',
        ]);

        if (empty($validated['slug'])) {
            $validated['slug'] = Str::slug($validated['name']);
        }

        $tag = Tag::create($validated);

        return back()->with('success', 'Etiqueta creada exitosamente.');
    }

    /**
     * Update the specified tag.
     */
    public function update(Request $request, Tag $tag)
    {
        $this->authorize('manage categories');

        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:tags,name,' . $tag->id,
            'slug' => 'nullable|string|max:255|unique:tags,slug,' . $tag->id,
        ]);

        if (empty($validated['slug'])) {
            $validated['slug'] = Str::slug($validated['name']);
        }

        $tag->fill($validated)->save();

        return back()->with('success', 'Etiqueta actualizada exitosamente.');
    }

    /**
     * Remove the specified tag.
     */
    public function destroy(Tag $tag)
    {
        $this->authorize('manage categories');

        Tag::destroy($tag->id);

        return back()->with('success', 'Etiqueta eliminada exitosamente.');
    }
}
