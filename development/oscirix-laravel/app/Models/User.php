<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens; // <-- Para que el usuario pueda generar tokens de acceso
use Illuminate\Support\Str;        // <-- Para poder generar los UUID aleatorios

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasApiTokens, HasFactory, Notifiable;
    protected $table = 'users';
    protected $keyType = 'string';
    public $incrementing = false;
    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'id', 
        'clinic_id',
        'full_name',
        'email',
        'password_hash',
        'role',
        'avatar_url',
        'is_active',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password_hash',
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
        ];
    }
    // Genera el UUID automáticamente antes de registrar un usuario
    protected static function booted()
    {
        static::creating(function ($user) {
            if (empty($user->id)) {
                $user->id = (string) \Illuminate\Support\Str::uuid();
            }
        });
    }

    // Le enseña a Laravel que use password_hash para validar los inicios de sesión
    public function getAuthPassword()
    {
        return $this->password_hash;
    }
}
