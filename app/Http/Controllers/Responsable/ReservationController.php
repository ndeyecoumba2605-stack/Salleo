<?php

namespace App\Http\Controllers\Responsable;

use App\Exceptions\SalleIndisponibleException;
use App\Http\Controllers\Controller;
use App\Http\Requests\ReservationRequest;
use App\Models\Reservation;
use App\Services\ReservationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use App\Exceptions\AnnulationRefuseeException;

class ReservationController extends Controller
{
    public function store(ReservationRequest $request,
        ReservationService $service)
    {
        try {
            $reservation = $service->reserver($request->user(),
                $request->validated());
        } catch (SalleIndisponibleException $e) {
            return redirect()->route('responsable.recherche')
                ->with('error', $e->getMessage());
        }

        $message = $reservation->statut === Reservation::EN_ATTENTE
            ? "Demande envoyée : elle doit être validée par l'administration."
            : 'Réservation confirmée.';

        return redirect()->route('responsable.reservations.index')
            ->with('status', $message);
    }

    // TEMPORAIRE (à remplacer en phase 9) : évite une page blanche
    public function index(Request $request)
    {
        $reservations = $request->user()->reservations()
            ->with('salle')
            ->whereIn('statut', Reservation::STATUTS_BLOQUANTS)
            ->where('date', '>=', now()->toDateString())
            ->orderBy('date')->orderBy('heure_debut')
            ->get();
    
        return view('responsable.reservations', compact('reservations'));
    }
    
    public function historique(Request $request)
    {
        $reservations = $request->user()->reservations()
            ->with('salle')
            ->orderByDesc('date')->orderByDesc('heure_debut')
            ->paginate(15);
    
        return view('responsable.historique', compact('reservations'));
    }
    
    public function annuler(Request $request, Reservation $reservation,
                            ReservationService $service)
    {
        Gate::authorize('annuler', $reservation);
    
        try {
            $service->annuler($reservation);
        } catch (AnnulationRefuseeException $e) {
            return back()->with('error', $e->getMessage());
        }
    
        return back()->with('status', 'Réservation annulée.');
    }

}