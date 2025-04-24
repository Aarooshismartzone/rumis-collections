<?php

namespace App\Http\Controllers;

use App\Models\ProductCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
        $request->validate([
            'category_name' => 'required|string|max:255',
            'parent_category' => 'nullable|exists:productcategories,id',
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
        $category->save();

        return redirect()->route('backend.categories.view')->with('success', $message);
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

            // Delete category
            $category->delete();
            return redirect()->route('backend.categories.view')
                ->with('success', 'Category deleted successfully!');
        }
        return redirect('/internal/login');
    }
}
