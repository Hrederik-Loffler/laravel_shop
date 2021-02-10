<?php

namespace App\Models\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'user_id',
        'name',
        'email',
        'phone',
        'address',
        'comment',
        'amount',
    ];
    
    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }
}
