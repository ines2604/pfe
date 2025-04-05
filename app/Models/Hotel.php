<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Casts\ObjectId;
use MongoDB\Laravel\Eloquent\Model as Eloquent;



class Hotel extends Eloquent
{
    protected $connection = "mongodb";
    protected $collection = 'hotel';
    protected $table = 'hotel';
    protected $fillable = [
        'nom',
        'adresse',
        'nb_etoiles',
        'telephone',
        'email',
        'facebook',
        'instagram',
        'galerie',
        'logo',
        'description',
        'services'
    ];
}