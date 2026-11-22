<form method="POST" action="{{ route('responsable.reservations.store') }}">
    @csrf
    <input type="hidden" name="salle_id" value="{{ $salle->id }}">
    <input type="hidden" name="date"
           value="{{ $criteres['date'] ?? request('date') }}">
    <input type="hidden" name="heure_debut" value="{{ $debut }}">
    <input type="hidden" name="heure_fin" value="{{ $fin }}">
    <input type="hidden" name="effectif"
           value="{{ $criteres['effectif'] ?? request('effectif') }}">
    <button class="bg-green-500 text-white font-semibold px-5 py-2 rounded-full">
        Réserver
    </button>
</form>