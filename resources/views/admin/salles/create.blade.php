<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl">Ajouter une salle</h2>
    </x-slot>
    <div class="py-8 max-w-3xl mx-auto sm:px-6 lg:px-8">
        <form method="POST" action="{{ route('admin.salles.store') }}"
              class="bg-white p-6 rounded shadow">
            @csrf
            @include('admin.salles._form')
            <x-primary-button class="mt-6">Enregistrer</x-primary-button>
        </form>
    </div>
</x-app-layout>
