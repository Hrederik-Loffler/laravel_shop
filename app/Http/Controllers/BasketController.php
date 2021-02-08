<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Basket;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class BasketController extends Controller
{

    // public $basket;

    // public function __construct() {
    //     $this->getBasket = Basket::getBasket();
    // }

    public function index(Request $request) {
                $basket_id = $request->cookie('basket_id');
                if (!empty($basket_id)) {
                    $products = Basket::findOrFail($basket_id)->products;
                    return view('basket.index', compact('products'));
                } else {
                    abort(404);
                }
            }
        
            public function checkout() {
                return view('basket.checkout');
            }
        
            /**
             * Добавляет товар с идентификатором $id в корзину
             */
            public function add(Request $request, $id) {
                $basket_id = $request->cookie('basket_id');
                $quantity = $request->input('quantity') ?? 1;
                if (empty($basket_id)) {
                    // если корзина еще не существует — создаем объект
                    $basket = Basket::create();
                    // получаем идентификатор, чтобы записать в cookie
                    $basket_id = $basket->id;
                } else {
                   // корзина уже существует, получаем объект корзины
                    $basket = Basket::findOrFail($basket_id);
                    // обновляем поле `updated_at` таблицы `baskets`
                    $basket->touch();
                }
                if ($basket->products->contains($id)) {
                    // если такой товар есть в корзине — изменяем кол-во
                    $pivotRow = $basket->products()->where('product_id', $id)->first()->pivot;
                    $quantity = $pivotRow->quantity + $quantity;
                    $pivotRow->update(['quantity' => $quantity]);
               } else {
                    // если такого товара нет в корзине — добавляем его
                    $basket->products()->attach($id, ['quantity' => $quantity]);
                }
               // выполняем редирект обратно на страницу, где была нажата кнопка «В корзину»
                return back()->withCookie(cookie('basket_id', $basket_id, 525600));
            }
        




    

    // /**
    //  * Показывает корзину покупателя
    //  */
    // public function index() {
    //     $products = $this->basket->products;
    //     return view('basket.index', compact('products'));
    // }

    // /**
    //  * Форма оформления заказа
    //  */
    // public function checkout() {
    //     return view('basket.checkout');
    // }

    // /**
    //  * Добавляет товар с идентификатором $id в корзину
    //  */
    // public function add(Request $request, $id) {
    //     $quantity = $request->input('quantity') ?? 1;
    //     $this->basket->increase($id, $quantity);
    //     // выполняем редирект обратно на ту страницу,
    //     // где была нажата кнопка «В корзину»
    //     return back();
    // }

    // /**
    //  * Увеличивает кол-во товара $id в корзине на единицу
    //  */
    // public function plus($id) {
    //     $this->basket->increase($id);
    //     // выполняем редирект обратно на страницу корзины
    //     return redirect()->route('basket.index');
    // }

    // /**
    //  * Уменьшает кол-во товара $id в корзине на единицу
    //  */
    // public function minus($id) {
    //     $this->basket->decrease($id);
    //     // выполняем редирект обратно на страницу корзины
    //     return redirect()->route('basket.index');
    // }

    // /**
    //  * Возвращает объект корзины; если не найден — создает новый
    //  */
    // public function getBasket()
    // {
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

    // /**
    //  * Удаляет товар с идентификаторм $id из корзины
    //  */
    // public function remove($id) {
    //     $this->basket->remove($id);
    //     // выполняем редирект обратно на страницу корзины
    //     return redirect()->route('basket.index');
    // }

    /**
     * Полностью очищает содержимое корзины покупателя
     */
    // public function clear() {
    //     $this->basket->delete();
    //     // выполняем редирект обратно на страницу корзины
    //     return redirect()->route('basket.index');
    // }
}