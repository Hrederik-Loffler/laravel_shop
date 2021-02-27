<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Product;

class Category extends Model
{
    protected $fillable = [
        'name',
        'slug',
    ];

    //return all products from selected category
    public function products()
    {
        return $this->hasMany(Product::class);
        // return Product::where('category_id', $this->id)->get();
    }
}
