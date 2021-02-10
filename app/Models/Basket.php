<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Cookie;
use App\Http\Controllers\BasketController;


class Basket extends Model
{

    /**
     * many to many relationship
     */
    public function products()
    {
        return $this->belongsToMany(Product::class)->withPivot('quantity');
    }
    
}