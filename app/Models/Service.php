<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Casts\ObjectId;
use MongoDB\Laravel\Eloquent\Model as Eloquent;

class Service extends Eloquent
{
    protected $connection = "mongodb";
    protected $collection = "service";
    protected $table = "service";
    protected $fillable = [
        'hotel_id',
        'nom',
        'description',
        'points_forts',
        'categorie',
        'prix',
        'images'
    ];
    protected $casts = [
        'hotel_id' => ObjectId::class,
    ];
}
