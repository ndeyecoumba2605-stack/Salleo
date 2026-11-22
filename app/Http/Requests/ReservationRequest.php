<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class ReservationRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
{
        return true; // route déjà protégée par role:responsable
}

    public function rules(): array
    {
        return [
            'salle_id' => ['required', 'integer', 'exists:salles,id'],
            'date' => ['required', 'date', 'after_or_equal:today'],
            'heure_debut' => ['required', 'date_format:H:i'],
            'heure_fin' => ['required', 'date_format:H:i', 'after:heure_debut'],
            'effectif' => ['required', 'integer', 'min:1', 'max:500'],
        ];
}
}
