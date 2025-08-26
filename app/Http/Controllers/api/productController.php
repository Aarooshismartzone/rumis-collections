<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductCategory;
use Illuminate\Http\Request;

class productController extends Controller
{
    public function getCategories(Request $request)
    {
        $categories = ProductCategory::with('children')->get();

        return response()->json([
            'status' => true,
            'message' => 'Product categories fetched successfully',
            'data' => $categories
        ], 200);
    }

    /**
     * Get all products
     */
    public function getProducts(Request $request)
    {
        $products = Product::with(['category', 'tags', 'productInfos'])->get();

        return response()->json([
            'status' => true,
            'message' => 'Products fetched successfully',
            'data' => $products
        ], 200);
    }

    public function getFeaturedProducts(Request $request)
{
    $products = Product::with(['category', 'tags', 'productInfos'])
                ->where('is_featured', true)
                ->get();

    return response()->json([
        'status' => true,
        'message' => 'Featured products fetched successfully',
        'data' => $products
    ], 200);
}


    /**
     * Get single product by ID
     */
    public function getProductById($id)
    {
        $product = Product::with(['category', 'tags', 'productInfos'])->find($id);

        if (!$product) {
            return response()->json([
                'status' => false,
                'message' => 'Product not found',
            ], 404);
        }

        return response()->json([
            'status' => true,
            'message' => 'Product fetched successfully',
            'data' => $product
        ], 200);
    }
}
