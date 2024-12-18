<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCategoryRequest;
use App\Http\Resources\CategoryResource;
use App\Models\Category;
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
        return CategoryResource::collection(Category::with('products')->get());
    }

    public function show(Category $category)
    {
        abort_if(!auth()->user()->tokenCan('categories-show'),403);
        return new CategoryResource(Category::with('products')->find($category->id));
    }

    public function list()
    {
        return CategoryResource::collection(Category::all());
    }

    public function store(StoreCategoryRequest $request)
    {
        abort_if(!auth()->user()->tokenCan('categories-store'),403);

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
        abort_if(!auth()->user()->tokenCan('categories-update'),403);

        $category->update($request->all());
        return new CategoryResource($category);
    }

    public function destroy(Category $category)
    {
        abort_if(!auth()->user()->tokenCan('categories-destroy'),403);

        $category->delete();
        //return response(null, Response::HTTP_NO_CONTENT); CUALQUIERA DE LA DOS SE PUEDE USAR
        return response()->noContent();
    }


}
