<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateEstadoPedidoRequest;
use App\Models\Pedido;
use Illuminate\Http\JsonResponse;
use Throwable;
use App\Models\Factura;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;

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
     * Si el pedido pasa a confirmado o enviado,
     * se genera automáticamente su factura PDF.
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

            // Si el pedido pasa a confirmado o enviado, se genera la factura.
            if (in_array($pedido->estado, ['confirmado', 'enviado'])) {
                $this->generarFacturaPdf($pedido);
            }

            return response()->json([
                'success' => true,
                'message' => 'Estado del pedido actualizado correctamente.',
                'data' => $pedido->load('factura')
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

    /**
     * Genera la factura en PDF y la guarda en disco.
     *
     * Si la factura ya existe, reutiliza el registro y actualiza el PDF.
     *
     * @param \App\Models\Pedido $pedido
     * @return \App\Models\Factura
     */
    private function generarFacturaPdf(Pedido $pedido): Factura
    {
        // Se crea o recupera la factura del pedido.
        $factura = Factura::firstOrCreate(
            ['idPedido' => $pedido->idPedido],
            [
                'fechaEmision' => now()->toDateString(),
                'numeroFactura' => 'FAC-' . now()->format('Y') . '-' . str_pad((string) $pedido->idPedido, 6, '0', STR_PAD_LEFT),
            ]
        );

        // Se recarga el pedido con sus relaciones necesarias.
        $pedido->load(['usuario', 'detalles.producto']);

        // Se genera el PDF desde la vista Blade.
        $pdf = Pdf::loadView('pdf.factura', [
            'pedido' => $pedido,
            'factura' => $factura,
        ]);

        // Se define la ruta donde se guardará el PDF.
        $rutaPdf = 'facturas/' . $factura->numeroFactura . '.pdf';

        // Se guarda el PDF en storage/app/public/facturas
        Storage::disk('public')->put($rutaPdf, $pdf->output());

        // Se actualiza la ruta en la factura.
        $factura->update([
            'rutaPdf' => $rutaPdf,
        ]);

        return $factura->fresh();
    }
}
