<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreDireccionRequest;
use App\Http\Requests\UpdateDireccionRequest;
use App\Models\Direccion;
use Illuminate\Http\JsonResponse;
use Throwable;

class DireccionController extends Controller
{
    /**
     * Muestra todas las direcciones del usuario autenticado.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        try {
            // Se obtiene el usuario autenticado.
            $usuario = auth()->user();

            // Se cargan solo sus direcciones, ordenando primero la principal.
            $direcciones = Direccion::where('idUsuario', $usuario->idUsuario)
                ->orderByDesc('esPrincipal')
                ->orderByDesc('idDireccion')
                ->get();

            return response()->json([
                'success' => true,
                'message' => 'Direcciones obtenidas correctamente.',
                'data' => $direcciones
            ], 200);
        } catch (Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'Se produjo un error al obtener las direcciones.',
                'errors' => [
                    'server' => [$e->getMessage()]
                ]
            ], 500);
        }
    }

    /**
     * Guarda una nueva dirección para el usuario autenticado.
     *
     * Si la nueva dirección llega marcada como principal,
     * primero se desmarcan las demás.
     *
     * @param StoreDireccionRequest $request
     * @return JsonResponse
     */
    public function store(StoreDireccionRequest $request): JsonResponse
    {
        try {
            // Se obtiene el usuario autenticado.
            $usuario = auth()->user();

            // Si esta dirección será principal,
            // se quita la marca principal del resto.
            if ($request->boolean('esPrincipal')) {
                Direccion::where('idUsuario', $usuario->idUsuario)
                    ->update(['esPrincipal' => false]);
            }

            // Se crea la nueva dirección asociada al usuario autenticado.
            $direccion = Direccion::create([
                'idUsuario' => $usuario->idUsuario,
                'nombreDireccion' => $request->nombreDireccion,
                'pais' => $request->pais,
                'provincia' => $request->provincia,
                'ciudad' => $request->ciudad,
                'codigoPostal' => $request->codigoPostal,
                'calle' => $request->calle,
                'numeroPortal' => $request->numeroPortal,
                'piso' => $request->piso,
                'esPrincipal' => $request->boolean('esPrincipal'),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Dirección creada correctamente.',
                'data' => $direccion
            ], 201);
        } catch (Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'Se produjo un error al crear la dirección.',
                'errors' => [
                    'server' => [$e->getMessage()]
                ]
            ], 500);
        }
    }

    /**
     * Muestra una dirección concreta del usuario autenticado.
     *
     * Solo permite acceder si la dirección pertenece al usuario.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function show(int $id): JsonResponse
    {
        try {
            // Se obtiene el usuario autenticado.
            $usuario = auth()->user();

            // Se busca la dirección solo dentro de las del usuario autenticado.
            $direccion = Direccion::where('idUsuario', $usuario->idUsuario)
                ->where('idDireccion', $id)
                ->first();

            // Si no existe, se devuelve error 404.
            if (!$direccion) {
                return response()->json([
                    'success' => false,
                    'message' => 'Dirección no encontrada.',
                    'errors' => null
                ], 404);
            }

            return response()->json([
                'success' => true,
                'message' => 'Dirección obtenida correctamente.',
                'data' => $direccion
            ], 200);
        } catch (Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'Se produjo un error al obtener la dirección.',
                'errors' => [
                    'server' => [$e->getMessage()]
                ]
            ], 500);
        }
    }

    /**
     * Actualiza una dirección del usuario autenticado.
     *
     * Solo permite modificarla si pertenece al usuario.
     *
     * Si se marca como principal, primero se desmarcan las demás.
     *
     * @param UpdateDireccionRequest $request
     * @param int $id
     * @return JsonResponse
     */
    public function update(UpdateDireccionRequest $request, int $id): JsonResponse
    {
        try {
            // Se obtiene el usuario autenticado.
            $usuario = auth()->user();

            // Se busca la dirección solo entre las del usuario autenticado.
            $direccion = Direccion::where('idUsuario', $usuario->idUsuario)
                ->where('idDireccion', $id)
                ->first();

            // Si no existe, se devuelve error 404.
            if (!$direccion) {
                return response()->json([
                    'success' => false,
                    'message' => 'Dirección no encontrada.',
                    'errors' => null
                ], 404);
            }

            // Si la dirección actualizada será principal,
            // se desmarcan primero las demás.
            if ($request->boolean('esPrincipal')) {
                Direccion::where('idUsuario', $usuario->idUsuario)
                    ->where('idDireccion', '!=', $direccion->idDireccion)
                    ->update(['esPrincipal' => false]);
            }

            // Se actualizan los datos de la dirección.
            $direccion->update([
                'nombreDireccion' => $request->nombreDireccion,
                'pais' => $request->pais,
                'provincia' => $request->provincia,
                'ciudad' => $request->ciudad,
                'codigoPostal' => $request->codigoPostal,
                'calle' => $request->calle,
                'numeroPortal' => $request->numeroPortal,
                'piso' => $request->piso,
                'esPrincipal' => $request->boolean('esPrincipal'),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Dirección actualizada correctamente.',
                'data' => $direccion
            ], 200);
        } catch (Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'Se produjo un error al actualizar la dirección.',
                'errors' => [
                    'server' => [$e->getMessage()]
                ]
            ], 500);
        }
    }

    /**
     * Elimina una dirección del usuario autenticado.
     *
     * Solo permite eliminarla si pertenece al usuario.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function destroy(int $id): JsonResponse
    {
        try {
            // Se obtiene el usuario autenticado.
            $usuario = auth()->user();

            // Se busca la dirección solo entre las del usuario autenticado.
            $direccion = Direccion::where('idUsuario', $usuario->idUsuario)
                ->where('idDireccion', $id)
                ->first();

            // Si no existe, se devuelve error 404.
            if (!$direccion) {
                return response()->json([
                    'success' => false,
                    'message' => 'Dirección no encontrada.',
                    'errors' => null
                ], 404);
            }

            // Se elimina la dirección.
            $direccion->delete();

            return response()->json([
                'success' => true,
                'message' => 'Dirección eliminada correctamente.',
                'data' => null
            ], 200);
        } catch (Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'Se produjo un error al eliminar la dirección.',
                'errors' => [
                    'server' => [$e->getMessage()]
                ]
            ], 500);
        }
    }

    /**
     * Marca una dirección como principal.
     *
     * Primero desmarca el resto de direcciones del usuario.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function setPrincipal(int $id): JsonResponse
    {
        try {
            $usuario = auth()->user();

            $direccion = Direccion::where('idUsuario', $usuario->idUsuario)
                ->where('idDireccion', $id)
                ->first();

            if (!$direccion) {
                return response()->json([
                    'success' => false,
                    'message' => 'Dirección no encontrada.',
                    'errors' => null
                ], 404);
            }

            \DB::transaction(function () use ($usuario, $direccion) {
                Direccion::where('idUsuario', $usuario->idUsuario)
                    ->update(['esPrincipal' => false]);

                $direccion->update([
                    'esPrincipal' => true
                ]);
            });

            $direccion->refresh();

            return response()->json([
                'success' => true,
                'message' => 'Dirección principal actualizada correctamente.',
                'data' => $direccion
            ], 200);
        } catch (Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'Se produjo un error al marcar la dirección como principal.',
                'errors' => [
                    'server' => [$e->getMessage()]
                ]
            ], 500);
        }
    }
}
