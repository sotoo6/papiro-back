<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Models\Cesta;
use App\Models\Usuario;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;
use Throwable;

class AuthController extends Controller
{
    /**
     * Registra un nuevo usuario cliente.
     *
     * Además de crear el usuario, también se crea una cesta inicial
     * y se genera un token de acceso con Sanctum.
     *
     * @param RegisterRequest $request Datos validados del formulario.
     * @return JsonResponse Respuesta JSON con usuario y token.
     */
    public function register(RegisterRequest $request): JsonResponse
    {
        try {
            // Se crea el usuario con rol cliente por defecto.
            $usuario = Usuario::create([
                'nombre' => $request->nombre,
                'apellidos' => $request->apellidos,
                'email' => $request->email,
                'passwordHash' => Hash::make($request->password),
                'rol' => 'cliente',
                'telefono' => $request->telefono,
                'fechaRegistro' => now()->toDateString(),
                'estaActivo' => true,
            ]);

            // Se crea una cesta vacía para el nuevo usuario.
            Cesta::create([
                'idUsuario' => $usuario->idUsuario,
                'fechaCreacion' => now()->toDateString(),
                'estado' => 'activa',
                'totalCesta' => 0,
            ]);

            // Se genera un token de acceso para el usuario.
            $token = $usuario->createToken('auth_token')->plainTextToken;

            // Se devuelve la respuesta correcta.
            return response()->json([
                'success' => true,
                'message' => 'Usuario registrado correctamente.',
                'data' => [
                    'usuario' => $usuario,
                    'token' => $token,
                ]
            ], 201);
        } catch (Throwable $e) {
            // Si ocurre un error inesperado, se devuelve error 500.
            return response()->json([
                'success' => false,
                'message' => 'Se produjo un error al registrar el usuario.',
                'errors' => [
                    'server' => [$e->getMessage()]
                ]
            ], 500);
        }
    }

    /**
     * Inicia sesión de un usuario.
     *
     * Busca el usuario por email y comprueba la contraseña
     * contra el campo passwordHash.
     *
     * @param LoginRequest $request Datos validados del formulario.
     * @return JsonResponse Respuesta JSON con usuario y token.
     */
    public function login(LoginRequest $request): JsonResponse
    {
        try {
            // Se busca el usuario por correo electrónico.
            $usuario = Usuario::where('email', $request->email)->first();

            // Si no existe, se devuelven credenciales incorrectas.
            if (!$usuario) {
                return response()->json([
                    'success' => false,
                    'message' => 'Credenciales incorrectas.',
                    'errors' => null
                ], 401);
            }

            // Si la cuenta está desactivada, no se permite el acceso.
            if (!$usuario->estaActivo) {
                return response()->json([
                    'success' => false,
                    'message' => 'Tu cuenta está desactivada.',
                    'errors' => null
                ], 403);
            }

            // Se comprueba la contraseña contra el hash almacenado.
            if (!Hash::check($request->password, $usuario->passwordHash)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Credenciales incorrectas.',
                    'errors' => null
                ], 401);
            }

            // Se eliminan tokens anteriores para dejar una sola sesión activa.
            // TODO $usuario->tokens()->delete();

            // Se crea un nuevo token.
            $token = $usuario->createToken('auth_token')->plainTextToken;

            // Se devuelve la respuesta correcta.
            return response()->json([
                'success' => true,
                'message' => 'Inicio de sesión correcto.',
                'data' => [
                    'usuario' => $usuario,
                    'token' => $token,
                ]
            ], 200);
        } catch (Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'Se produjo un error al iniciar sesión.',
                'errors' => [
                    'server' => [$e->getMessage()]
                ]
            ], 500);
        }
    }

    /**
     * Devuelve el usuario autenticado.
     *
     * Esta ruta debe estar protegida con auth:sanctum.
     *
     * @return JsonResponse Respuesta JSON con el usuario autenticado.
     */
    public function me(): JsonResponse
    {
        try {
            // Se obtiene el usuario autenticado desde la sesión/token actual.
            $usuario = auth()->user();

            return response()->json([
                'success' => true,
                'message' => 'Usuario autenticado obtenido correctamente.',
                'data' => $usuario
            ], 200);
        } catch (Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'No se pudo obtener el usuario autenticado.',
                'errors' => [
                    'server' => [$e->getMessage()]
                ]
            ], 500);
        }
    }

    /**
     * Cierra la sesión del usuario autenticado.
     *
     * Elimina el token usado en la petición actual.
     *
     * @return JsonResponse Respuesta JSON confirmando el cierre de sesión.
     */
    public function logout(): JsonResponse
    {
        try {
            // Se obtiene el usuario autenticado.
            $usuario = auth()->user();

            // Si no existe usuario autenticado, se devuelve error.
            if (!$usuario) {
                return response()->json([
                    'success' => false,
                    'message' => 'No hay ningún usuario autenticado.',
                    'errors' => null
                ], 401);
            }

            // Se elimina el token actual.
            $usuario->currentAccessToken()->delete();

            return response()->json([
                'success' => true,
                'message' => 'Sesión cerrada correctamente.',
                'data' => null
            ], 200);
        } catch (Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'Se produjo un error al cerrar la sesión.',
                'errors' => [
                    'server' => [$e->getMessage()]
                ]
            ], 500);
        }
    }
}
