<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateDireccionRequest extends FormRequest
{
    /**
     * Indica si esta petición está autorizada.
     *
     * Como la ruta ya estará protegida con auth:sanctum,
     * aquí devolvemos true.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Reglas de validación para actualizar una dirección.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'nombreDireccion' => ['required', 'string', 'max:100'],
            'pais' => ['required', 'string', 'max:100'],
            'provincia' => ['required', 'string', 'max:100'],
            'ciudad' => ['required', 'string', 'max:100'],
            'codigoPostal' => ['required', 'string', 'max:20'],
            'calle' => ['required', 'string', 'max:150'],
            'numeroPortal' => ['required', 'string', 'max:20'],
            'esPrincipal' => ['nullable', 'boolean'],
        ];
    }

    /**
     * Mensajes personalizados de validación.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'nombreDireccion.required' => 'El nombre de la dirección es obligatorio.',
            'pais.required' => 'El país es obligatorio.',
            'provincia.required' => 'La provincia es obligatoria.',
            'ciudad.required' => 'La ciudad es obligatoria.',
            'codigoPostal.required' => 'El código postal es obligatorio.',
            'calle.required' => 'La calle es obligatoria.',
            'numeroPortal.required' => 'El número del portal es obligatorio.',
        ];
    }
}
