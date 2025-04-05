<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AvisClientController;
use App\Http\Controllers\chaine_hoteliereController;
use App\Http\Controllers\chambreController;
use App\Http\Controllers\DemandeSpecialeController;
use App\Http\Controllers\HotelController;
use App\Http\Controllers\offreController;
use App\Http\Controllers\PasswordResetController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ServiceController;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/verify-otp', [AuthController::class, 'verifyOtp']);
Route::post('/resend-otp', [AuthController::class, 'resendOtp']);
Route::post('forgot-password', [PasswordResetController::class, 'sendResetLinkEmail']);
Route::post('reset-password/{token}', [PasswordResetController::class, 'reset']);

Route::get('/hotels', [HotelController::class, 'getHotels']);
Route::get('/chambres',[ChambreController::class , 'getChambres']);
Route::get('/offres', [OffreController::class , 'getOffresActif']);
Route::get('/avis',[AvisClientController::class , 'getAvis']);
Route::get('/chambre/{id}', [ChambreController::class, 'getChambreById']);
Route::get('/offre/{id}',[OffreController::class, 'getOffreById']);

Route::get('/chaine_hoteliere',[chaine_hoteliereController::class, 'getInformations']);

Route::get('/services',[ServiceController::class , 'getServices']);
Route::get('/service/{id}', [ServiceController::class, 'getServiceById']);
Route::post('/demande_speciale', [DemandeSpecialeController::class , 'submit']);
Route::get('{hotel_nom}/contactus', [HotelController::class , 'getHotelByNom']);
Route::get('/hotels/{hotel_nom}/chambres', [ChambreController::class, 'getChambresByHotelName']);
Route::get('/hotels/{hote_nom}/offres', [OffreController::class, 'getOffresByHotelName']);
Route::get('/hotels/{hotel_nom}/services', [ServiceController::class , 'getServicesbyHotelName']);
Route::get('/hotels/{hotel_nom}/avis_clients' , [AvisClientController::class , 'getAvisByHotelName']);

Route::get('hotels/{hotel_nom}' , [HotelController::class , 'getHotelByNom']);


Route::middleware('auth:api')->group(function () {
    Route::get('/user',[ProfileController::class,'getUser']);
    Route::patch('/update-profile', [ProfileController::class, 'update']);
    Route::patch('/update-password', [ProfileController::class, 'update_password']);
    Route::post('/logout', [AuthController::class, 'logout']);
});