<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Sales;
use App\Models\Products;

class Sales_Details extends Model
{
    use HasFactory;

    protected $fillable = ['sale_id', 'product_id', 'quantity', 'unit_price', 'total_price'];

    // Relación: Un detalle pertenece a una venta
    public function sale()
    {
        return $this->belongsTo(Sales::class, 'sale_id', 'id');
    }

    // Relación: Un detalle pertenece a un producto
    public function product()
    {
        return $this->belongsTo(Products::class);
    }
}
