<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model as Eloquent;

class PersonalAccessToken extends Eloquent
{
    protected $connection = 'mongodb';
    protected $fillable = ['name', 'tokenable_type', 'tokenable_id', 'token'];

}