<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Factura extends Model
{
    /**
     * Nombre de la tabla asociada al modelo.
     *
     * @var string
     */
    protected $table = 'facturas';

    /**
     * Clave primaria personalizada.
     *
     * @var string
     */
    protected $primaryKey = 'idFactura';

    /**
     * Campos asignables de forma masiva.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'idPedido',
        'fechaEmision',
        'numeroFactura',
        'rutaPdf',
    ];

    /**
     * Conversión automática de tipos.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'fechaEmision' => 'date',
    ];

    /**
     * Atributos calculados que se añadirán al JSON.
     *
     * @var array<int, string>
     */
    protected $appends = ['pdf_url'];

    /**
     * Devuelve la URL pública del PDF de la factura.
     *
     * @return string|null
     */
    public function getPdfUrlAttribute(): ?string
    {
        if (!$this->rutaPdf) {
            return null;
        }

        return asset('storage/' . $this->rutaPdf);
    }

    /**
     * Relación: una factura pertenece a un pedido.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function pedido()
    {
        return $this->belongsTo(Pedido::class, 'idPedido', 'idPedido');
    }
}
