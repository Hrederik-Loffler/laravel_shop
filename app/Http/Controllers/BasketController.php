<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Basket;
use App\Models\Models\Order;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Cookie;
use Symfony\Component\HttpFoundation\Cookie as HttpFoundationCookie;

class BasketController extends Controller
{
    
    // private $basket;

    // public function __construct() {
    //     $this->basket = Basket::getBasket();
    // }

    // private function getBasket() {
    //     $basket_id = request()->cookie('basket_id');
    //     if (!empty($basket_id)) {
    //         try {
    //             $this->basket = Basket::findOrFail($basket_id);
    //         } catch (ModelNotFoundException $e) {
    //             $this->basket = Basket::create();
    //         }
    //     } else {
    //         $this->basket = Basket::create();
    //     }
    //     Cookie::queue('basket_id', $this->basket->id, 525600);
    // }

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
            $products = [];
            return view('basket.index', compact('products'));
        }
    }

    // public function index()
    // {
    //     $products = $this->basket->products;
    //     dd($products);
    //     return view('basket.index', compact('products'));
    // }

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

    // public function add(Request $request, $id) {
    //     $quantity = $request->input('quantity') ?? 1;
    //     $this->basket->increase($id, $quantity);
    //     // выполняем редирект обратно на ту страницу,
    //     // где была нажата кнопка «В корзину»
    //     return back();
    // }

    public function saveOrder(Request $request)
    {
        
        $basket = Basket::getBasket();
        $user_id = auth()->check() ? auth()->user()->id : null;
        $order = Order::create(
            $request->all() + ['amount' => $basket->getAmount(), 'user_id' => $user_id]
        );

        foreach ($basket->products as $product) {
            $order->items()->create([
                'product_id' => $product->id,
                'name' => $product->name,
                'price' => $product->price,
                'quantity' => $product->pivot->quantity,
                'cost' => $product->price * $product->pivot->quantity,
            ]);
        }
        Cookie::queue(Cookie::forget('basket_id'));
        $basket->delete();


        return redirect()->route('basket.success')->with('order_id', $order->id);
    }

    public function success(Request $request)
    {
        if ($request->session()->exists('order_id')) {
            // сюда покупатель попадает сразу после успешного оформления заказа
            $order_id = $request->session()->pull('order_id');
            $order = Order::findOrFail($order_id);
            return view('basket.success', compact('order'));
        } else {
            // если покупатель попал сюда случайно, не после оформления заказа,
            // ему здесь делать нечего — отправляем на страницу корзины
            return redirect()->route('basket.index');
        }
    }
}