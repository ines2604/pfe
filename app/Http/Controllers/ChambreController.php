<?php

namespace App\Http\Controllers;

use App\Models\Chambre;
use App\Models\Hotel;

use MongoDB\BSON\ObjectId;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;

class ChambreController extends Controller
{
    public function getChambres(){
        $chambres = Chambre::all();
        if ($chambres->isEmpty()) {
            return response()->json(['message' => 'Aucune chambre trouvée'], 404);
        }
        $chambresAvecNomHotel = $chambres->map(function ($chambre) {
            $hotel = Hotel::find($chambre->hotel_id); 
            if ($hotel) {
                $chambre->hotel_nom = $hotel->nom;
            } else {
                $chambre->hotel_nom = null;
            }

            return $chambre;
        });

        return response()->json($chambresAvecNomHotel);
    }
    public function getChambreById($id){
        $chambre = Chambre::find($id);
        if(!$chambre){
            return response()->json(['message' => 'Aucune chambre trouvée'], 404);
        }
        return response()->json($chambre);
    }

    public function getChambresByHotelName(Request $request, $hotel_nom) {
        $hotel = Hotel::where('nom', $hotel_nom)->first();
    
        if (!$hotel) {
            return response()->json(['message' => 'Hôtel non trouvé'], 404);
        }
    
        $query = Chambre::where('hotel_id', new ObjectId($hotel->_id));
        
        $rooms = $query->get();
    
        return response()->json($rooms);
    }
}