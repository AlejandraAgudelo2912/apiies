<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreProductRequest;
use App\Http\Resources\ProductResource;
use App\Models\Category;
use App\Models\Product;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::with('category', 'tags')->paginate(9);//para que me de los productos de la categoria asociada

        return ProductResource::collection($products);
    }

    public function show(Product $product)
    {

        return new ProductResource($product);
    }

    public function update(Product $product, StoreProductRequest $request)
    {
        $product->update($request->all());
        return new ProductResource($product);
    }

    public function destroy(Product $product)
    {
        $product->tags()->detach();
        $product->delete();
        return response()->noContent();
    }

    public function store(StoreProductRequest $request)
    {
        $product = Product::create($request->all());
        $product->tags()->sync($request->input('tags',[]));
        return new ProductResource($product);
    }

    public function byCategory(Category $category)
    {
        return $category->products;
    }

    public function byProduct(Category $category,Product $product)
    {
        if ($product->category_id !== $category->id) {
            return response()->json(['message' => 'The product does not belong to this category'], 404);
        }
        return new ProductResource($product);
    }
}
