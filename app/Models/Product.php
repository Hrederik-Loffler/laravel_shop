<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Category;

class Product extends Model
{
    protected $fillable = [
        'category_id',
        'name',
        'slug',
        'image',
        'price',
    ];
    //return category of selected product
    public function category()
    {
        return $this->belongsTo(Category::class);
        // return Category::find($this->categore_id);
    }

    public function baskets()
    {
        return $this->belongsToMany(Basket::class)->withPivot('quantity');
    }
}