<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use MongoDB\BSON\ObjectId;
use App\Models\Hotel;
use App\Models\Service;

class ServiceController extends Controller
{
    public function getServices(){
        $service = Service::all()->where('disponibilite' , true);
        if ( $service->isEmpty()){
            return response()->json(['message' => 'Aucune service trouvée'], 404);
        }
        $servicesAvecNomHotel = $service->map(function($service){
            $hotel = Hotel::find($service->hotel_id);
            if( $hotel){
                $service->hotel_nom = $hotel->nom;
            }else{
                $service->hotel_nom = null;
            }
            return $service;
        });

        return response()->json($servicesAvecNomHotel);
    }

    public function getServiceById($id){
        $service= Service::find($id);
        if(!$service){
            return response()->json(['message'=> ' Aucune service trouvée'],404);
        }
        return response()->json($service);
    }

    public function getServicesByHotelName(Request $request, $hotel_nom) {
        $hotel = Hotel::where('nom', $hotel_nom)->first();
    
        if (!$hotel) {
            return response()->json(['message' => 'Hôtel non trouvé'], 404);
        }
    
        $query = Service::where('hotel_id', new ObjectId($hotel->_id));
        
        $services = $query->get();
    
        return response()->json($services);
    }
}
