<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Usuario extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * Nombre de la tabla asociada al modelo.
     *
     * @var string
     */
    protected $table = 'usuarios';

    /**
     * Clave primaria personalizada de la tabla.
     *
     * @var string
     */
    protected $primaryKey = 'idUsuario';

    /**
     * Campos que se pueden asignar de forma masiva.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'nombre',
        'apellidos',
        'email',
        'passwordHash',
        'rol',
        'telefono',
        'fechaRegistro',
        'estaActivo',
    ];

    /**
     * Campos que no deben aparecer normalmente en JSON.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'passwordHash',
        'remember_token',
    ];

    /**
     * Conversión automática de tipos.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'fechaRegistro' => 'date',
        'estaActivo' => 'boolean',
    ];

    /**
     * Indica a Laravel qué campo debe usar como contraseña.
     *
     * @return string
     */
    public function getAuthPassword(): string
    {
        return $this->passwordHash;
    }

    /**
     * Relación: un usuario puede tener muchas direcciones.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function direcciones()
    {
        return $this->hasMany(Direccion::class, 'idUsuario', 'idUsuario');
    }

    /**
     * Relación: un usuario tiene una sola cesta.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function cesta()
    {
        return $this->hasOne(Cesta::class, 'idUsuario', 'idUsuario');
    }

    /**
     * Relación: un usuario puede tener muchos pedidos.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function pedidos()
    {
        return $this->hasMany(Pedido::class, 'idUsuario', 'idUsuario');
    }
}
