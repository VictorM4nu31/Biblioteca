<?php

namespace App\Http\Controllers\Library;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Inertia\Inertia;

class CategoryController extends Controller
{
    /**
     * Display a listing of categories.
     */
    public function index()
    {
        $categories = Category::withCount('items')
            ->orderBy('name')
            ->get();

        return Inertia::render('Library/Categories/Index', [
            'categories' => $categories,
        ]);
    }

    /**
     * Show the form for creating a new category.
     */
    public function create()
    {
        $this->authorize('manage categories');

        return Inertia::render('Library/Categories/Create');
    }

    /**
     * Store a newly created category.
     */
    public function store(\App\Http\Requests\Library\Category\StoreCategoryRequest $request)
    {
        $validated = $request->validated();

        // Generar slug si no se proporciona
        if (empty($validated['slug'])) {
            $validated['slug'] = Str::slug($validated['name']);
        }

        $category = Category::create($validated);

        return redirect()->route('library.categories.index')
            ->with('success', 'Categoría creada exitosamente.');
    }

    /**
     * Display the specified category.
     */
    public function show(Category $category)
    {
        $category->load(['items' => function ($query) {
            $query->with(['uploader', 'tags'])->latest()->paginate(20);
        }]);

        return Inertia::render('Library/Categories/Show', [
            'category' => $category,
        ]);
    }

    /**
     * Show the form for editing the specified category.
     */
    public function edit(Category $category)
    {
        $this->authorize('manage categories');

        return Inertia::render('Library/Categories/Edit', [
            'category' => $category,
        ]);
    }

    /**
     * Update the specified category.
     */
    public function update(\App\Http\Requests\Library\Category\UpdateCategoryRequest $request, Category $category)
    {
        $validated = $request->validated();

        // Generar slug si no se proporciona
        if (empty($validated['slug'])) {
            $validated['slug'] = Str::slug($validated['name']);
        }

        $category->update($validated);

        return redirect()->route('library.categories.index')
            ->with('success', 'Categoría actualizada exitosamente.');
    }

    /**
     * Remove the specified category.
     */
    public function destroy(Category $category)
    {
        $this->authorize('manage categories');

        // Verificar si tiene items asociados
        if ($category->items()->count() > 0) {
            return back()->with('error', 'No se puede eliminar una categoría con items asociados.');
        }

        $category->delete();

        return redirect()->route('library.categories.index')
            ->with('success', 'Categoría eliminada exitosamente.');
    }
}
