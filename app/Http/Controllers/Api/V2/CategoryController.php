<?php

namespace App\Http\Controllers\Api\V2;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCategoryRequest;
use App\Http\Resources\CategoryResource;
use App\Models\Category;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;
use OpenApi\Annotations as OA;

class CategoryController extends Controller
{

    /**
     * @OA\Get(
     *     path="/categorieds",
     *     tags={"Categories"},
     *     summary="Get List",
     *     @OA\Response(
     *         response="200",
     *          description="Succeful operation",
     *     ),
     *      @OA\Response(
     *          response="401",
     *           description="Unauntheticated",
     *      ),
     *      @OA\Response(
     *          response="403",
     *           description="Forbiden",
     *      ),
     * )
     */
    public function index()
    {
        abort_if(!auth()->user()->tokenCan('categories-list'),403);
        return CategoryResource::collection(Cache::rememberForever('categories',function(){
            return Category::all();
        }));
    }

    public function show(Category $category)
    {
        abort_if(!auth()->user()->tokenCan('categories-show'),403);
        return new CategoryResource($category);
    }

    public function list()
    {
        return CategoryResource::collection(Category::all());
    }

    public function store(StoreCategoryRequest $request)
    {
        $data = $request->all();

        if($request->hasFile('photo')){
            $file = $request->file('photo');//dolar file son todos los datos que tiene la imagen
            //elemento unico para cada valor
            $name='categories/'.Str::uuid().'.'.$file->extension();
            $file->storeAs('categories', $name,'public');
            $data['photo'] = $name;
        }
        $category=Category::create($data);
        return new CategoryResource($category);
    }

    public function update(Category $category, StoreCategoryRequest $request)
    {
        $category->update($request->all());
        return new CategoryResource($category);
    }

    public function destroy(Category $category)
    {
        $category->delete();
        //return response(null, Response::HTTP_NO_CONTENT); CUALQUIERA DE LA DOS SE PUEDE USAR
        return response()->noContent();
    }
}
