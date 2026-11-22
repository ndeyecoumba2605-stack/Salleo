<?php
 
namespace App\Http\Controllers\Admin;
 
use App\Http\Controllers\Controller;
use App\Models\Reservation;
use App\Models\Salle;
use Illuminate\Http\Request;
 
class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $date = $request->query('date', now()->toDateString());
 
        $salles = Salle::active()
            ->with(['reservations' => fn ($q) => $q
                ->where('date', $date)
                ->whereIn('statut', Reservation::STATUTS_BLOQUANTS)
                ->with('user')
                ->orderBy('heure_debut')])
            ->orderBy('batiment')->orderBy('numero')
            ->get();
 
        return view('admin.dashboard', [
            'date' => $date,
            'salles' => $salles,
            'enAttente' => Reservation::where('statut', Reservation::EN_ATTENTE)
                ->count(),
            'aVenir' => Reservation::where('statut', Reservation::CONFIRMEE)
                ->where('date', '>=', now()->toDateString())->count(),
        ]);
    }
}
