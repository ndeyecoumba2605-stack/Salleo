@php $coches = old('equipements', $selection ?? []); @endphp

<fieldset class="equipements">
    <legend>Équipements</legend>
    <div class="liste-equipements">
        @foreach (\App\Models\Salle::EQUIPEMENTS as $nom)
            <label class="case-eq">
                <input type="checkbox" name="equipements[]" value="{{ $nom }}"
                       @checked(in_array($nom, $coches, true))>
                {{ $nom }}
            </label>
        @endforeach
    </div>
    @error('equipements.*') <div class="erreur">{{ $message }}</div> @enderror
</fieldset>