<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCarritoItemRequest extends FormRequest
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
     * Reglas de validación para añadir un producto al carrito.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'idProducto' => ['required', 'integer', 'exists:productos,idProducto'],
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
            'idProducto.required' => 'El producto es obligatorio.',
            'idProducto.exists' => 'El producto seleccionado no existe.',
            'cantidad.required' => 'La cantidad es obligatoria.',
            'cantidad.integer' => 'La cantidad debe ser un número entero.',
            'cantidad.min' => 'La cantidad debe ser al menos 1.',
        ];
    }
}
