@extends('layouts.site')

@section('content')
    <h1>Каталог товаров</h1>

    <p>
    Lorem ipsum dolor sit amet, consectetur adipisicing elit. Atque ducimus, eligendi
    exercitationem expedita, iure iusto laborum magnam qui quidem repellat similique
    tempora tempore ullam! Deserunt doloremque impedit quis repudiandae voluptas?
    </p>

    <h2>Разделы каталога</h2>
    <div class="row">
        @foreach ($categories as $category)
            @include('catalog.part.category', ['categpry' => $category])
        @endforeach
    </div>
@endsection