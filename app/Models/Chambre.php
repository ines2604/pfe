<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Casts\ObjectId;
use MongoDB\Laravel\Eloquent\Model as Eloquent;

class Chambre extends Eloquent
{
    protected $connection = "mongodb";
    protected $collection = "chambre";
    protected $table = "chambre";
    protected $fillable = [
        'hotel_id',
        'nom',
        'surface',
        'description',
        'capacite',
        'nb_lits',
        'prix',
        'images',
        'points_forts'
    ];
    protected $casts = [
        'hotel_id' => ObjectId::class,
    ];
}
