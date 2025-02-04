<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Clients extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'identification', 'email', 'phone'];

    // Relación: Un cliente tiene muchas ventas
    public function sales()
    {
        return $this->hasMany(Sales::class);
    }
}
