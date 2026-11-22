<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800">Mes réservations</h2>
    </x-slot>
 
    <div class="py-8 max-w-5xl mx-auto sm:px-6 lg:px-8 space-y-4">
        <x-flash />
 
        @forelse ($reservations as $r)
            <div class="bg-white p-5 rounded shadow flex justify-between
                        items-center">
                <div>
                    <p class="font-semibold">Salle {{ $r->salle->nom_complet }}</p>
                    <p class="text-sm text-gray-600">
                        {{ $r->date->format('d/m/Y') }} -
                        {{ substr($r->heure_debut, 0, 5) }} à
                        {{ substr($r->heure_fin, 0, 5) }} -
                        {{ $r->libelleStatut() }}
                    </p>
                </div>
                <form method="POST"
                      action="{{ route('responsable.reservations.annuler', $r) }}"
                      onsubmit="return confirm('Annuler cette réservation ?')">
                    @csrf @method('DELETE')
                    <button class="text-red-600 font-medium">Annuler</button>
                </form>
            </div>
        @empty
            <p class="text-gray-600">Aucune réservation à venir.</p>
        @endforelse
    </div>
</x-app-layout>
