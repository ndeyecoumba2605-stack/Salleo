<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255',
                        'unique:'.User::class],
            'nom_classe' => ['required', 'string', 'max:100'],
            'effectif' => ['required', 'integer', 'min:1', 'max:500'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],

        ]);

        $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'nom_classe' => $request->nom_classe,
                'effectif' => $request->effectif,
                'role' => 'responsable',   // jamais lu depuis le formulaire !
                'password' => Hash::make($request->password),

        ]);

        event(new Registered($user));

        Auth::login($user);

        return redirect(route('dashboard', absolute: false));
    }
}
