<?php

namespace App\Models;

use DateTime;
use MongoDB\Laravel\Eloquent\Casts\ObjectId;
use MongoDB\Laravel\Eloquent\Model as Eloquent;


class Avis_Client extends Eloquent
{
    protected $connection = "mongodb";
    protected $collection = "avis_client";
    protected $table = "avis_client";
    protected $fillable = [
        'client_id',
        'hotel_id',
        'note',
        'commentaire',
        'date_publication'
    ];

    protected $casts = [
        'hotel_id' => ObjectId::class,
        'client_id'=>ObjectId::class,
        'date_publication' => 'datetime'
    ];
}
