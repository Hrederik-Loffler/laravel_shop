<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Models\Category;
use App\Models\Basket;

class ComposerServiceProvider extends ServiceProvider {
    /* ... */
    public function boot() {
        View::composer('layout.part.roots', function($view) {
            $view->with(['items' => Category::roots()]);
        });
        View::composer('layout.site', function($view) {
            $view->with(['positions' => Basket::getBasket()->products->count()]);
        });
        View::composer('layout.site', function($view) {
            $view->with(['positions' => Basket::getCount()]);
        });
    }
}