<?php

namespace App\Http\Controllers;

use App\Http\Resources\CategoryResource;
use App\Models\Category;
use Facade\FlareClient\Http\Response;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    use ValidatesRequests;

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Category $cagtegory)
    {
        $data = $request->all();
        $cagtegory->name = $data['name'];
        $cagtegory->description = $data['description'];
        $cagtegory->uuid = Str::orderedUuid();
        $cagtegory->save();
        return new CategoryResource($cagtegory);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show()
    {
        // $this->validateWith()
        return CategoryResource::collection(Category::paginate());

    }



    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($uuid , Request $request)
    {
        if(!$uuid){
            return response()->json([
                'error' => true,
                'msg' => '参数错误'
            ],404);
        }

        $category = Category::where('uuid','=',$uuid);

        if($category->get()->count() ){
            $category->delete();
            return response(null,204);
        }else{
            return response()->json([
                'error' => true,
                'msg' => '参数错误'
            ],404);
        }

    }
}
