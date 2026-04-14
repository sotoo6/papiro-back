<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Producto;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Throwable;

class ProductoController extends Controller
{
    /**
     * Muestra el listado de productos.
     *
     * Permite buscar por nombre, filtrar por categoría
     * y ordenar por nombre o precio.
     *
     * @param Request $request Datos enviados en la query string.
     * @return JsonResponse Respuesta JSON con los productos.
     */
    public function index(Request $request): JsonResponse
    {
        try {
            // Se inicia la consulta base cargando también
            // las relaciones de IVA y categorías.
            $query = Producto::with(['iva', 'categorias']);

            // Si llega un texto en "buscar", se filtran los productos
            // cuyo nombre contenga ese texto.
            if ($request->filled('buscar')) {
                $query->where('nombre', 'like', '%' . $request->buscar . '%');
            }

            // Si llega una categoría, se filtran solo los productos
            // que pertenezcan a esa categoría.
            if ($request->filled('categoria')) {
                $categoriaId = $request->categoria;

                $query->whereHas('categorias', function ($q) use ($categoriaId) {
                    $q->where('categorias.idCategoria', $categoriaId);
                });
            }

            // Si llega un criterio de ordenación, se aplica.
            if ($request->filled('orden')) {
                switch ($request->orden) {
                    case 'precio_asc':
                        $query->orderBy('precio', 'asc');
                        break;

                    case 'precio_desc':
                        $query->orderBy('precio', 'desc');
                        break;

                    case 'nombre_asc':
                        $query->orderBy('nombre', 'asc');
                        break;

                    case 'nombre_desc':
                        $query->orderBy('nombre', 'desc');
                        break;

                    default:
                        // Si el valor recibido no coincide con ninguno
                        // de los previstos, se usa un orden por defecto.
                        $query->orderBy('idProducto', 'desc');
                        break;
                }
            } else {
                // Si no se envía ningún criterio de orden,
                // se ordena por id descendente.
                $query->orderBy('idProducto', 'desc');
            }

            // Se ejecuta la consulta.
            $productos = $query->get();

            // Se devuelve una respuesta JSON correcta.
            return response()->json([
                'success' => true,
                'message' => 'Productos obtenidos correctamente.',
                'data' => $productos
            ], 200);
        } catch (Throwable $e) {
            // Si ocurre un error inesperado, se devuelve error 500.
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
     * Muestra el detalle de un producto concreto.
     *
     * Busca el producto por su id y carga también
     * las relaciones de IVA y categorías.
     *
     * @param string $id Identificador del producto.
     * @return JsonResponse Respuesta JSON con el producto o error.
     */
    public function show(string $id): JsonResponse
    {
        try {
            // Se busca el producto por id.
            $producto = Producto::with(['iva', 'categorias'])->find($id);

            // Si no existe, se devuelve error 404.
            if (!$producto) {
                return response()->json([
                    'success' => false,
                    'message' => 'Producto no encontrado.',
                    'errors' => null
                ], 404);
            }

            // Si existe, se devuelve su información.
            return response()->json([
                'success' => true,
                'message' => 'Producto obtenido correctamente.',
                'data' => $producto
            ], 200);
        } catch (Throwable $e) {
            // Si ocurre un error inesperado, se devuelve error 500.
            return response()->json([
                'success' => false,
                'message' => 'Se produjo un error al obtener el producto.',
                'errors' => [
                    'server' => [$e->getMessage()]
                ]
            ], 500);
        }
    }
}
