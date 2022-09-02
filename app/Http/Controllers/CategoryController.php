<?php

namespace App\Http\Controllers;

use App\Http\Requests\CategoryRequest;
use App\Http\Resources\CategoryResource;
use App\Models\Category;
use Facade\FlareClient\Http\Response;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    use ValidatesRequests;


    public function index(Request $request){

       return $this->success(collect(Category::paginate())->toArray());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CategoryRequest $request, Category $category)
    {
        $data = $request->all();
        $category->name = $data['name'];
        $category->description = $data['description'];
        $category->uuid = Str::orderedUuid();
        $category->save();
        return new CategoryResource($category);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Category $category)
    {
        return new CategoryResource($category);

    }



    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Category $category, CategoryRequest $request)
    {

        dd($request->all());
        $update_arr = [];
        $request->get('name') ? $update_arr['name']=$request->get('name'):false;
        $request->get('description') ? $update_arr['description']=$request->get('description'):false;
        if(!empty($update_arr)){
            // dd($category);
            $category->update($update_arr);
        }else{
            return $this->failed(20001,'数据格式错误');

        }

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Category $category, Request $request)
    {
        // 这个就是会返回一个 Category 的model对象
        // dd($category);

        // 这个会返回一个collection，奇怪
        // dd($category->get());

        $category->delete();
        return $this->success();
    }
}
