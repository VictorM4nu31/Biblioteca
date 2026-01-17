<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Category;
use App\Models\Tag;
use Illuminate\Http\Request;
use Inertia\Inertia;

class SearchController extends Controller
{
    /**
     * Perform a search across items.
     */
    public function index(Request $request)
    {
        $query = Item::with(['uploader', 'categories', 'tags']);

        // Búsqueda por texto
        if ($request->filled('q')) {
            $searchTerm = $request->q;
            $query->where(function ($q) use ($searchTerm) {
                $q->where('title', 'like', '%' . $searchTerm . '%')
                  ->orWhere('author', 'like', '%' . $searchTerm . '%')
                  ->orWhere('description', 'like', '%' . $searchTerm . '%')
                  ->orWhere('publisher', 'like', '%' . $searchTerm . '%')
                  ->orWhere('isbn', 'like', '%' . $searchTerm . '%');
            });
        }

        // Filtro por tipo
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        // Filtro por categoría
        if ($request->filled('category')) {
            $query->whereHas('categories', function ($q) use ($request) {
                $q->where('categories.id', $request->category);
            });
        }

        // Filtro por tag
        if ($request->filled('tag')) {
            $query->whereHas('tags', function ($q) use ($request) {
                $q->where('tags.id', $request->tag);
            });
        }

        // Filtro por idioma
        if ($request->filled('language')) {
            $query->where('language', $request->language);
        }

        // Filtro por año
        if ($request->filled('year')) {
            $query->where('publication_year', $request->year);
        }

        // Ordenamiento
        $sortBy = $request->get('sort', 'latest');
        switch ($sortBy) {
            case 'title':
                $query->orderBy('title');
                break;
            case 'author':
                $query->orderBy('author');
                break;
            case 'oldest':
                $query->oldest();
                break;
            case 'rating':
                $query->withAvg('ratings', 'rating')
                      ->orderByDesc('ratings_avg_rating');
                break;
            case 'latest':
            default:
                $query->latest();
                break;
        }

        $items = $query->paginate(20)->withQueryString();

        return Inertia::render('Search/Index', [
            'items' => $items,
            'categories' => Category::orderBy('name', 'asc')->get(),
            'tags' => Tag::orderBy('name', 'asc')->get(),
            'filters' => $request->only(['q', 'type', 'category', 'tag', 'language', 'year', 'sort']),
        ]);
    }

    /**
     * Advanced search page.
     */
    public function advanced()
    {
        return Inertia::render('Search/Advanced', [
            'categories' => Category::orderBy('name', 'asc')->get(),
            'tags' => Tag::orderBy('name', 'asc')->get(),
            'languages' => Item::distinct()->pluck('language'),
            'years' => Item::distinct()->orderByDesc('publication_year')->pluck('publication_year'),
        ]);
    }
}
