<?php

namespace App\Models\Models;

use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    protected $fillable = [
        'product_id',
        'name',
        'price',
        'quantity'
    ];
    
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
