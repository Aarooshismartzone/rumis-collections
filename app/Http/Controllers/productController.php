<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\ProductInfo;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class productController extends Controller
{
    public function viewProducts()
    {
        if (Auth::check()) {
            $products = Product::with(['category', 'tags'])->get();
            return view('backend.products.view', [
                'user' => Auth::user(),
                'products' => $products,
            ]);
        }
        return redirect('/internal/login');
    }

    public function showAddProductForm($product_id = null)
    {
        if (Auth::check()) {
            $product = $product_id ? Product::with('tags')->findOrFail($product_id) : null;
            $categories = ProductCategory::all();
            $tags = Tag::all();
            $productinfo = $product_id ? ProductInfo::where('product_id', $product_id)->get() : null;
            return view('backend.products.add', compact('product', 'categories', 'tags', 'productinfo'));
        }
        return redirect('/internal/login');
    }

    public function storeProduct(Request $request, $product_id = null)
    {
        if (Auth::check()) {
            $request->validate([
                'product_category_id' => 'required|exists:productcategories,id',
                'product_name' => 'required|string|max:255',
                'description' => 'nullable|string',
                'actual_price' => 'required|numeric|min:0',
                'discounted_price' => 'nullable|numeric|min:0',
                'stock' => 'required|integer|min:0',
                'sku' => 'nullable|string|unique:products,sku,' . $product_id,
                'is_featured' => 'boolean',
                'image' => ($product_id ? 'nullable' : 'required') . '|image|mimes:jpeg,png,jpg,webp,jfif|max:7500',
                'ai_1' => 'nullable|image|mimes:jpeg,png,jpg,webp,jfif|max:7500',
                'ai_2' => 'nullable|image|mimes:jpeg,png,jpg,webp,jfif|max:7500',
                'ai_3' => 'nullable|image|mimes:jpeg,png,jpg,webp,jfif|max:7500',
                'ai_4' => 'nullable|image|mimes:jpeg,png,jpg,webp,jfif|max:7500',
                'ai_5' => 'nullable|image|mimes:jpeg,png,jpg,webp,jfif|max:7500',
                'ai_6' => 'nullable|image|mimes:jpeg,png,jpg,webp,jfif|max:7500',
                'tags' => 'nullable|string',
                'property' => 'nullable|array',
                'value' => 'nullable|array',
            ]);

            $imageFields = ['image', 'ai_1', 'ai_2', 'ai_3', 'ai_4', 'ai_5', 'ai_6'];

            if ($product_id) {
                $product = Product::findOrFail($product_id);
                $message = 'Product updated successfully!';


                foreach ($imageFields as $field) {
                    if ($request->hasFile($field)) {
                        if ($product->$field) {
                            Storage::disk('public')->delete($product->$field);
                        }
                        $product->$field = $request->file($field)->store('images/products', 'public');
                    }
                }
            } else {
                $product = new Product();
                $message = 'Product added successfully!';
            }

            $product->fill($request->only([
                'product_category_id',
                'product_name',
                'description',
                'actual_price',
                'discounted_price',
                'stock',
                'sku',
                'is_featured',
            ]));

            // Generate unique product slug
            $slug = Str::slug($request->product_name);
            $count = Product::where('product_slug', 'LIKE', "$slug%")
                ->where('id', '!=', $product_id)
                ->count();
            $product->product_slug = $count ? "$slug-$count" : $slug;
            $product->number_of_orders = 0;

            if($request->sizes){
                $product->product_size = $request->sizes;
            }

            foreach ($imageFields as $field) {
                if ($request->hasFile($field)) {
                    $product->$field = $request->file($field)->store('images/products', 'public');
                }
            }

            $product->save();

            // Handle tags (create new tags if they don’t exist)
            if ($request->filled('tags')) {
                $tagNames = explode(',', $request->tags);
                $tagIds = [];

                foreach ($tagNames as $tagName) {
                    $tagName = trim($tagName);
                    if (!$tagName) continue;

                    $tag = Tag::firstOrCreate([
                        'slug' => Str::slug($tagName),
                    ], [
                        'name' => $tagName
                    ]);

                    $tagIds[] = $tag->id;
                }

                // Attach tags to product
                $product->tags()->sync($tagIds);
            }

            //Store Product Info
            if ($product_id) {
                // Delete old product info if updating
                if (ProductInfo::where('product_id', $product_id)->count() > 0) {
                    ProductInfo::where('product_id', $product_id)->delete();
                }
            }

            if ($request->filled('property') && $request->filled('value')) {
                $properties = $request->property;
                $values = $request->value;

                foreach ($properties as $index => $property) {
                    if (!empty($property) && !empty($values[$index])) {
                        ProductInfo::create([
                            'category_id' => $product->product_category_id, // Associate with category
                            'product_id' => $product->id,
                            'property' => $property,
                            'value' => $values[$index],
                        ]);
                    }
                }
            }

            return redirect()->route('backend.products.view')->with('success', $message);
        }
        return redirect('/internal/login');
    }


    public function deleteProduct($id)
    {
        if (Auth::check()) {
            $product = Product::findOrFail($id);

            // List of image fields to check
            $imageFields = ['image', 'ai_1', 'ai_2', 'ai_3', 'ai_4', 'ai_5', 'ai_6'];

            foreach ($imageFields as $field) {
                if (!empty($product->$field) && Storage::disk('public')->exists($product->$field)) {
                    Storage::disk('public')->delete($product->$field);
                }
            }

            // Detach tags
            $product->tags()->detach();

            // Delete product
            $product->delete();

            return redirect()
                ->route('backend.products.view')
                ->with('success', 'Product and associated images deleted successfully!');
        }
        return redirect('/internal/login');
    }
}
