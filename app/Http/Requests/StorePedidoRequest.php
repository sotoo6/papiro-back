<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePedidoRequest extends FormRequest
{
    /**
     * Indica si esta petición está autorizada.
     *
     * La ruta ya estará protegida con auth:sanctum,
     * por eso aquí devolvemos true.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Reglas de validación para crear un pedido.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'idDireccion' => ['required', 'integer', 'exists:direcciones,idDireccion'],
            'metodoPago' => ['required', 'string', 'max:50'],
            'metodoEntrega' => ['required', 'string', 'max:50'],
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
            'idDireccion.required' => 'La dirección es obligatoria.',
            'idDireccion.exists' => 'La dirección seleccionada no existe.',
            'metodoPago.required' => 'El método de pago es obligatorio.',
            'metodoEntrega.required' => 'El método de entrega es obligatorio.',
        ];
    }
}
