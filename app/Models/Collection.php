<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Collection extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'description',
        'is_public'
    ];

    protected $casts = [
        'is_public' => 'boolean'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->belongsToMany(Item::class)
                    ->withPivot('added_at')
                    ->orderByPivot('added_at', 'desc');
    }

    // Verificar si un item está en la colección
    public function hasItem($itemId)
    {
        return $this->items()->where('item_id', $itemId)->exists();
    }

    // Scope para colecciones públicas
    public function scopePublic($query)
    {
        return $query->where('is_public', true);
    }

    // Scope para colecciones del usuario
    public function scopeForUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }
}
