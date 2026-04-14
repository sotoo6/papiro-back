<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCarritoItemRequest extends FormRequest
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
     * Reglas de validación para actualizar una línea del carrito.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'cantidad' => ['required', 'integer', 'min:1'],
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
            'cantidad.required' => 'La cantidad es obligatoria.',
            'cantidad.integer' => 'La cantidad debe ser un número entero.',
            'cantidad.min' => 'La cantidad debe ser al menos 1.',
        ];
    }
}
