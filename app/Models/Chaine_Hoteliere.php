<?php

namespace App\Models;

use DateTime;
use MongoDB\Laravel\Eloquent\Model as Eloquent;


class Chaine_Hoteliere extends Eloquent
{
    protected $connection = "mongodb";
    protected $collectio = "chaine_hoteliere";
    protected $table= "chaine_hoteliere";
    protected $fillable = [
        'logo',
        'nom',
        'facebook',
        'instagram',
        'nb_hotels',
        'nb_chambres',
        'nb_employes',
        'nb_pays',
        'date_foendation',
        'histoire',
        'missions',
        'services',
        'engagements'
    ];

    protected $casts = [
        'date_fondation' => 'datetime'
    ];
}
