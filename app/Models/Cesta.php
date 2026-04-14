<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cesta extends Model
{
    /**
     * Nombre de la tabla asociada al modelo.
     *
     * @var string
     */
    protected $table = 'cestas';

    /**
     * Clave primaria personalizada.
     *
     * @var string
     */
    protected $primaryKey = 'idCesta';

    /**
     * Campos asignables de forma masiva.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'idUsuario',
        'fechaCreacion',
        'estado',
        'totalCesta',
    ];

    /**
     * Conversión automática de tipos.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'fechaCreacion' => 'date',
        'totalCesta' => 'decimal:2',
    ];

    /**
     * Relación: una cesta pertenece a un usuario.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'idUsuario', 'idUsuario');
    }

    /**
     * Relación: una cesta puede tener muchas líneas de cesta.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function cestaProductos()
    {
        return $this->hasMany(CestaProducto::class, 'idCesta', 'idCesta');
    }
}
