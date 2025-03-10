<?php

namespace App\Models;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Auth\CanResetPassword;
use MongoDB\Laravel\Eloquent\Model as Eloquent;

class User extends Eloquent implements Authenticatable, CanResetPassword
{
    use \Illuminate\Auth\Authenticatable, \Illuminate\Auth\Passwords\CanResetPassword;

    protected $connection = 'mongodb'; 
    protected $collection = 'users'; 

    protected $fillable = [
        'nom',
        'prenom',
        'email',
        'tel',
        'naissance',
        'password',
        'pays',
        'api_token',
    ];

    // les champs masquées dans les réponses JSON
    protected $hidden = [
        'password',
        'api_token', 
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    // Remplace remember_token par api_token
    public function getRememberToken()
    {
        return $this->api_token;
    }

    public function setRememberToken($value)
    {
        $this->api_token = $value;
    }

    public function getRememberTokenName()
    {
        return 'api_token';
    }
    public function generateToken()
    {
        $this->api_token = \Illuminate\Support\Str::random(60);
        $this->save();
        return $this->api_token;
    }

    // Supprimer le token (logout)
    public function removeToken()
    {
        $this->api_token = null;
        $this->save();
    }
}