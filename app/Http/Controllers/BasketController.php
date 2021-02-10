<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Basket;

class BasketController extends Controller {
    
    /**
     * Show the basket
     */
    public function index(Request $request)
    {
        $basket_id = $request->cookie('basket_id');
        if (!empty($basket_id)) {
            $products = Basket::findOrFail($basket_id)->products;
            return view('basket.index', compact('products'));
        } else {
            $products = null;
            return view('basket.index', compact('products'));
        }
    }

    public function checkout()
    {
        return view('basket.checkout');
    }

    /**
     * add product to basket with $id
     */
    public function add(Request $request, $id)
    {
        $basket_id = $request->cookie('basket_id');
        $quantity = $request->input('quantity') ?? 1;
        if (empty($basket_id)) {
            // if the basket do not exists - create new instance
            $basket = Basket::create();
            // get a identifier for store to cookie
            $basket_id = $basket->id;
        } else {
            // basket exists alredy, get instance of basket
            $basket = Basket::findOrFail($basket_id);
            // uptade 'updated_at' row of baskets table
            $basket->touch();
        }
        if ($basket->products->contains($id)) {
            // if a product in basket - change quantity of product
            $pivotRow = $basket->products()->where('product_id', $id)->first()->pivot;
            $quantity = $pivotRow->quantity + $quantity;
            $pivotRow->update(['quantity' => $quantity]);
        } else {
            // if this product do not in the basket add this product
            $basket->products()->attach($id, ['quantity' => $quantity]);
        }
        return back()->withCookie(cookie('basket_id', $basket_id, 525600));
    }
}