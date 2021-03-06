<!doctype html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Магазин</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <script src="{{ asset('js/app.js') }}"></script>
</head>
<body>
<div class="wrapper">
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark mb-4">
        <!-- кнопка «Гамбургер» -->
        <a class="navbar-brand" href="{{ route('index') }}">Магазин</a>
        <button class="navbar-toggler burger-button" type="button" data-toggle="collapse"
                data-target="#navbar-example" aria-controls="navbar-larashop"
                aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <!-- Основная часть меню (может содержать ссылки, формы и прочее) -->
        <div class="collapse navbar-collapse" id="navbar-larashop">
            <!-- Этот блок расположен слева -->
            <ul class="navbar-nav mr-auto">
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('catalog.index') }}">Каталог</a>
                </li>
            </ul>

        @if(isset($_SESSION['product']))
                <!-- Этот блок расположен посередине -->
                <form class="form-inline my-2 my-lg-0" method="post">
                    @csrf
                    <input id="searchProduct" class="form-control mr-sm-2" type="search"
                           placeholder="Поиск по каталогу" aria-label="Search">
                    <!-- <button class="btn btn-outline-info my-2 my-sm-0"
                            type="submit">Искать</button> -->
                </form>
            @else
            @endif
        <!-- Этот блок расположен справа -->
            <ul class="navbar-nav ml-auto">
                <li class="nav-item">
                    <a class="nav-link link-basket @if ($position ?? '') text-success @endif" href="{{ route('basket.index') }}">
                        Корзина
                        @if ($position ?? '') ({{$position ?? ''}}) @endif
                    </a>
                </li>
                @guest
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('user.login') }}">Войти</a>
                    </li>
                @else
                    <li class="nav-item dropdown">
                        <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown"
                           aria-haspopup="true" aria-expanded="false" v-pre>
                            {{ Auth::user()->name }}
                        </a>
                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                            <a class="dropdown-item" href="{{ route('user.logout') }}"
                               onclick="event.preventDefault();
                                            document.getElementById('logout-form').submit();">
                                {{ __('Logout') }}
                            </a>
                            <form id="logout-form" action="{{ route('user.logout') }}" method="POST" class="d-none">
                                @csrf
                            </form>
                        </div>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('user.index') }}">Личный кабинет</a>
                    </li>
                @endguest
            </ul>
        </div>
    </nav>
</div>
<div class="container">
    <div class="row">
        <div class="col-md-3 author-info">
            <p>Андрей</p>
            <a href="tel:+380963774077">+380963774077</a>
            <a href="tel:+380665230922">+380665230922</a>
        </div>
        <div class="col-md-9">
            @if ($message = Session::get('success'))
                <div class="alert alert-success alert-dismissible mt-0" role="alert">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Закрыть">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    {{ $message }}
                </div>
            @endif

            @if ($errors->any())
                <div class="alert alert-danger alert-dismissible mt-0" role="alert">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Закрыть">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            @yield('content')
        </div>
    </div>
</div>
<script>
    var searchInput = document.querySelector("#searchProduct");

    if(searchInput) {
        searchInput.addEventListener('keyup', function(){
            var filter, list, li, a, i;
            filter = searchInput.value.toUpperCase();
            list = document.querySelector(".list-products");
            li = list.querySelectorAll(".item-products");

            if (li.length) {
                for (i = 0; i < li.length; i++) {
                    a = li[i].querySelector(".item-products-name");
                    if (a.textContent.toUpperCase().indexOf(filter) > -1) {
                        li[i].style.display = "";
                    } else {
                        li[i].style.display = "none";
                    }
                }
            }
        });
    }

    var burger = document.querySelector('.burger-button');
    var body = document.querySelector('body');

    burger.addEventListener('click', function() {
        body.classList.toggle('menu-open');
    });

</script>
</body>
</html>
