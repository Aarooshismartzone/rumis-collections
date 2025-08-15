<?php

namespace App\Http\Controllers;

use App\Models\ProductCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class productCategoryController extends Controller
{
    public function viewCategories()
    {
        if (Auth::check()) {
            $categories = ProductCategory::all();
            return view('backend.product-categories.view', [
                'user' => Auth::user(),
                'categories' => $categories,
            ]);
        }
        return redirect('/internal/login');
    }

    public function showAddCategoryForm($category_id = null)
    {
        if (Auth::check()) {
            $category = null;
            if ($category_id) {
                $category = ProductCategory::findOrFail($category_id);
            }
            $categories = ProductCategory::whereNull('parent_category')->get();
            return view('backend.product-categories.add', compact('category', 'categories'));
        }
        return redirect('/internal/login');
    }

    public function storeCategory(Request $request, $category_id = null)
    {
        if (Auth::check()) {
            $request->validate([
                'category_name' => 'required|string|max:255',
                'parent_category' => 'nullable|exists:productcategories,id',
                'category_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            ]);

            if ($category_id) {
                $category = ProductCategory::findOrFail($category_id);
                $message = 'Category updated successfully!';
            } else {
                $category = new ProductCategory();
                $message = 'Category added successfully!';
            }

            $category->category_name = $request->category_name;
            $category->category_slug = Str::slug($request->category_name);
            $category->parent_category = $request->parent_category;

            // Handle image upload
            if ($request->hasFile('category_image')) {
                // Delete old image if exists
                if (!empty($category->category_image) && Storage::disk('public')->exists($category->category_image)) {
                    Storage::disk('public')->delete($category->category_image);
                }
                $category->category_image = $request->file('category_image')->store('images/productcategories', 'public');
            }

            $category->save();

            return redirect()->route('backend.categories.view')->with('success', $message);
        }
        return redirect('/internal/login');
    }

    public function deleteCategory($id)
    {
        if (Auth::check()) {
            $category = ProductCategory::findOrFail($id);

            // Check if the category has subcategories
            if ($category->children()->count() > 0) {
                return redirect()->route('backend.categories.view')
                    ->with('error', 'Cannot delete category with subcategories!');
            }

            if (!empty($category->category_image) && Storage::disk('public')->exists($category->category_image)) {
                    Storage::disk('public')->delete($category->category_image);
                }
            // Delete category
            $category->delete();
            return redirect()->route('backend.categories.view')
                ->with('success', 'Category deleted successfully!');
        }
        return redirect('/internal/login');
    }
}
