<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800">Historique</h2>
    </x-slot>
 
    <div class="py-8 max-w-5xl mx-auto sm:px-6 lg:px-8">
        <table class="w-full bg-white shadow rounded text-sm">
            <thead class="bg-gray-100 text-left">
                <tr>
                    <th class="p-3">Date</th><th>Horaire</th>
                    <th>Salle</th><th>Statut</th>
                </tr>
            </thead>
            <tbody>
            @foreach ($reservations as $r)
                <tr class="border-t">
                    <td class="p-3">{{ $r->date->format('d/m/Y') }}</td>
                    <td>{{ substr($r->heure_debut, 0, 5) }} -
                        {{ substr($r->heure_fin, 0, 5) }}</td>
                    <td>{{ $r->salle->nom_complet }}</td>
                    <td>{{ $r->libelleStatut() }}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
 
        <div class="mt-4">{{ $reservations->links() }}</div>
    </div>
</x-app-layout>
