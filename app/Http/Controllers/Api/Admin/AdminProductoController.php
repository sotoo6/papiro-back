<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreProductoRequest;
use App\Http\Requests\UpdateProductoRequest;
use App\Models\Producto;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Storage;
use Throwable;

class AdminProductoController extends Controller
{
    /**
     * Muestra todos los productos para administración.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        try {
            // Se cargan productos con su IVA y categorías.
            $productos = Producto::with(['iva', 'categorias'])
                ->orderByDesc('idProducto')
                ->get();

            return response()->json([
                'success' => true,
                'message' => 'Productos obtenidos correctamente.',
                'data' => $productos
            ], 200);
        } catch (Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'Se produjo un error al obtener los productos.',
                'errors' => [
                    'server' => [$e->getMessage()]
                ]
            ], 500);
        }
    }

    /**
     * Muestra un producto concreto para administración.
     *
     * @param string $id
     * @return JsonResponse
     */
    public function show(string $id): JsonResponse
    {
        try {
            // Se busca el producto por id.
            $producto = Producto::with(['iva', 'categorias'])->find($id);

            if (!$producto) {
                return response()->json([
                    'success' => false,
                    'message' => 'Producto no encontrado.',
                    'errors' => null
                ], 404);
            }

            return response()->json([
                'success' => true,
                'message' => 'Producto obtenido correctamente.',
                'data' => $producto
            ], 200);
        } catch (Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'Se produjo un error al obtener el producto.',
                'errors' => [
                    'server' => [$e->getMessage()]
                ]
            ], 500);
        }
    }

    /**
     * Crea un nuevo producto.
     *
     * @param StoreProductoRequest $request
     * @return JsonResponse
     */
    public function store(StoreProductoRequest $request): JsonResponse
    {
        try {
            // Ruta de imagen por defecto.
            $rutaImagen = null;

            // Si se subió una imagen, se guarda en storage/app/public/productos.
            if ($request->hasFile('imagen')) {
                $rutaImagen = $request->file('imagen')->store('productos', 'public');
            }

            // Se crea el producto.
            $producto = Producto::create([
                'idIva' => $request->idIva,
                'nombre' => $request->nombre,
                'descripcion' => $request->descripcion,
                'precio' => $request->precio,
                'stock' => $request->stock,
                'imagen' => $rutaImagen,
                'descuento' => $request->descuento ?? 0,
                'marca' => $request->marca,
                'proveedor' => $request->proveedor,
            ]);

            // Si vienen categorías, se relacionan con el producto.
            if ($request->filled('categorias')) {
                $producto->categorias()->sync($request->categorias);
            }

            return response()->json([
                'success' => true,
                'message' => 'Producto creado correctamente.',
                'data' => $producto->load(['iva', 'categorias'])
            ], 201);
        } catch (Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'Se produjo un error al crear el producto.',
                'errors' => [
                    'server' => [$e->getMessage()]
                ]
            ], 500);
        }
    }

    /**
     * Actualiza un producto existente.
     *
     * @param UpdateProductoRequest $request
     * @param string $id
     * @return JsonResponse
     */
    public function update(UpdateProductoRequest $request, string $id): JsonResponse
    {
        try {
            // Se busca el producto.
            $producto = Producto::find($id);

            if (!$producto) {
                return response()->json([
                    'success' => false,
                    'message' => 'Producto no encontrado.',
                    'errors' => null
                ], 404);
            }

            // Si se sube una nueva imagen, se elimina la anterior y se guarda la nueva.
            if ($request->hasFile('imagen')) {
                if ($producto->imagen && Storage::disk('public')->exists($producto->imagen)) {
                    Storage::disk('public')->delete($producto->imagen);
                }

                $producto->imagen = $request->file('imagen')->store('productos', 'public');
            }

            // Se actualizan los datos del producto.
            $producto->update([
                'idIva' => $request->idIva,
                'nombre' => $request->nombre,
                'descripcion' => $request->descripcion,
                'precio' => $request->precio,
                'stock' => $request->stock,
                'descuento' => $request->descuento ?? 0,
                'marca' => $request->marca,
                'proveedor' => $request->proveedor,
            ]);

            // Si se envían categorías, se actualizan.
            if ($request->has('categorias')) {
                $producto->categorias()->sync($request->categorias ?? []);
            }

            return response()->json([
                'success' => true,
                'message' => 'Producto actualizado correctamente.',
                'data' => $producto->load(['iva', 'categorias'])
            ], 200);
        } catch (Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'Se produjo un error al actualizar el producto.',
                'errors' => [
                    'server' => [$e->getMessage()]
                ]
            ], 500);
        }
    }

    /**
     * Elimina un producto existente.
     *
     * @param string $id
     * @return JsonResponse
     */
    public function destroy(string $id): JsonResponse
    {
        try {
            // Se busca el producto.
            $producto = Producto::find($id);

            if (!$producto) {
                return response()->json([
                    'success' => false,
                    'message' => 'Producto no encontrado.',
                    'errors' => null
                ], 404);
            }

            // Si tiene imagen, se elimina del storage.
            if ($producto->imagen && Storage::disk('public')->exists($producto->imagen)) {
                Storage::disk('public')->delete($producto->imagen);
            }

            // Se eliminan relaciones con categorías antes de borrar.
            $producto->categorias()->detach();

            // Se elimina el producto.
            $producto->delete();

            return response()->json([
                'success' => true,
                'message' => 'Producto eliminado correctamente.',
                'data' => null
            ], 200);
        } catch (Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'Se produjo un error al eliminar el producto.',
                'errors' => [
                    'server' => [$e->getMessage()]
                ]
            ], 500);
        }
    }
}
