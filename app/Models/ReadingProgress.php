<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ReadingProgress extends Model
{
    use HasFactory;

    protected $table = 'reading_progress';

    protected $fillable = [
        'user_id',
        'item_id',
        'current_page',
        'total_pages',
        'status'
    ];

    protected $casts = [
        'current_page' => 'integer',
        'total_pages' => 'integer',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function item()
    {
        return $this->belongsTo(Item::class);
    }

    // Calcular porcentaje de progreso
    public function getProgressPercentageAttribute()
    {
        if ($this->total_pages == 0) {
            return 0;
        }
        
        return round(($this->current_page / $this->total_pages) * 100, 2);
    }

    // Verificar si está completado
    public function getIsCompletedAttribute()
    {
        return $this->status === 'completed' || 
               $this->current_page >= $this->total_pages;
    }

    // Actualizar progreso
    public function updateProgress($currentPage)
    {
        $this->current_page = $currentPage;
        
        if ($currentPage >= $this->total_pages) {
            $this->status = 'completed';
        } elseif ($currentPage > 0 && $this->status === 'wishlist') {
            $this->status = 'reading';
        }
        
        $this->save();
        
        return $this;
    }

    // Scopes
    public function scopeReading($query)
    {
        return $query->where('status', 'reading');
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    public function scopeWishlist($query)
    {
        return $query->where('status', 'wishlist');
    }

    public function scopeForUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }
}
