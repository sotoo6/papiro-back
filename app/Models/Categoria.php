<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Categoria extends Model
{
    /**
     * Nombre de la tabla asociada al modelo.
     *
     * @var string
     */
    protected $table = 'categorias';

    /**
     * Clave primaria personalizada.
     *
     * @var string
     */
    protected $primaryKey = 'idCategoria';

    /**
     * Campos asignables de forma masiva.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'nombre',
        'descripcion',
    ];

    /**
     * Relación: una categoría puede pertenecer a muchos productos.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function productos()
    {
        return $this->belongsToMany(
            Producto::class,
            'producto_categoria',
            'idCategoria',
            'idProducto'
        )->withTimestamps();
    }
}
