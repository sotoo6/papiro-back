<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Categoria;
use Illuminate\Http\JsonResponse;
use Throwable;

class CategoriaController extends Controller
{
    /**
     * Muestra el listado de categorías.
     *
     * @return JsonResponse Respuesta JSON con las categorías.
     */
    public function index(): JsonResponse
    {
        try {
            // Se obtienen todas las categorías ordenadas alfabéticamente.
            $categorias = Categoria::orderBy('nombre', 'asc')->get();

            return response()->json([
                'success' => true,
                'message' => 'Categorías obtenidas correctamente.',
                'data' => $categorias
            ], 200);
        } catch (Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'Se produjo un error al obtener las categorías.',
                'errors' => [
                    'server' => [$e->getMessage()]
                ]
            ], 500);
        }
    }

    /**
     * Muestra una categoría concreta.
     *
     * Además, carga los productos relacionados.
     *
     * @param string $id Identificador de la categoría.
     * @return JsonResponse Respuesta JSON con la categoría o error.
     */
    public function show(string $id): JsonResponse
    {
        try {
            // Se busca la categoría por id cargando sus productos.
            $categoria = Categoria::with('productos')->find($id);

            // Si no existe, se devuelve error 404.
            if (!$categoria) {
                return response()->json([
                    'success' => false,
                    'message' => 'Categoría no encontrada.',
                    'errors' => null
                ], 404);
            }

            return response()->json([
                'success' => true,
                'message' => 'Categoría obtenida correctamente.',
                'data' => $categoria
            ], 200);
        } catch (Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'Se produjo un error al obtener la categoría.',
                'errors' => [
                    'server' => [$e->getMessage()]
                ]
            ], 500);
        }
    }
}
