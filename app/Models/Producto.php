<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{
    /**
     * Nombre de la tabla asociada al modelo.
     *
     * @var string
     */
    protected $table = 'productos';

    /**
     * Clave primaria personalizada.
     *
     * @var string
     */
    protected $primaryKey = 'idProducto';

    /**
     * Campos asignables de forma masiva.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'idIva',
        'nombre',
        'descripcion',
        'precio',
        'stock',
        'imagen',
        'descuento',
        'marca',
        'proveedor',
    ];

    /**
     * Conversión automática de tipos.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'precio' => 'decimal:2',
        'stock' => 'integer',
        'descuento' => 'decimal:2',
    ];

    /**
     * Atributos calculados que se añadirán al JSON.
     *
     * @var array<int, string>
     */
    protected $appends = ['imagen_url'];

    /**
     * Devuelve la URL pública completa de la imagen del producto.
     *
     * Si el producto no tiene imagen, devuelve null.
     *
     * @return string|null
     */
    public function getImagenUrlAttribute(): ?string
    {
        if (!$this->imagen) {
            return null;
        }

        return asset('storage/' . $this->imagen);
    }

    /**
     * Relación: un producto pertenece a un tipo de IVA.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function iva()
    {
        return $this->belongsTo(Iva::class, 'idIva', 'idIva');
    }

    /**
     * Relación: un producto puede pertenecer a muchas categorías.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function categorias()
    {
        return $this->belongsToMany(
            Categoria::class,
            'producto_categoria',
            'idProducto',
            'idCategoria'
        )->withTimestamps();
    }

    /**
     * Relación: un producto puede aparecer en muchas líneas de cesta.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function cestaProductos()
    {
        return $this->hasMany(CestaProducto::class, 'idProducto', 'idProducto');
    }

    /**
     * Relación: un producto puede estar en muchos detalles de pedido.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function detallePedidos()
    {
        return $this->hasMany(DetallePedido::class, 'idProducto', 'idProducto');
    }
}
