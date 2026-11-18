<?php
 
namespace App\Http\Requests;
 
use App\Models\Salle;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
 
class SalleRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // l'accès est déjà protégé par le middleware role:admin
    }
 
    public function rules(): array
    {
        $id = $this->route('salle')?->id;
 
        return [
            'batiment' => ['required', 'string', 'max:50'],
            'numero' => [
                'required', 'string', 'max:20',
                Rule::unique('salles', 'numero')
                    ->where('batiment', $this->input('batiment'))
                    ->ignore($id),
            ],
            'capacite' => ['required', 'integer', 'min:1', 'max:500'],
            'equipements' => ['nullable', 'array'],
            'equipements.*' => ['string', Rule::in(Salle::EQUIPEMENTS)],
        ];
    }
}
