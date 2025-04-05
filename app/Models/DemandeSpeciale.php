<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Casts\ObjectId;
use MongoDB\Laravel\Eloquent\Model as Eloquent;

class DemandeSpeciale extends Eloquent
{
    protected $connection = 'mongodb';
    protected $collection = 'Demande_speciale';
    protected $table = "Demande_speciale";

    protected $fillable = [
        'nom',
        'email',
        'telephone',
        'sujet',
        'message',
        'hotel_id'
    ];
    protected $casts = [
        'hotel_id'=> ObjectId::class,
    ];
}
