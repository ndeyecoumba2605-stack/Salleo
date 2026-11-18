@php($s = $salle ?? null)
 
<div class="grid gap-4 md:grid-cols-3">
    <div>
        <x-input-label for="batiment" value="Bâtiment" />
        <x-text-input id="batiment" name="batiment" class="w-full"
            :value="old('batiment', $s?->batiment)" required />
        <x-input-error :messages="$errors->get('batiment')" />
    </div>
    <div>
        <x-input-label for="numero" value="Numéro" />
        <x-text-input id="numero" name="numero" class="w-full"
            :value="old('numero', $s?->numero)" required />
        <x-input-error :messages="$errors->get('numero')" />
    </div>
    <div>
        <x-input-label for="capacite" value="Capacité" />
        <x-text-input id="capacite" name="capacite" type="number" class="w-full"
            :value="old('capacite', $s?->capacite)" required />
        <x-input-error :messages="$errors->get('capacite')" />
    </div>
</div>
 
<div class="mt-4">
    <span class="block font-medium text-sm text-gray-700">Équipements</span>
    @foreach (\App\Models\Salle::EQUIPEMENTS as $eq)
        <label class="inline-flex items-center mr-4">
            <input type="checkbox" name="equipements[]" value="{{ $eq }}"
                @checked(in_array($eq, old('equipements', $s?->equipements ?? [])))>
            <span class="ml-1">{{ $eq }}</span>
        </label>
    @endforeach
</div>
 
<div class="mt-4 space-x-6">
    <label><input type="checkbox" name="active" value="1"
        @checked(old('active', $s?->active ?? true))> Salle active</label>
    <label><input type="checkbox" name="validation_requise" value="1"
        @checked(old('validation_requise', $s?->validation_requise ?? false))>
        Réservation soumise à validation</label>
</div>
