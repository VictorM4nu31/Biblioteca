<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Tag extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'slug'];

    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($tag) {
            if (empty($tag->slug)) {
                $tag->slug = Str::slug($tag->name);
            }
        });
    }

    public function items()
    {
        return $this->belongsToMany(Item::class);
    }

    // Obtener o crear tag
    public static function findOrCreateByName($name)
    {
        $slug = Str::slug($name);
        
        return static::firstOrCreate(
            ['slug' => $slug],
            ['name' => $name]
        );
    }

    // Scope para tags populares
    public function scopePopular($query, $limit = 10)
    {
        return $query->withCount('items')
                    ->orderBy('items_count', 'desc')
                    ->limit($limit);
    }
}
