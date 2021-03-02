<div class="col-md-6 mb-4">
    <div class="card">
        <div class="card-header text-center">
            <h4>{{ $product->name }}
                <span style="color: green">{{ $product->price }} грн</span>
            </h4>
        </div>
        <div class="card-body p-0">
            <!-- <img src="https://via.placeholder.com/400x120" alt="" class="img-fluid"> -->
            <img src="{{ asset('storage/catalog/product/image/' . $product->image) }}" min-height="400px" min-width="120px" alt="" class="img-fluid">
        </div>
        <div class="card-footer text-center">
            <a href="{{ route('catalog.product', ['slug' => $product->slug]) }}"
                class="btn btn-dark">Перейти к товару</a>
        </div>
    </div>
</div>
