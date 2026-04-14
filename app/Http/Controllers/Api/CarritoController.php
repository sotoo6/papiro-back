<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCarritoItemRequest;
use App\Http\Requests\UpdateCarritoItemRequest;
use App\Models\Cesta;
use App\Models\CestaProducto;
use App\Models\Producto;
use Illuminate\Http\JsonResponse;
use Throwable;

class CarritoController extends Controller
{
    /**
     * Muestra el carrito del usuario autenticado.
     *
     * Devuelve la cesta con sus líneas y los productos asociados.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        try {
            // Se obtiene el usuario autenticado.
            $usuario = auth()->user();

            // Si no hay usuario autenticado, se devuelve error.
            if (!$usuario) {
                return response()->json([
                    'success' => false,
                    'message' => 'Usuario no autenticado.',
                    'errors' => null
                ], 401);
            }

            // Se busca la cesta del usuario cargando sus líneas y productos.
            $cesta = Cesta::with(['cestaProductos.producto'])
                ->where('idUsuario', $usuario->idUsuario)
                ->first();

            // Si no existe cesta, se devuelve error 404.
            if (!$cesta) {
                return response()->json([
                    'success' => false,
                    'message' => 'Carrito no encontrado.',
                    'errors' => null
                ], 404);
            }

            return response()->json([
                'success' => true,
                'message' => 'Carrito obtenido correctamente.',
                'data' => $cesta
            ], 200);
        } catch (Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'Se produjo un error al obtener el carrito.',
                'errors' => [
                    'server' => [$e->getMessage()]
                ]
            ], 500);
        }
    }

    /**
     * Añade un producto al carrito del usuario autenticado.
     *
     * Si el producto ya existe en el carrito, suma la cantidad.
     * Además, comprueba que haya stock suficiente.
     *
     * @param StoreCarritoItemRequest $request
     * @return JsonResponse
     */
    public function store(StoreCarritoItemRequest $request): JsonResponse
    {
        try {
            // Se obtiene el usuario autenticado.
            $usuario = auth()->user();

            // Si no hay usuario autenticado, se devuelve error.
            if (!$usuario) {
                return response()->json([
                    'success' => false,
                    'message' => 'Usuario no autenticado.',
                    'errors' => null
                ], 401);
            }

            // Se obtiene la cesta del usuario.
            $cesta = Cesta::where('idUsuario', $usuario->idUsuario)->first();

            // Si no existe la cesta, se devuelve error.
            if (!$cesta) {
                return response()->json([
                    'success' => false,
                    'message' => 'Carrito no encontrado.',
                    'errors' => null
                ], 404);
            }

            // Se busca el producto que se quiere añadir.
            $producto = Producto::find($request->idProducto);

            // Si no existe, se devuelve error.
            if (!$producto) {
                return response()->json([
                    'success' => false,
                    'message' => 'Producto no encontrado.',
                    'errors' => null
                ], 404);
            }

            // Se comprueba si ya existe una línea con ese producto en la cesta.
            $linea = CestaProducto::where('idCesta', $cesta->idCesta)
                ->where('idProducto', $producto->idProducto)
                ->first();

            if ($linea) {
                // Si ya existe, se suma la cantidad anterior con la nueva.
                $nuevaCantidad = $linea->cantidad + $request->cantidad;

                // Se comprueba que haya stock suficiente.
                if ($nuevaCantidad > $producto->stock) {
                    return response()->json([
                        'success' => false,
                        'message' => 'No hay stock suficiente para la cantidad solicitada.',
                        'errors' => null
                    ], 422);
                }

                // Se actualiza la cantidad de la línea existente.
                $linea->update([
                    'cantidad' => $nuevaCantidad,
                    'precioUnitario' => $producto->precio,
                ]);
            } else {
                // Si no existe, se comprueba el stock antes de crear la línea.
                if ($request->cantidad > $producto->stock) {
                    return response()->json([
                        'success' => false,
                        'message' => 'No hay stock suficiente para la cantidad solicitada.',
                        'errors' => null
                    ], 422);
                }

                // Se crea una nueva línea en el carrito.
                $linea = CestaProducto::create([
                    'idCesta' => $cesta->idCesta,
                    'idProducto' => $producto->idProducto,
                    'cantidad' => $request->cantidad,
                    'precioUnitario' => $producto->precio,
                ]);
            }

            // Se recalcula el total del carrito.
            $this->recalcularTotalCesta($cesta);

            // Se devuelve la línea creada o actualizada.
            return response()->json([
                'success' => true,
                'message' => 'Producto añadido al carrito correctamente.',
                'data' => $linea->load('producto')
            ], 201);
        } catch (Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'Se produjo un error al añadir el producto al carrito.',
                'errors' => [
                    'server' => [$e->getMessage()]
                ]
            ], 500);
        }
    }

    /**
     * Actualiza la cantidad de una línea del carrito.
     *
     * Solo permite modificar líneas que pertenezcan al usuario autenticado.
     *
     * @param UpdateCarritoItemRequest $request
     * @param string $id
     * @return JsonResponse
     */
    public function update(UpdateCarritoItemRequest $request, string $id): JsonResponse
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

            // Se obtiene la cesta del usuario.
            $cesta = Cesta::where('idUsuario', $usuario->idUsuario)->first();

            if (!$cesta) {
                return response()->json([
                    'success' => false,
                    'message' => 'Carrito no encontrado.',
                    'errors' => null
                ], 404);
            }

            // Se busca la línea dentro del carrito del usuario.
            $linea = CestaProducto::where('idCesta', $cesta->idCesta)
                ->where('idCestaProducto', $id)
                ->first();

            if (!$linea) {
                return response()->json([
                    'success' => false,
                    'message' => 'Línea de carrito no encontrada.',
                    'errors' => null
                ], 404);
            }

            // Se obtiene el producto asociado a la línea.
            $producto = Producto::find($linea->idProducto);

            if (!$producto) {
                return response()->json([
                    'success' => false,
                    'message' => 'Producto no encontrado.',
                    'errors' => null
                ], 404);
            }

            // Se comprueba si hay stock suficiente para la nueva cantidad.
            if ($request->cantidad > $producto->stock) {
                return response()->json([
                    'success' => false,
                    'message' => 'No hay stock suficiente para la cantidad solicitada.',
                    'errors' => null
                ], 422);
            }

            // Se actualiza la línea del carrito.
            $linea->update([
                'cantidad' => $request->cantidad,
                'precioUnitario' => $producto->precio,
            ]);

            // Se recalcula el total del carrito.
            $this->recalcularTotalCesta($cesta);

            return response()->json([
                'success' => true,
                'message' => 'Línea de carrito actualizada correctamente.',
                'data' => $linea->load('producto')
            ], 200);
        } catch (Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'Se produjo un error al actualizar la línea del carrito.',
                'errors' => [
                    'server' => [$e->getMessage()]
                ]
            ], 500);
        }
    }

    /**
     * Elimina una línea del carrito del usuario autenticado.
     *
     * @param string $id
     * @return JsonResponse
     */
    public function destroy(string $id): JsonResponse
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

            // Se obtiene la cesta del usuario.
            $cesta = Cesta::where('idUsuario', $usuario->idUsuario)->first();

            if (!$cesta) {
                return response()->json([
                    'success' => false,
                    'message' => 'Carrito no encontrado.',
                    'errors' => null
                ], 404);
            }

            // Se busca la línea dentro del carrito del usuario.
            $linea = CestaProducto::where('idCesta', $cesta->idCesta)
                ->where('idCestaProducto', $id)
                ->first();

            if (!$linea) {
                return response()->json([
                    'success' => false,
                    'message' => 'Línea de carrito no encontrada.',
                    'errors' => null
                ], 404);
            }

            // Se elimina la línea.
            $linea->delete();

            // Se recalcula el total del carrito.
            $this->recalcularTotalCesta($cesta);

            return response()->json([
                'success' => true,
                'message' => 'Producto eliminado del carrito correctamente.',
                'data' => null
            ], 200);
        } catch (Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'Se produjo un error al eliminar la línea del carrito.',
                'errors' => [
                    'server' => [$e->getMessage()]
                ]
            ], 500);
        }
    }

    /**
     * Vacía completamente el carrito del usuario autenticado.
     *
     * @return JsonResponse
     */
    public function clear(): JsonResponse
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

            // Se obtiene la cesta del usuario.
            $cesta = Cesta::where('idUsuario', $usuario->idUsuario)->first();

            if (!$cesta) {
                return response()->json([
                    'success' => false,
                    'message' => 'Carrito no encontrado.',
                    'errors' => null
                ], 404);
            }

            // Se eliminan todas las líneas de la cesta.
            CestaProducto::where('idCesta', $cesta->idCesta)->delete();

            // Se pone el total del carrito a 0.
            $cesta->update([
                'totalCesta' => 0
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Carrito vaciado correctamente.',
                'data' => null
            ], 200);
        } catch (Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'Se produjo un error al vaciar el carrito.',
                'errors' => [
                    'server' => [$e->getMessage()]
                ]
            ], 500);
        }
    }

    /**
     * Recalcula el total de una cesta.
     *
     * Suma todas las líneas del carrito multiplicando
     * cantidad por precio unitario.
     *
     * @param Cesta $cesta
     * @return void
     */
    private function recalcularTotalCesta(Cesta $cesta): void
    {
        // Se recalcula el total sumando todas las líneas del carrito.
        $total = CestaProducto::where('idCesta', $cesta->idCesta)
            ->get()
            ->sum(function ($linea) {
                return $linea->cantidad * $linea->precioUnitario;
            });

        // Se guarda el nuevo total.
        $cesta->update([
            'totalCesta' => $total
        ]);
    }
}
