<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pedido extends Model
{
    /**
     * Nombre de la tabla asociada al modelo.
     *
     * @var string
     */
    protected $table = 'pedidos';

    /**
     * Clave primaria personalizada.
     *
     * @var string
     */
    protected $primaryKey = 'idPedido';

    /**
     * Campos asignables de forma masiva.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'idUsuario',
        'fechaPedido',
        'estado',
        'metodoPago',
        'metodoEntrega',
        'totalPedido',
        'descuento',
        'paisEnvio',
        'provinciaEnvio',
        'ciudadEnvio',
        'codigoPostalEnvio',
        'calleEnvio',
        'numeroEnvio',
    ];

    /**
     * Conversión automática de tipos.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'fechaPedido' => 'date',
        'totalPedido' => 'decimal:2',
        'descuento' => 'decimal:2',
    ];

    /**
     * Relación: un pedido pertenece a un usuario.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'idUsuario', 'idUsuario');
    }

    /**
     * Relación: un pedido puede tener muchas líneas de detalle.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function detalles()
    {
        return $this->hasMany(DetallePedido::class, 'idPedido', 'idPedido');
    }

    /**
     * Relación: un pedido puede tener una factura.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function factura()
    {
        return $this->hasOne(Factura::class, 'idPedido', 'idPedido');
    }
}
