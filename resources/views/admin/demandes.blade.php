<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800">Demandes en attente</h2>
    </x-slot>
 
    <div class="py-8 max-w-5xl mx-auto sm:px-6 lg:px-8 space-y-4">
        <x-flash />
 
        @forelse ($demandes as $d)
            <div class="bg-white p-5 rounded shadow flex justify-between
                        items-center">
                <div>
                    <p class="font-semibold">
                        Salle {{ $d->salle->nom_complet }}
                        ({{ $d->salle->capacite }} places)
                    </p>
                    <p class="text-sm text-gray-600">
                        {{ $d->user->nom_classe }} ({{ $d->effectif }} étudiants) -
                        {{ $d->date->format('d/m/Y') }},
                        {{ substr($d->heure_debut, 0, 5) }} à
                        {{ substr($d->heure_fin, 0, 5) }}
                    </p>
                </div>
                <div class="flex gap-2">
                    <form method="POST"
                          action="{{ route('admin.demandes.approuver', $d) }}">
                        @csrf
                        <button class="bg-teal-700 text-white px-3 py-1 rounded">
                            Approuver
                        </button>
                    </form>
                    <form method="POST"
                          action="{{ route('admin.demandes.refuser', $d) }}">
                        @csrf
                        <button class="bg-red-600 text-white px-3 py-1 rounded">
                            Refuser
                        </button>
                    </form>
                </div>
            </div>
        @empty
            <p class="text-gray-600">Aucune demande en attente.</p>
        @endforelse
    </div>
</x-app-layout>
