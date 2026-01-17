<?php

use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\RatingModerationController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Library\CategoryController;
use App\Http\Controllers\Library\ItemController;
use App\Http\Controllers\Library\TagController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\User\CollectionController;
use App\Http\Controllers\User\DashboardController as UserDashboardController;
use App\Http\Controllers\User\RatingController;
use App\Http\Controllers\User\ReadingProgressController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Laravel\Fortify\Features;

// Home / Landing
// Página principal: redirige al login de Fortify
Route::get('/', function () {
    return redirect()->route('login');
})->name('home');

// Página pública opcional (si quieres una landing distinta al login)
Route::get('/welcome', [HomeController::class, 'welcome'])->name('welcome');

// Dashboard
Route::get('dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// Search routes
Route::get('/search', [SearchController::class, 'index'])->name('search.index');
Route::get('/search/advanced', [SearchController::class, 'advanced'])->name('search.advanced');

// Library routes
Route::prefix('library')->name('library.')->group(function () {
    // Items
    Route::resource('items', ItemController::class);
    Route::get('items/{item}/download', [ItemController::class, 'download'])->name('items.download');
    
    // Categories
    Route::resource('categories', CategoryController::class);
    
    // Tags
    Route::get('tags', [TagController::class, 'index'])->name('tags.index');
    Route::post('tags', [TagController::class, 'store'])->name('tags.store');
    Route::put('tags/{tag}', [TagController::class, 'update'])->name('tags.update');
    Route::delete('tags/{tag}', [TagController::class, 'destroy'])->name('tags.destroy');
});

// User routes
Route::prefix('user')->name('user.')->middleware(['auth', 'verified'])->group(function () {
    // Dashboard
    Route::get('dashboard', [UserDashboardController::class, 'index'])->name('dashboard');
    
    // Collections
    Route::get('collections/my', [CollectionController::class, 'myCollections'])->name('collections.my');
    Route::post('collections/{collection}/items', [CollectionController::class, 'addItem'])->name('collections.add-item');
    Route::delete('collections/{collection}/items/{item}', [CollectionController::class, 'removeItem'])->name('collections.remove-item');
    Route::resource('collections', CollectionController::class);
    
    // Ratings
    Route::post('items/{item}/ratings', [RatingController::class, 'store'])->name('ratings.store');
    Route::put('ratings/{rating}', [RatingController::class, 'update'])->name('ratings.update');
    Route::delete('ratings/{rating}', [RatingController::class, 'destroy'])->name('ratings.destroy');
    Route::get('ratings', [RatingController::class, 'myRatings'])->name('ratings.my');
    
    // Reading Progress
    Route::get('reading-progress', [ReadingProgressController::class, 'index'])->name('reading-progress.index');
    Route::post('items/{item}/reading-progress', [ReadingProgressController::class, 'store'])->name('reading-progress.store');
    Route::put('reading-progress/{progress}', [ReadingProgressController::class, 'update'])->name('reading-progress.update');
    Route::delete('reading-progress/{progress}', [ReadingProgressController::class, 'destroy'])->name('reading-progress.destroy');
    Route::post('items/{item}/wishlist', [ReadingProgressController::class, 'addToWishlist'])->name('reading-progress.wishlist');
    Route::post('items/{item}/completed', [ReadingProgressController::class, 'markAsCompleted'])->name('reading-progress.completed');
});

// Admin routes
Route::prefix('admin')->name('admin.')->middleware(['auth', 'verified'])->group(function () {
    // Dashboard
    Route::get('dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
    
    // Users
    Route::post('users/{user}/roles', [UserController::class, 'assignRole'])->name('users.assign-role');
    Route::delete('users/{user}/roles', [UserController::class, 'removeRole'])->name('users.remove-role');
    Route::resource('users', UserController::class);
    
    // Rating Moderation
    Route::get('ratings', [RatingModerationController::class, 'index'])->name('ratings.index');
    Route::delete('ratings/{rating}', [RatingModerationController::class, 'destroy'])->name('ratings.destroy');
});

require __DIR__.'/settings.php';
