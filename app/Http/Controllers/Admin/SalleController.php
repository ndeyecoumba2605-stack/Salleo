<?php
 
namespace App\Http\Controllers\Admin;
 
use App\Http\Controllers\Controller;
use App\Http\Requests\SalleRequest;
use App\Models\Salle;
 
class SalleController extends Controller
{
    public function index()
    {
        $salles = Salle::orderBy('batiment')->orderBy('numero')->paginate(15);
 
        return view('admin.salles.index', compact('salles'));
    }
 
    public function create()
    {
        return view('admin.salles.create');
    }
 
    public function store(SalleRequest $request)
    {
        Salle::create($this->donnees($request));
 
        return redirect()->route('admin.salles.index')
            ->with('status', 'Salle ajoutée.');
    }
 
    public function edit(Salle $salle)
    {
        return view('admin.salles.edit', compact('salle'));
    }
 
    public function update(SalleRequest $request, Salle $salle)
    {
        $salle->update($this->donnees($request));
 
        return redirect()->route('admin.salles.index')
            ->with('status', 'Salle mise à jour.');
    }
 
    public function destroy(Salle $salle)
    {
        if ($salle->reservations()->exists()) {
            return back()->with('error',
                'Cette salle a un historique de réservations : désactivez-la.');
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
