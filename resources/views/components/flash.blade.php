@if (session('status'))
    <div class="rounded bg-green-100 text-green-800 px-4 py-3">
        {{ session('status') }}
    </div>
@endif
 
@if (session('error'))
    <div class="rounded bg-red-100 text-red-800 px-4 py-3">
        {{ session('error') }}
    </div>
@endif
