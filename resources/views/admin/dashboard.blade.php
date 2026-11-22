<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800">Tableau de bord</h2>
    </x-slot>
 
    <div class="py-8 max-w-6xl mx-auto sm:px-6 lg:px-8 space-y-6">
        <div class="grid gap-4 md:grid-cols-3">
            <div class="bg-white p-5 rounded shadow">
                <p class="text-sm text-gray-500">Salles actives</p>
                <p class="text-3xl font-bold">{{ $salles->count() }}</p>
            </div>
            <div class="bg-white p-5 rounded shadow">
                <p class="text-sm text-gray-500">Réservations à venir</p>
                <p class="text-3xl font-bold">{{ $aVenir }}</p>
            </div>
            <a href="{{ route('admin.demandes') }}"
               class="bg-white p-5 rounded shadow">
                <p class="text-sm text-gray-500">Demandes en attente</p>
                <p class="text-3xl font-bold text-teal-700">{{ $enAttente }}</p>
            </a>
        </div>
 
        <form method="GET" class="flex items-end gap-3">
            <div>
                <x-input-label for="date" value="Planning du" />
                <x-text-input id="date" name="date" type="date"
                              :value="$date" />
            </div>
            <x-primary-button>Afficher</x-primary-button>
        </form>
 
        <table class="w-full bg-white shadow rounded text-sm">
            <thead class="bg-gray-100 text-left">
                <tr><th class="p-3">Salle</th><th>Occupation du jour</th></tr>
            </thead>
            <tbody>
            @foreach ($salles as $salle)
                <tr class="border-t align-top">
                    <td class="p-3">
                        {{ $salle->nom_complet }}
                        <span class="text-gray-500">({{ $salle->capacite }})</span>
                    </td>
                    <td class="p-3">
                        @forelse ($salle->reservations as $r)
                            <p>
                                {{ substr($r->heure_debut, 0, 5) }} -
                                {{ substr($r->heure_fin, 0, 5) }} :
                                {{ $r->user->nom_classe }}
                                ({{ $r->libelleStatut() }})
                            </p>
                        @empty
                            <span class="text-green-700">Libre toute la journée</span>
                        @endforelse
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
</x-app-layout>
