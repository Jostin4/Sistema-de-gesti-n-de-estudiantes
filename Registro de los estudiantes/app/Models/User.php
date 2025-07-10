<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
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
        ];
    }

    /**
     * Verifica si el usuario tiene un rol específico
     */
    public function hasRole($role)
    {
        return $this->role === $role;
    }

    /**
     * Relación con el modelo Profesor
     */
    public function profesor()
    {
        return $this->hasOne(Profesor::class);
    }

    /**
     * Relación con el modelo Estudiante
     */
    public function estudiante()
    {
        return $this->hasOne(estudiante::class);
    }

    /**
     * Boot del modelo para configurar eliminación en cascada
     */
    protected static function boot()
    {
        parent::boot();
        
        static::deleting(function ($user) {
            if ($user->profesor) {
                $user->profesor->delete();
            }
        });
    }
}
