<?php

namespace App\Http\Controllers\Responsable;

use App\Exceptions\SalleIndisponibleException;
use App\Http\Controllers\Controller;
use App\Http\Requests\ReservationRequest;
use App\Models\Reservation;
use App\Services\ReservationService;

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
    public function index()
    {
        session()->reflash();

        return redirect()->route('responsable.recherche');
    }

    // annuler() et historique() : Phase 9
}