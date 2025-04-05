<?php

namespace App\Http\Controllers;

use App\Models\Offre;
use MongoDB\BSON\ObjectId;
use App\Models\Hotel;
use Illuminate\Http\Request;
use PhpParser\Node\Expr\Cast\Object_;

class OffreController extends Controller
{
    public function getOffresActif(){
        $offre = Offre::where('status', 'actif')->get();
        if ( $offre->isEmpty()){
            return response()->json(['message' => 'Aucune offre trouvée'], 404);
        }
        $offresAvecNomHotel = $offre->map(function($offre){
            $hotel = Hotel::find($offre->hotel_id);
            if( $hotel){
                $offre->hotel_nom = $hotel->nom;
            }else{
                $offre->hotel_nom = null;
            }
            return $offre;
        });

        return response()->json($offresAvecNomHotel);
    }

    public function getOffreById($id){
        $offre = Offre::find($id);
        if(!$offre){
            return response()->json(['message' => 'Aucune offre trouvée'], 404);
        }
        return response()->json($offre);
    }

    public function getOffresByHotelName(Request $request, $hotel_nom){
        $hotel = Hotel::where('nom', $hotel_nom)->first();

        if (!$hotel) {
            return response()->json(['message' => 'Hôtel non trouvé'], 404);
        }

        $query =  Offre::where('hotel_id', new ObjectId($hotel->_id))->where('status', 'actif');

        $offres = $query->get();

        return response()->json($offres);
    }
}