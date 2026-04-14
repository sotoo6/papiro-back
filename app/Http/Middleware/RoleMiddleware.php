<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Comprueba si el usuario autenticado tiene uno de los roles permitidos.
     *
     * Los roles permitidos se pasan en la propia ruta, por ejemplo:
     * ->middleware('role:admin,superadmin')
     *
     * @param Request $request Petición actual.
     * @param Closure $next Siguiente paso del middleware.
     * @param string ...$roles Lista de roles permitidos.
     * @return Response
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        // Se obtiene el usuario autenticado.
        $usuario = $request->user();

        // Si no hay usuario autenticado, se devuelve error 401.
        if (!$usuario) {
            return response()->json([
                'success' => false,
                'message' => 'Usuario no autenticado.',
                'errors' => null
            ], 401);
        }

        // Si el rol del usuario no está dentro de los permitidos, se devuelve 403.
        if (!in_array($usuario->rol, $roles)) {
            return response()->json([
                'success' => false,
                'message' => 'No tienes permisos para acceder a este recurso.',
                'errors' => null
            ], 403);
        }

        // Si todo está bien, la petición continúa.
        return $next($request);
    }
}
