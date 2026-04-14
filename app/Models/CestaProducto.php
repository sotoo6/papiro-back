<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CestaProducto extends Model
{
    /**
     * Nombre de la tabla asociada al modelo.
     *
     * @var string
     */
    protected $table = 'cesta_producto';

    /**
     * Clave primaria personalizada.
     *
     * @var string
     */
    protected $primaryKey = 'idCestaProducto';

    /**
     * Campos asignables de forma masiva.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'idCesta',
        'idProducto',
        'cantidad',
        'precioUnitario',
    ];

    /**
     * Conversión automática de tipos.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'cantidad' => 'integer',
        'precioUnitario' => 'decimal:2',
    ];

    /**
     * Relación: una línea de cesta pertenece a una cesta.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function cesta()
    {
        return $this->belongsTo(Cesta::class, 'idCesta', 'idCesta');
    }

    /**
     * Relación: una línea de cesta pertenece a un producto.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function producto()
    {
        return $this->belongsTo(Producto::class, 'idProducto', 'idProducto');
    }
}
