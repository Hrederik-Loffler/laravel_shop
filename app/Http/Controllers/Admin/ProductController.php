<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Http\Helpers\ImageSaver;
use App\Models\Category;

class ProductController extends Controller
{

    private $imageSaver;

    public function __construct(ImageSaver $imageSaver)
    {
        $this->imageSaver = $imageSaver;
    }

    /**
     * Show all products from category
     *
     * @return \Illuminate\Http\Response
     */
    public function category(Category $category)
    {
        $products = $category->products()->paginate(5);
        return view('admin.product.category', compact('category', 'products'));
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $products = Product::paginate(5);
        return view('admin.product.index', compact('products'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $items = Category::all();
        return view('admin.product.create', compact('items'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $request->all();
        $data['image'] = $this->imageSaver->upload($request, null, 'product');
        $product = Product::create($data);
        return redirect()->route('admin.product.show', ['product' => $product->id])->with('success', 'Новый товар успешно создан');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function show(Product $product, Category $category)
    {
        return view('admin.product.show', compact('product', 'category'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function edit(Product $product)
    {
        $items = Category::all();
        return view('admin.product.edit', compact('product', 'items'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Product $product)
    {
        $data = $request->all();
        $data['image'] = $this->imageSaver->upload($request, $product, 'product');
        $product->update($data);
        return redirect()->route('admin.product.show', ['product' => $product->id])->with('success', 'Товар был успешно обновлен');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product $product)
    {
        $this->imageSaver->remove($product, 'product');
        $product->delete();
        // return redirect()->route('admin.product.index')->with('success', 'Товар каталога успешно удален');
        return back()->with('success', 'Товар каталога успешно удален');
    }
}
