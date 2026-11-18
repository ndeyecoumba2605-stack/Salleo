<?php
 
namespace App\Http\Controllers\Admin;
 
use App\Http\Controllers\Controller;
use App\Http\Requests\SalleRequest;
use App\Models\Salle;
use Illuminate\Validation\Rule;
 
class SalleController extends Controller
{
    public function index()
    {
        $salles = Salle::orderBy('numero')->paginate(15);
        return view('admin.salles.index', compact('salles'));
    }
 
    public function create()
    {
        return view('admin.salles.create');
    }
 
    public function store(SalleRequest $request)
    {
        $data = $request->validate([
            'numero'   => ['required', 'string', 'max:100', 'unique:salles,numero'],
            'type'      => ['required', 'in:salle,amphi,labo,reunion,examen'],
            'capacite'  => ['required', 'integer', 'min:1'],
            'batiment'  => ['required', 'string', 'max:100'],
            'etage'     => ['required', 'integer'],
            'accessible_pmr' => ['boolean'],
            'equipements'   => ['nullable', 'array'],
            'equipements.*' => ['string', Rule::in(Salle::EQUIPEMENTS)],
        ]);

        $data['accessible_pmr'] = $request->boolean('accessible_pmr');
        $data['actif']          = true; // une nouvelle salle est réservable

        Salle::create($data);
        $data['equipements'] = $data['equipements'] ?? [];

        return redirect()->route('admin.salles.index')->with('status', 'Salle ajoutée.');
        
    }
 
    public function edit(Salle $salle)
    {
        return view('admin.salles.edit', compact('salle'));
    }
 
    public function update(SalleRequest $request, Salle $salle)
    {
            $data = $request->validate([
                'numero' => ['required', 'string', 'max:100', Rule::unique('salles', 'numero')->ignore($salle->id)],
                'type'     => ['required', 'in:salle,amphi,labo,reunion,examen'],
                'capacite' => ['required', 'integer', 'min:1'],
                'batiment' => ['required', 'string', 'max:100'],
                'etage'    => ['required', 'integer'],
                'equipements'   => ['nullable', 'array'],
                'equipements.*' => ['string', Rule::in(Salle::EQUIPEMENTS)], 
        ]);

        // Une case décochée n'est pas envoyée par le navigateur : on la lit explicitement.
        $data['accessible_pmr'] = $request->boolean('accessible_pmr');
        $data['actif']          = $request->boolean('actif');

        // Empêche de désactiver une salle qui a encore des réservations à venir.
        // Adapte 'fin' au nom réel de ta colonne de fin de créneau.
        if (! $data['actif'] && $salle->reservations()->where('fin', '>', now())->exists()) {
            return back()
                ->withErrors(['actif' => 'Cette salle a des réservations à venir : annulez-les avant de la désactiver.'])
                ->withInput();
        }

        $salle->update($data);
        $data['equipements'] = $data['equipements'] ?? [];

        return redirect()->route('admin.salles.index')->with('status', 'Salle modifiée.');
    }
 
    public function destroy(Salle $salle)
    {
        if ($salle->reservations()->exists()) {
            $salle->update(['actif' => false]);
            return back()->with('status', 'Salle désactivée : elle a un historique de réservations.');
        }

        $salle->delete();
        return back()->with('status', 'Salle supprimée.');
    }
 
    private function donnees(SalleRequest $request): array
    {
        return [
            ...$request->validated(),
            'equipements' => $request->input('equipements', []),
            'active' => $request->boolean('active'),
            'validation_requise' => $request->boolean('validation_requise'),
        ];
    }
}
