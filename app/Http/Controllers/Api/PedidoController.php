<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePedidoRequest;
use App\Models\Cesta;
use App\Models\CestaProducto;
use App\Models\Direccion;
use App\Models\Pedido;
use App\Models\DetallePedido;
use App\Models\Producto;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Throwable;

class PedidoController extends Controller
{
    /**
     * Muestra todos los pedidos del usuario autenticado.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        try {
            // Se obtiene el usuario autenticado.
            $usuario = auth()->user();

            if (!$usuario) {
                return response()->json([
                    'success' => false,
                    'message' => 'Usuario no autenticado.',
                    'errors' => null
                ], 401);
            }

            // Se cargan los pedidos del usuario junto con su detalle y factura.
            $pedidos = Pedido::with(['detalles.producto', 'factura'])
                ->where('idUsuario', $usuario->idUsuario)
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
     * Muestra un pedido concreto del usuario autenticado.
     *
     * @param string $id
     * @return JsonResponse
     */
    public function show(string $id): JsonResponse
    {
        try {
            // Se obtiene el usuario autenticado.
            $usuario = auth()->user();

            if (!$usuario) {
                return response()->json([
                    'success' => false,
                    'message' => 'Usuario no autenticado.',
                    'errors' => null
                ], 401);
            }

            // Se busca el pedido solo entre los del usuario autenticado.
            $pedido = Pedido::with(['detalles.producto', 'factura'])
                ->where('idUsuario', $usuario->idUsuario)
                ->where('idPedido', $id)
                ->first();

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
     * Crea un pedido a partir del carrito del usuario autenticado.
     *
     * Comprueba que exista la cesta, que tenga productos,
     * que la dirección pertenezca al usuario y que haya stock suficiente.
     *
     * @param StorePedidoRequest $request
     * @return JsonResponse
     */
    public function store(StorePedidoRequest $request): JsonResponse
    {
        try {
            // Se obtiene el usuario autenticado.
            $usuario = auth()->user();

            if (!$usuario) {
                return response()->json([
                    'success' => false,
                    'message' => 'Usuario no autenticado.',
                    'errors' => null
                ], 401);
            }

            // Se obtiene la dirección y se comprueba que pertenezca al usuario.
            $direccion = Direccion::where('idUsuario', $usuario->idUsuario)
                ->where('idDireccion', $request->idDireccion)
                ->first();

            if (!$direccion) {
                return response()->json([
                    'success' => false,
                    'message' => 'La dirección no pertenece al usuario autenticado.',
                    'errors' => null
                ], 403);
            }

            // Se obtiene la cesta del usuario con sus productos.
            $cesta = Cesta::with('cestaProductos')
                ->where('idUsuario', $usuario->idUsuario)
                ->first();

            if (!$cesta) {
                return response()->json([
                    'success' => false,
                    'message' => 'Carrito no encontrado.',
                    'errors' => null
                ], 404);
            }

            if ($cesta->cestaProductos->isEmpty()) {
                return response()->json([
                    'success' => false,
                    'message' => 'No se puede crear un pedido con el carrito vacío.',
                    'errors' => null
                ], 422);
            }

            // Se usa transacción para asegurar que todo el proceso
            // se complete correctamente o no se guarde nada.
            $pedido = DB::transaction(function () use ($usuario, $direccion, $cesta, $request) {
                $totalPedido = 0;

                // Se crea el pedido principal.
                $pedido = Pedido::create([
                    'idUsuario' => $usuario->idUsuario,
                    'fechaPedido' => now()->toDateString(),
                    'estado' => 'pendiente',
                    'metodoPago' => $request->metodoPago,
                    'metodoEntrega' => $request->metodoEntrega,
                    'totalPedido' => 0,
                    'descuento' => 0,
                    'paisEnvio' => $direccion->pais,
                    'provinciaEnvio' => $direccion->provincia,
                    'ciudadEnvio' => $direccion->ciudad,
                    'codigoPostalEnvio' => $direccion->codigoPostal,
                    'calleEnvio' => $direccion->calle,
                    'numeroEnvio' => $direccion->numeroPortal,
                ]);

                // Se recorren todas las líneas de la cesta.
                foreach ($cesta->cestaProductos as $lineaCesta) {
                    // Se obtiene el producto actual.
                    $producto = Producto::find($lineaCesta->idProducto);

                    if (!$producto) {
                        throw new \Exception('Uno de los productos del carrito no existe.');
                    }

                    // Se comprueba que haya stock suficiente.
                    if ($lineaCesta->cantidad > $producto->stock) {
                        throw new \Exception('No hay stock suficiente para el producto: ' . $producto->nombre);
                    }

                    // Se calcula el subtotal de la línea.
                    $subtotal = $lineaCesta->cantidad * $lineaCesta->precioUnitario;

                    // Se crea la línea de detalle del pedido.
                    DetallePedido::create([
                        'idPedido' => $pedido->idPedido,
                        'idProducto' => $producto->idProducto,
                        'cantidad' => $lineaCesta->cantidad,
                        'precioUnitario' => $lineaCesta->precioUnitario,
                        'ivaAplicado' => $producto->iva?->porcentaje ?? 0,
                        'subtotal' => $subtotal,
                    ]);

                    // Se descuenta stock del producto.
                    $producto->decrement('stock', $lineaCesta->cantidad);

                    // Se suma al total del pedido.
                    $totalPedido += $subtotal;
                }

                // Se actualiza el total final del pedido.
                $pedido->update([
                    'totalPedido' => $totalPedido
                ]);

                // Se vacía la cesta una vez creado el pedido.
                CestaProducto::where('idCesta', $cesta->idCesta)->delete();

                // Se pone el total de la cesta a 0.
                $cesta->update([
                    'totalCesta' => 0
                ]);

                return $pedido->load(['detalles.producto']);
            });

            return response()->json([
                'success' => true,
                'message' => 'Pedido creado correctamente.',
                'data' => $pedido
            ], 201);
        } catch (Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'Se produjo un error al crear el pedido.',
                'errors' => [
                    'server' => [$e->getMessage()]
                ]
            ], 500);
        }
    }
}
