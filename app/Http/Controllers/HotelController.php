<?php

namespace App\Http\Controllers;
use App\Models\Hotel;

class HotelController extends Controller
{
    public function getHotels()
    {
        try {
            $hotels = Hotel::all();
            return response()->json($hotels, 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Erreur interne du serveur'], 500);
        }
    }

    public function getHotelByNom($hotel_nom){
        $hotel = Hotel::where('nom', $hotel_nom)->first();
        if(!$hotel){
            return response()->json(['error' => 'Hotel non trouvée'], 404);
        }
        return response()->json($hotel);
    }
}
