<?php

namespace App\Models;

use DateTime;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Auth\CanResetPassword;
use MongoDB\Laravel\Eloquent\Model as Eloquent;


class Client extends Eloquent implements Authenticatable, CanResetPassword
{
    use \Illuminate\Auth\Authenticatable, \Illuminate\Auth\Passwords\CanResetPassword;

    protected $connection = 'mongodb'; 
    protected $collection = 'client'; 
    protected $table = 'client';


    protected $fillable = [
        'nom',
        'prenom',
        'email',
        'tel',
        'naissance',
        'password',
        'pays',
        'api_token',
        'otp', 
        'otp_expires_at',
    ];

    // les champs masquées dans les réponses JSON
    protected $hidden = [
        'password',
        'api_token', 
        'otp',
        'otp_expires_at',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'naissance' => 'datetime',
        'otp_expires_at' => 'datetime',
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

    public function generateOtp()
    {
        $this->otp = \Illuminate\Support\Str::random(6); 
        $this->otp_expires_at = now()->addMinutes(10); 
        $this->save();
        return $this->otp;
    }

    public function validateOtp($otp)
    {
        return $this->otp === $otp && now()->lt($this->otp_expires_at);
    }

    public function markEmailAsVerified()
    {
        $this->email_verified_at = now();
        $this->otp = null; 
        $this->otp_expires_at = null; 
        $this->save();
    }
}