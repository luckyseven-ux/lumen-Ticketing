<?php

namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Lumen\Auth\Authorizable;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Model implements AuthenticatableContract, AuthorizableContract, JWTSubject
{
    use Authenticatable, Authorizable, HasFactory;

    protected $table = 'users';
    protected $fillable = ['name', 'email', 'password', 'role'];

    public function getJWTIdentifier()
    {
        return $this->getKey(); // Mengembalikan ID pengguna
    }

    public function tickets()
    {
        return $this->hasMany(Ticket::class, 'user_id'); // Relasi ke model Ticket
    }

    public function getJWTCustomClaims()
    {
        return []; // Klaim kustom (opsional)
    }

    public $timestamps = true;
    protected $hidden = ['password'];
}
