<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderDetail extends Model
{
  protected $table = 'vta_pedido_vta_det';
  protected $primaryKey = 'idvta_pedido_vta_det';
  public const CREATED_AT = 'fecha_creacion';
  public const UPDATED_AT = 'fecha_edicion';
  protected $fillable = [
    'vta_plato_id_vta_plato',
    'precio',
    'cantidad',
    'id_cme_receta',
    'idvta_pedido_venta_cab',
    'vta_pedido_vta_detcol'
  ];
}
