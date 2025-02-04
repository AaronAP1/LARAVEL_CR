<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Clients;
use App\Models\Sales_Details;

class Sales extends Model
{
    use HasFactory;

    protected $fillable = ['code', 'client_id', 'seller_id', 'total_amount', 'sale_datetime'];

    public function client()
    {
        return $this->belongsTo(Clients::class);
    }

    public function seller()
    {
        return $this->belongsTo(User::class, 'seller_id');
    }

    public function salesDetails()
    {
        return $this->hasMany(Sales_Details::class);
    }

    public function details()
    {
        return $this->hasMany(Sales_Details::class, 'sale_id', 'id');
    }
}
