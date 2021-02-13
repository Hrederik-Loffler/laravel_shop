<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Models\Category;
use Illuminate\Http\Request;
use App\Http\Helpers\ImageSaver;

class CategoryController extends Controller
{

    private $imageSaver;

    public function __construct(ImageSaver $imageSaver)
    {
        $this->imageSaver = $imageSaver;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $roots = Category::all();
        return view('admin.category.index', compact('roots'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $parents = Category::all();
        return view('admin.category.create', compact('parents'));
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
        $data['image'] = $this->imageSaver->upload($request, null, 'category');
        // $this->validate($request, [
        //     'name' => 'required|max:100',
        //     'slug' => 'required|max:100|unique:categories,slug|alpha_dash',
        // ]);
        // $category = Category::create($request->all());
        $category = Category::create($data);
        return redirect()->route('admin.category.show', ['category' => $category->id])->with('success', 'Новая категория успешно создана');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function show(Category $category)
    {  
        return view('admin.category.show', compact('category'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function edit(Category $category)
    {
        $parents = Category::all();
        return view('admin.category.edit', compact('parents', 'category'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Category $category)
    {   
        $data = $request->all();
        $data['image'] = $this->imageSaver->upload($request, null, 'category');
        $id = $category->id;
        $this->validate($request, [
            'name' => 'required|max:100',
            /*
             * Проверка на уникальность slug, исключая эту категорию по идентифкатору:
             * 1. categories — таблица базы данных, где проверяется уникальность
             * 2. slug — имя колонки, уникальность значения которой проверяется
             * 3. значение, по которому из проверки исключается запись таблицы БД
             * 4. поле, по которому из проверки исключается запись таблицы БД
             * Для проверки будет использован такой SQL-запрос к базе данныхЖ
             * SELECT COUNT(*) FROM `categories` WHERE `slug` = '...' AND `id` <> 17
             */
            'slug' => 'required|max:100|unique:categories,slug,'.$id.',id|alpha_dash'
        ]);
        // $category->update($request->all());
        $category->update($data);
        return redirect()->route('admin.category.show', ['category' => $category->id])->with('success', 'Категория была успешно исправлена');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function destroy(Category $category)
    {
        $category->delete();
        return redirect()->route('admin.category.index')->with('success', 'Категория каталога успешно удалена');
    
    }
}
