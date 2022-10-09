<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Orders extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'orders';

    /**
     * Obtener el producto comprado en la orden.
     */
    public function product()
    {
        return $this->belongsTo(Products::class,'products_id','id');
    }

    /**
     * Obtener el cliente que compró en la orden.
     */
    public function customer()
    {
        return $this->belongsTo(Customer::class,'customer_id','id');
    }

    /**
     * Obtener el status de la orden.
     */
    public function status()
    {
        return $this->belongsTo(Status::class,'status_id','id');
    }

}
