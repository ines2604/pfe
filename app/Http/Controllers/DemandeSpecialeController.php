<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\Request;
use App\Models\DemandeSpeciale;
use App\Models\Hotel;
use Illuminate\Support\Facades\Log;
use App\Mail\DemandeSpecialeMail;

class DemandeSpecialeController extends Controller
{
    public function submit(Request $request){

        $request->validate([
            'hotel_nom' => 'required|string',
            'nom' => 'required|string',
            'email' => 'required|email',
            'tel' => 'required|string',
            'sujet' => 'required|string',
            'message' => 'required|string',
        ]);

        $hotel = Hotel::where('nom', $request->hotel_nom)->first();
        $hotel_id = $hotel->_id;
        $hotel_email=$hotel->email;
        try{
            $demandeSpeciale = DemandeSpeciale::create([
                'nom' => $request->nom,
                'email' => $request->email,
                'telephone' => $request->tel,
                'sujet' => $request->sujet,
                'message' => $request->message,
                'hotel_id' => $hotel_id,
            ]);
            $demandeSpeciale->save();
            Mail::to($hotel_email)->send(new DemandeSpecialeMail($demandeSpeciale));
            return response()->json(['message' => 'Votre message a été envoyé avec succès.'], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Erreur lors de l\'envoi du message: ' . $e->getMessage()], 500);
        }
    }
}
