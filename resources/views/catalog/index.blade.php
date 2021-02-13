@extends('layouts.site')

@section('content')
    <h1>Каталог товаров</h1>

    <p>
    Выберите категорию ниже. Категория содержит наименование товаров.
    </p>

    <h2>Разделы каталога</h2>
    <div class="row">
        @foreach ($categories as $category)
            @include('catalog.part.category', ['categpry' => $category])
        @endforeach
    </div>
@endsection