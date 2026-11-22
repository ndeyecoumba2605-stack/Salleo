<?php
 
namespace App\Http\Controllers\Admin;
 
use App\Http\Controllers\Controller;
use App\Models\Reservation;
use App\Models\Salle;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
 
class StatistiqueController extends Controller
{
    public function index(Request $request)
    {
        $du = $request->query('du', now()->subDays(30)->toDateString());
        $au = $request->query('au', now()->toDateString());
        $statuts = [Reservation::CONFIRMEE, Reservation::HONOREE];
 
        $base = fn () => Reservation::whereBetween('date', [$du, $au])
            ->whereIn('statut', $statuts);
 
        // Taux d'occupation = minutes réservées / minutes disponibles
        $minutesReservees = $base()->get(['heure_debut', 'heure_fin'])->sum(
            fn ($r) => Carbon::parse($r->heure_debut)
                ->diffInMinutes(Carbon::parse($r->heure_fin))
        );
        $jours = Carbon::parse($du)->diffInDays(Carbon::parse($au)) + 1;
        $minutesOuverture = Carbon::parse(config('salleo.heure_ouverture'))
            ->diffInMinutes(Carbon::parse(config('salleo.heure_fermeture')));
        $minutesDisponibles = Salle::active()->count() * $jours * $minutesOuverture;
 
        $taux = $minutesDisponibles > 0
            ? round($minutesReservees / $minutesDisponibles * 100, 1)
            : 0;
 
        $topSalles = Salle::withCount(['reservations as total' => fn ($q) => $q
                ->whereBetween('date', [$du, $au])
                ->whereIn('statut', $statuts)])
            ->orderByDesc('total')->limit(5)->get();
 
        $topCreneaux = $base()
            ->select('heure_debut', DB::raw('count(*) as total'))
            ->groupBy('heure_debut')
            ->orderByDesc('total')->limit(5)->get();
 
        return view('admin.statistiques', compact(
            'du', 'au', 'taux', 'topSalles', 'topCreneaux'
        ));
    }
}
