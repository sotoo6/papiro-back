<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateEstadoPedidoRequest;
use App\Models\Pedido;
use Illuminate\Http\JsonResponse;
use Throwable;

class AdminPedidoController extends Controller
{
    /**
     * Muestra todos los pedidos para administración.
     *
     * Carga también usuario, detalle y factura.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        try {
            // Se obtienen todos los pedidos con sus relaciones necesarias.
            $pedidos = Pedido::with(['usuario', 'detalles.producto', 'factura'])
                ->orderByDesc('idPedido')
                ->get();

            return response()->json([
                'success' => true,
                'message' => 'Pedidos obtenidos correctamente.',
                'data' => $pedidos
            ], 200);
        } catch (Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'Se produjo un error al obtener los pedidos.',
                'errors' => [
                    'server' => [$e->getMessage()]
                ]
            ], 500);
        }
    }

    /**
     * Muestra un pedido concreto para administración.
     *
     * @param string $id
     * @return JsonResponse
     */
    public function show(string $id): JsonResponse
    {
        try {
            // Se busca el pedido con sus relaciones.
            $pedido = Pedido::with(['usuario', 'detalles.producto', 'factura'])
                ->find($id);

            if (!$pedido) {
                return response()->json([
                    'success' => false,
                    'message' => 'Pedido no encontrado.',
                    'errors' => null
                ], 404);
            }

            return response()->json([
                'success' => true,
                'message' => 'Pedido obtenido correctamente.',
                'data' => $pedido
            ], 200);
        } catch (Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'Se produjo un error al obtener el pedido.',
                'errors' => [
                    'server' => [$e->getMessage()]
                ]
            ], 500);
        }
    }

    /**
     * Actualiza el estado de un pedido.
     *
     * @param UpdateEstadoPedidoRequest $request
     * @param string $id
     * @return JsonResponse
     */
    public function updateEstado(UpdateEstadoPedidoRequest $request, string $id): JsonResponse
    {
        try {
            // Se busca el pedido por id.
            $pedido = Pedido::find($id);

            if (!$pedido) {
                return response()->json([
                    'success' => false,
                    'message' => 'Pedido no encontrado.',
                    'errors' => null
                ], 404);
            }

            // Se actualiza el estado del pedido.
            $pedido->update([
                'estado' => $request->estado,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Estado del pedido actualizado correctamente.',
                'data' => $pedido
            ], 200);
        } catch (Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'Se produjo un error al actualizar el estado del pedido.',
                'errors' => [
                    'server' => [$e->getMessage()]
                ]
            ], 500);
        }
    }
}
