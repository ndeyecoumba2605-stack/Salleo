<x-guest-layout>
    <div class="text-center">
        <h1 class="text-3xl font-bold text-teal-700">SALLEO</h1>
        <p class="mt-2 text-gray-600">
            Réserver une salle de cours, sans tourner en rond.
        </p>
        <div class="mt-6 space-x-3">
            <a href="{{ route('login') }}"
               class="bg-teal-700 text-white px-4 py-2 rounded">Connexion</a>
            <a href="{{ route('register') }}"
               class="border border-teal-700 text-teal-700 px-4 py-2 rounded">
                Inscription
            </a>
        </div>
    </div>
</x-guest-layout>
