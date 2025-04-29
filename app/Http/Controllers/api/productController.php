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



// I will be creating the android app right now. It will be done using Java and XML. The launching page shall show the logo, which will be there for 3 seconds. The token should be generated in the meantime. Also, I need to store the login creds in the SQLIte database so that if the user had already logged in once, they need not log in again.