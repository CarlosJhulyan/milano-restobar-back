<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderHeader extends Model
{
  protected $table = 'vta_pedido_venta_cab';
  protected $primaryKey = 'idvta_pedido_venta_cab';
  public const CREATED_AT = 'fecha_creacion';
  public const UPDATED_AT = 'fecha_edicion';
  protected $fillable = [
    'estado',
    'monto_total',
    'id_usuario',
    'id_cliente',
    'vta_mesa_id_vta_mesa'
  ];
}
