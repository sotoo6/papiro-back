<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreAdminRequest;
use App\Models\Usuario;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;
use Throwable;

class AdminUsuarioController extends Controller
{
    /**
     * Muestra todos los usuarios para administración.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        try {
            // Se obtienen todos los usuarios ordenados por id descendente.
            $usuarios = Usuario::orderByDesc('idUsuario')->get();

            return response()->json([
                'success' => true,
                'message' => 'Usuarios obtenidos correctamente.',
                'data' => $usuarios
            ], 200);
        } catch (Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'Se produjo un error al obtener los usuarios.',
                'errors' => [
                    'server' => [$e->getMessage()]
                ]
            ], 500);
        }
    }

    /**
     * Muestra un usuario concreto para administración.
     *
     * @param string $id
     * @return JsonResponse
     */
    public function show(string $id): JsonResponse
    {
        try {
            // Se busca el usuario por id.
            $usuario = Usuario::find($id);

            if (!$usuario) {
                return response()->json([
                    'success' => false,
                    'message' => 'Usuario no encontrado.',
                    'errors' => null
                ], 404);
            }

            return response()->json([
                'success' => true,
                'message' => 'Usuario obtenido correctamente.',
                'data' => $usuario
            ], 200);
        } catch (Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'Se produjo un error al obtener el usuario.',
                'errors' => [
                    'server' => [$e->getMessage()]
                ]
            ], 500);
        }
    }

    /**
     * Activa o desactiva un usuario.
     *
     * Cambia el valor booleano de estaActivo.
     *
     * @param string $id
     * @return JsonResponse
     */
    public function updateEstado(string $id): JsonResponse
    {
        try {
            // Se busca el usuario.
            $usuario = Usuario::find($id);

            if (!$usuario) {
                return response()->json([
                    'success' => false,
                    'message' => 'Usuario no encontrado.',
                    'errors' => null
                ], 404);
            }

            // Se invierte el estado actual del usuario.
            $usuario->update([
                'estaActivo' => !$usuario->estaActivo
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Estado del usuario actualizado correctamente.',
                'data' => $usuario
            ], 200);
        } catch (Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'Se produjo un error al actualizar el estado del usuario.',
                'errors' => [
                    'server' => [$e->getMessage()]
                ]
            ], 500);
        }
    }

    /**
     * Crea un nuevo usuario con rol de administrador.
     *
     * Esta operación solo debe poder realizarla un superadmin.
     *
     * @param StoreAdminRequest $request
     * @return JsonResponse
     */
    public function storeAdmin(StoreAdminRequest $request): JsonResponse
    {
        try {
            // Se obtiene el usuario autenticado.
            $usuarioAutenticado = auth()->user();

            // Solo un superadmin puede crear administradores.
            if (!$usuarioAutenticado || $usuarioAutenticado->rol !== 'superadmin') {
                return response()->json([
                    'success' => false,
                    'message' => 'Solo un superadministrador puede crear administradores.',
                    'errors' => null
                ], 403);
            }

            // Se crea el nuevo usuario con rol admin.
            $admin = Usuario::create([
                'nombre' => $request->nombre,
                'apellidos' => $request->apellidos,
                'email' => $request->email,
                'passwordHash' => Hash::make($request->password),
                'rol' => 'admin',
                'telefono' => $request->telefono,
                'fechaRegistro' => now()->toDateString(),
                'estaActivo' => true,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Administrador creado correctamente.',
                'data' => $admin
            ], 201);
        } catch (Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'Se produjo un error al crear el administrador.',
                'errors' => [
                    'server' => [$e->getMessage()]
                ]
            ], 500);
        }
    }
}
