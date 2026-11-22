<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800">Statistiques</h2>
    </x-slot>
 
    <div class="py-8 max-w-5xl mx-auto sm:px-6 lg:px-8 space-y-6">
        <form method="GET" class="flex items-end gap-3">
            <div>
                <x-input-label for="du" value="Du" />
                <x-text-input id="du" name="du" type="date" :value="$du" />
            </div>
            <div>
                <x-input-label for="au" value="Au" />
                <x-text-input id="au" name="au" type="date" :value="$au" />
            </div>
            <x-primary-button>Afficher</x-primary-button>
        </form>
 
        <div class="bg-white p-6 rounded shadow">
            <p class="text-sm text-gray-500">Taux d'occupation des salles</p>
            <p class="text-4xl font-bold text-teal-700">{{ $taux }} %</p>
            <div class="mt-3 h-3 bg-gray-200 rounded">
                <div class="h-3 bg-teal-600 rounded"
                     style="width: {{ min($taux, 100) }}%"></div>
            </div>
        </div>
 
        <div class="grid gap-6 md:grid-cols-2">
            <div class="bg-white p-6 rounded shadow">
                <h3 class="font-semibold mb-3">Salles les plus demandées</h3>
                @foreach ($topSalles as $s)
                    <p class="flex justify-between">
                        <span>{{ $s->nom_complet }}</span>
                        <span class="font-medium">{{ $s->total }}</span>
                    </p>
                @endforeach
            </div>
            <div class="bg-white p-6 rounded shadow">
                <h3 class="font-semibold mb-3">Créneaux les plus chargés</h3>
                @foreach ($topCreneaux as $c)
                    <p class="flex justify-between">
                        <span>{{ substr($c->heure_debut, 0, 5) }}</span>
                        <span class="font-medium">{{ $c->total }}</span>
                    </p>
                @endforeach
            </div>
        </div>
    </div>
</x-app-layout>
