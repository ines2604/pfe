<?php

namespace App\Models;

use DateTime;
use MongoDB\Laravel\Eloquent\Casts\ObjectId;
use MongoDB\Laravel\Eloquent\Model as Eloquent;

class Offre extends Eloquent
{
    protected $connection = "mongodb";
    protected $collection = "offre";
    protected $table = "offre";
    protected $fillable = [
        'nom',
        'description',
        'prix',
        'status',
        'programme',
        'images',
        'hotel_id',
        'ancien_prix',
        'reduction_pourcentage'
    ];

    protected $casts = [ 
        'hotel_id' => ObjectId::class
    ];
}
