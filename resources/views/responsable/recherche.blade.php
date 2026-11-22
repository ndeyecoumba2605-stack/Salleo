<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800">Rechercher une salle</h2>
    </x-slot>

    <div class="py-8 max-w-5xl mx-auto sm:px-6 lg:px-8 space-y-6">
        <form method="GET" class="bg-white p-6 rounded shadow
                                  grid gap-4 md:grid-cols-4">
            <div>
                <x-input-label for="date" value="Date" />
                <x-text-input id="date" name="date" type="date" class="w-full"
                    :value="old('date', $criteres['date'] ?? now()->toDateString())"
                    required />
                <x-input-error :messages="$errors->get('date')" />
            </div>
            <div>
                <x-input-label for="heure_debut" value="Début" />
                <x-text-input id="heure_debut" name="heure_debut" type="time"
                    class="w-full"
                    :value="old('heure_debut', $criteres['heure_debut'] ?? '10:00')"
                    required />
                <x-input-error :messages="$errors->get('heure_debut')" />
            </div>
            <div>
                <x-input-label for="heure_fin" value="Fin" />
                <x-text-input id="heure_fin" name="heure_fin" type="time"
                    class="w-full"
                    :value="old('heure_fin', $criteres['heure_fin'] ?? '12:00')"
                    required />
                <x-input-error :messages="$errors->get('heure_fin')" />
            </div>
            <div>
                <x-input-label for="effectif" value="Effectif" />
                <x-text-input id="effectif" name="effectif" type="number"
                    class="w-full" min="1"
                    :value="old('effectif', $criteres['effectif']
                        ?? auth()->user()->effectif)" required />
                <x-input-error :messages="$errors->get('effectif')" />
            </div>
            <div>
                <x-input-label for="batiment" value="Bâtiment (facultatif)" />
                <select id="batiment" name="batiment"
                        class="w-full border-gray-300 rounded">
                    <option value="">Tous</option>
                    @foreach ($batiments as $b)
                        <option value="{{ $b }}"
                            @selected(($criteres['batiment'] ?? '') === $b)>
                            {{ $b }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="md:col-span-2">
                <span class="block text-sm font-medium text-gray-700">
                    Équipements
                </span>
                @foreach ($equipements as $eq)
                    <label class="inline-flex items-center mr-3 text-sm">
                        <input type="checkbox" name="equipements[]"
                               value="{{ $eq }}"
                            @checked(in_array($eq, $criteres['equipements'] ?? []))>
                        <span class="ml-1">{{ $eq }}</span>
                    </label>
                @endforeach
            </div>
            <div class="flex items-end">
                <x-primary-button>Rechercher</x-primary-button>
            </div>
        </form>

        <x-flash />

        @if ($resultat)
            @include('responsable._resultats')
        @endif
    </div>
</x-app-layout>