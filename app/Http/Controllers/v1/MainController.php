<?php

namespace App\Http\Controllers\v1;

use App\Http\Controllers\Controller;
use App\Http\Resources\CategoryResource;
use App\Http\Resources\MovementResource;
use App\Http\Resources\ProductResource;
use App\Models\Category;
use App\Models\Movement;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Services\ApiResponse;

class MainController extends Controller
{

    public function status()
    {
        return ApiResponse::success([
            'name'       => 'Bruno Rizzo',
            'email'      => 'bruno@email.com',
            'date'       => now()->toDateString(),
            'apiVersion' => 'v1'
        ], 'API is running ok');
    }


     public function listCategories()
    {

        $perPage = request()->input('per_page', 5);
        $categories = Category::paginate($perPage);

        return ApiResponse::success([
            'categories' => CategoryResource::collection($categories),
            'pagination' => [
                'current_page' => $categories->currentPage(),
                'last_page'    => $categories->lastPage(),
                'per_page'     => $categories->perPage(),
                'total'        => $categories->total(),
            ]
        ]);

    }


     public function listProducts()
    {
        $perPage = request()->input('per_page', 5);
        $products = Product::paginate($perPage);

        return ApiResponse::success([
            'products' => ProductResource::collection($products),
            'pagination' => [
                'current_page' => $products->currentPage(),
                'last_page'    => $products->lastPage(),
                'per_page'     => $products->perPage(),
                'total'        => $products->total(),
            ]
        ],'list of products get with success');
    }


    public function listMovements()
    {
        $perPage = request()->input('per_page', 10);
        $movements = Movement::paginate($perPage);

        return ApiResponse::success([
            'movements' => MovementResource::collection($movements),
            'pagination' => [
                'current_page' => $movements->currentPage(),
                'last_page'    => $movements->lastPage(),
                'per_page'     => $movements->perPage(),
                'total'        => $movements->total(),
            ]
        ],'list of movements');

    }


    public function listMovementsOrdered($field, $direction)
    {

        $validFields     = ['id', 'product_id', 'quantity', 'movement_type', 'created_at', 'updated_at'];
        $validDirections = ['asc', 'desc'];

        if(!in_array($field, $validFields)){
            return ApiResponse::error("Invalid field for ordering: {$field}", 400);
        }

        if(!in_array($direction, $validDirections)){
            return ApiResponse::error("Invalid direction for ordering: {$direction}", 400);
        }

        $perPage = request()->input('per_page', 10);

        $movements = Movement::orderBy($field, $direction)
                             ->paginate($perPage);

        return ApiResponse::success([
            'movements' => MovementResource::collection($movements),
            'pagination' => [
                'current_page' => $movements->currentPage(),
                'last_page'    => $movements->lastPage(),
                'per_page'     => $movements->perPage(),
                'total'        => $movements->total(),
            ]
        ],'list of movements');

    }


    public function getCategory($id)
    {

       $category = Category::find($id);

       if(!$category){
            return ApiResponse::error("Category with ID {$id} not found.", 404);
       }

       return ApiResponse::success([
            'category' => new CategoryResource($category)
       ]);

    }


    public function getProduct($id)
    {

       $product = Product::find($id);

       if(!$product){
            return ApiResponse::error("Product with ID {$id} not found.", 404);
       }

       return ApiResponse::success([
            'product' => new ProductResource($product)
       ]);

    }


     public function getProductsByCategory($id)
    {

        $category = Category::find($id);

         if(!$category){
            return ApiResponse::error("Category with ID {$id} not found.", 404);
        }

        $products = Product::where('category_id', $id)
                    ->get()
                    ->toResourceCollection(ProductResource::class)
                    ->resolve();

        $products = array_map(function ($product) {
            unset($product['category']);
            return $product;
        }, $products);

        return ApiResponse::success([
        'category'      => new CategoryResource($category),
        'products'      => $products,
        'totalProducts' => count($products),
         ]);

    }


    public function createCategory(Request $request)
    {
        $data = $request->validate([
            'name'        => 'required|string|max:50|unique:categories,name',
            'description' => 'nullable|string',
        ]);

        $category = Category::create($data);

        return ApiResponse::success([
            'category' => new CategoryResource($category)],
            'Category created successfully',
            201);
    }


    public function createProduct(Request $request)
    {
         $data = $request->validate([
            'name'        => 'required|string|max:50|unique:products,name',
            'description' => 'nullable|string',
            'category_id' => 'required|exists:categories,id'
        ]);

        $product = Product::create($data);

        return ApiResponse::success([
            'product' => new ProductResource($product)],
            'Product created successfully',
            201);
    }


    public function createMovement(Request $request)
    {
         $data = $request->validate([
            'product_id'    => 'required|exists:products,id',
            'quantity'      => 'required|integer|min:1',
            'movement_type' => 'required|in:in,out',
        ]);

        $movement = Movement::create($data);

        return ApiResponse::success([
            'movement' => new MovementResource($movement)],
            'Movement created successfully',
            201);
    }


    public function updateCategory(Request $request, $id)
    {
        $category = Category::find($id);

        if(!$category){
            return ApiResponse::error("Caterory with ID {$id} not found", 404);
        }

        $data = $request->validate([
            'name'        => 'string|max:50|unique:categories,name,'.$id,
            'description' => 'nullable|string',
        ]);

        $category->update($data);

        return ApiResponse::success([
            'category' => new CategoryResource($category)],
            'Category updated successfully',
            );
    }


    public function updateProduct(Request $request, $id)
    {
        $product = Product::find($id);

        if(!$product){
            return ApiResponse::error("Product with ID {$id} not found", 404);
        }

        $data = $request->validate([
            'name'        => 'string|max:50|unique:products,name,'.$id,
            'description' => 'nullable|string',
            'category_id' => 'exists:categories,id',
        ]);

        $product->update($data);

        return ApiResponse::success([
            'product' => new ProductResource($product)],
            'Product updated successfully',
            );
    }


    public function updateMovement(Request $request, $id)
    {
        $movement = Movement::find($id);

        if(!$movement){
            return ApiResponse::error("Movement with ID {$id} not found", 404);
        }

        $data = $request->validate([
            'product_id'    => 'integer|exists:products,id',
            'quantity'      => 'integer|min:1',
            'movement_type' => 'in:in,out',
        ]);

        $movement->update($data);

        return ApiResponse::success([
            'movement' => new MovementResource($movement)],
            'Movement updated successfully',
            );
    }


    public function deleteMovement($id)
    {
        $movement = Movement::find($id);

        if(!$movement){
            return ApiResponse::error("Movement with ID {$id} not found", 404);
        }

        $movement->delete();

         return ApiResponse::success([],'Movement deleted successfully');
    }


    public function deleteProduct($id)
    {
        $product = Product::find($id);

        if(!$product){
            return ApiResponse::error("Product with ID {$id} not found", 404);
        }

        $product->delete();

         return ApiResponse::success([],'Product deleted successfully');
    }


    public function deleteCategory($id)
    {
        $category = Category::find($id);

        if(!$category){
            return ApiResponse::error("Caterory with ID {$id} not found", 404);
        }

        $category->delete();

         return ApiResponse::success([],'Category deleted successfully');
    }


}
