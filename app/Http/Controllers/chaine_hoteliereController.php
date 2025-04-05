<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Chaine_Hoteliere;

class chaine_hoteliereController extends Controller
{
    public function getInformations(){
        $chaine = Chaine_Hoteliere::first();
        if(!$chaine){
            return response()->json(['message' => 'Aucune chaine trouvée'], 404);
        }
        return response()->json($chaine);

    }
}
