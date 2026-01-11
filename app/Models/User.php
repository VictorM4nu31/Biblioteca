<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, TwoFactorAuthenticatable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'two_factor_secret',
        'two_factor_recovery_codes',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'two_factor_confirmed_at' => 'datetime',
        ];
    }

    // Relaciones
    public function uploadedItems()
    {
        return $this->hasMany(Item::class, 'uploaded_by');
    }

    public function collections()
    {
        return $this->hasMany(Collection::class);
    }

    public function ratings()
    {
        return $this->hasMany(Rating::class);
    }

    public function readingProgress()
    {
        return $this->hasMany(ReadingProgress::class);
    }

    // Verificar si es admin
    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    // Obtener items en lectura
    public function currentlyReading()
    {
        return $this->readingProgress()
                    ->where('status', 'reading')
                    ->with('item')
                    ->get()
                    ->pluck('item');
    }

    // Obtener wishlist
    public function wishlist()
    {
        return $this->readingProgress()
                    ->where('status', 'wishlist')
                    ->with('item')
                    ->get()
                    ->pluck('item');
    }
}
