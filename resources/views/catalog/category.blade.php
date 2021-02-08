@extends('layouts.site')

@section('content')
    <h1>{{ $category->name }}</h1>

    <div class="row">
        @foreach ($category->products as $product)
            @include('catalog.part.product')
        @endforeach
    </div>
@endsection