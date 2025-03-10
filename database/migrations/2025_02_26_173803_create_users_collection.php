<?php

use Illuminate\Support\Facades\Schema;
use MongoDB\Laravel\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersCollection extends Migration
{
    protected $connection = 'mongodb';

    public function up()
    {
        Schema::create('users', function (Blueprint $collection) {
            $collection->string('nom');
            $collection->string('prenom');
            $collection->string('email')->unique();
            $collection->string('tel');
            $collection->date('naissance');
            $collection->string('password');
            $collection->string('pays');
            $collection->timestamps();
        });
    }

    public function down()
    {
        Schema::drop('users');
    }
}