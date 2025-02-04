<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Sales_Details;

class Products extends Model
{
    use HasFactory;

    protected $fillable = [
        'sku', 'name', 'description', 'price', 'stock', 'estatus'
    ];

    public function salesDetails()
    {
        return $this->hasMany(Sales_Details::class);
    }
}
