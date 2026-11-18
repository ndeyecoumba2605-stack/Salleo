<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800">Gestion des salles</h2>
    </x-slot>
 
    <div class="py-8 max-w-5xl mx-auto sm:px-6 lg:px-8 space-y-4">
        <x-flash />
 
        <a href="{{ route('admin.salles.create') }}"
           class="inline-block bg-teal-700 text-white px-4 py-2 rounded">
            Ajouter une salle
        </a>
 
        <table class="w-full bg-white shadow rounded text-sm">
            <thead class="bg-gray-100 text-left">
                <tr>
                    <th class="p-3">Salle</th><th>Capacité</th>
                    <th>Équipements</th><th>Statut</th><th></th>
                </tr>
            </thead>
            <tbody>
            @foreach ($salles as $salle)
                <tr class="border-t">
                    <td class="p-3">{{ $salle->nom_complet }}</td>
                    <td>{{ $salle->capacite }}</td>
                    <td>{{ implode(', ', $salle->equipements ?? []) }}</td>
                    <td>{{ $salle->active ? 'Active' : 'Désactivée' }}</td>
                    <td class="text-right p-3 space-x-2">
                        <a href="{{ route('admin.salles.edit', $salle) }}"
                           class="text-teal-700">Modifier</a>
                        <form method="POST" class="inline"
                              action="{{ route('admin.salles.destroy', $salle) }}"
                              onsubmit="return confirm('Supprimer cette salle ?')">
                            @csrf @method('DELETE')
                            <button class="text-red-600">Supprimer</button>
                        </form>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
 
        {{ $salles->links() }}
    </div>
</x-app-layout>
