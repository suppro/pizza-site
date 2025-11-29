<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;

class User extends Model implements AuthenticatableContract
{
    use Authenticatable;

    protected $table = 'User';
    public $timestamps = false;

    protected $fillable = [
        'name', 'phone', 'email', 'address', 'login', 'password_hash', 'role_id'
    ];

    protected $hidden = ['password_hash'];

    // Критически важно — Laravel будет использовать login как имя пользователя
    public function getAuthIdentifierName()
    {
        return 'login';
    }

    // Laravel будет брать готовый хеш из password_hash (не будет хешировать заново)
    public function getAuthPassword()
    {
        return $this->password_hash;
    }

    // Связь с ролью
    public function role()
    {
        return $this->belongsTo(Role::class, 'role_id');
    }

    public function isAdmin()
    {
        return $this->role_id == 1;
    }
}