<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Basket;

class BasketController extends Controller
{
    public function index(Request $request)
    {
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
    public function add(Request $request, $id)
    {
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
        if ($basket->products->contains($id)){
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

    /**
     * Увеличивает кол-во товара $id в корзине на единицу
     */
    public function plus(Request $request, $id)
    {
        $basket_id = $request->cookie('basket_id');
        if (empty($basket_id)) {
            abort(404);
        }
        $this->change($basket_id, $id, 1);
        // выполняем редирект обратно на страницу корзины
        return redirect()
            ->route('basket.index')
            ->withCookie(cookie('basket_id', $basket_id, 525600));
    }

    /**
     * Уменьшает кол-во товара $id в корзине на единицу
     */
    public function minus(Request $request, $id)
    {
        $basket_id = $request->cookie('basket_id');
        if (empty($basket_id)) {
            abort(404);
        }
        $this->change($basket_id, $id, -1);
        // выполняем редирект обратно на страницу корзины
        return redirect()
            ->route('basket.index')
            ->withCookie(cookie('basket_id', $basket_id, 525600));
    }

    /**
     * Изменяет кол-во товара $product_id на величину $count
     */
    private function change($basket_id, $product_id, $count = 0)
    {
        if ($count == 0) {
            return;
        }
        $basket = Basket::findOrFail($basket_id);
        // если товар есть в корзине — изменяем кол-во
        if ($basket->products->contains($product_id)) {
            $pivotRow = $basket->products()->where('product_id', $product_id)->first()->pivot;
            $quantity = $pivotRow->quantity + $count;
            if ($quantity > 0) {
                // обновляем кол-во товара $product_id в корзине
                $pivotRow->update(['quantity' => $quantity]);
                // обновляем поле `updated_at` таблицы `baskets`
                $basket->touch();
            } else {
                // кол-во равно нулю — удаляем товар из корзины
                $pivotRow->delete();
            }
        }
    }

}