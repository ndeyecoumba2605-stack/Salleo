<?php
 
namespace App\Http\Controllers\Admin;
 
use App\Http\Controllers\Controller;
use App\Models\Reservation;
use App\Services\ReservationService;
 
class DemandeController extends Controller
{
    public function index()
    {
        $demandes = Reservation::with(['salle', 'user'])
            ->where('statut', Reservation::EN_ATTENTE)
            ->orderBy('date')->orderBy('heure_debut')
            ->get();
 
        return view('admin.demandes', compact('demandes'));
    }
 
    public function approuver(Reservation $reservation, ReservationService $service)
    {
        abort_unless($reservation->statut === Reservation::EN_ATTENTE, 409);
        $service->confirmer($reservation);
 
        return back()->with('status', 'Demande approuvée.');
    }
 
    public function refuser(Reservation $reservation, ReservationService $service)
    {
        abort_unless($reservation->statut === Reservation::EN_ATTENTE, 409);
        $service->refuser($reservation);
 
        return back()->with('status', 'Demande refusée.');
    }
}
