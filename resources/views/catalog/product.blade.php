@extends('layouts.site')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h1>{{ $product->name }}</h1>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <!-- <img src="https://via.placeholder.com/400x400" alt="" class="img-fluid"> -->
                        <img src="{{ asset('storage/catalog/product/image/'.$product->image) }}" height="300px" width="400px" alt="">
                    </div>
                    <div class="col-md-6">
                        <p>Цена: {{ number_format($product->price, 2, '.', '') }}</p>

                        <form action="{{ route('basket.add', ['id' => $product->id]) }}"
                            method="post" class="form-inline">
                            @csrf
                            <label for="input-quantity">Количество</label>
                            <input type="text" name="quantity" id="input-quantity" value="1"
                                class="form-control mx-2 w-25">
                            <button id="bttn-add" type="submit" class="btn btn-success">Добавить в корзину</button>
                        </form>
                        
                    </div>
                </div>
            </div>
            <div class="card-footer">
                <div class="row">
                    <div class="col-md-6">
                        Категория:
                        <a href="{{ route('catalog.category', ['slug' => $product->category->slug]) }}">
                            {{ $product->category->name }}
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        var bttn = document.querySelector('#bttn-add');
        var link = document.querySelector('.link-basket');

        bttn.addEventListener("click", function() {
            sessionStorage.setItem('added-product', 'worked');
        })

        if (sessionStorage.getItem("added-product")) {
            link.classList.add('added-to-basket');
        }
    })
</script>
@endsection