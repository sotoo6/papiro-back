<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Iva extends Model
{
    /**
     * Nombre de la tabla asociada al modelo.
     *
     * @var string
     */
    protected $table = 'ivas';

    /**
     * Clave primaria personalizada.
     *
     * @var string
     */
    protected $primaryKey = 'idIva';

    /**
     * Campos asignables de forma masiva.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'porcentaje',
    ];

    /**
     * Conversión automática de tipos.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'porcentaje' => 'decimal:2',
    ];

    /**
     * Relación: un tipo de IVA puede estar en muchos productos.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function productos()
    {
        return $this->hasMany(Producto::class, 'idIva', 'idIva');
    }
}
