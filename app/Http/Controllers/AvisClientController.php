<?php

namespace App\Http\Controllers;

use App\Models\Avis_Client;
use App\Models\Client;
use App\Models\Hotel;
use Illuminate\Http\Request;
use MongoDB\BSON\ObjectId;

class AvisClientController extends Controller
{
    public function getAvis()
    {
        $avis = Avis_Client::all();
        if( $avis->isEmpty()){
            return response()->json(['message' => 'Aucune avis trouvée'], 404);
        }
        $avisInfo = $avis->map(function($avis){
            $hotel = Hotel::find($avis->hotel_id);
            if ($hotel){
                $avis->hotel_nom = $hotel->nom;
            }else{
                $avis->hotel_nom = null;
            }
            $client = Client::find($avis->client_id);
            if ($client){
                $avis->client_nom = $client->nom;
                $avis->client_prenom = $client->prenom;
            }else{
                $avis->client_nom = null;
                $avis->client_prenom = null;
            }
            return $avis;
        });

        return response()->json($avisInfo);
    }

    public function getAvisByHotelName(Request $request, $hotel_nom){
        $hotel = Hotel::where(column: 'nom', $hotel_nom)->first();
    
        if (!$hotel) {
            return response()->json(['message' => 'Hôtel non trouvé'], 404);
        }
    
        $query = Avis_Client::where('hotel_id', new ObjectId($hotel->_id))->get();

        $avisInfo = $query->map(function($query){
            $client = Client::find($query->client_id);
            if ($client){
                $query->client_nom = $client->nom;
                $query->client_prenom = $client->prenom;
            }else{
                $query->client_nom = null;
                $query->client_prenom = null;
            }
            return $query;
        });

        return response()->json($avisInfo);
    }
}