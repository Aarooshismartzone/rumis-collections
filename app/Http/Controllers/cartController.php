<?php

namespace App\Http\Controllers;

use App\Models\Orderitem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use App\Models\Cart;
use App\Models\Generic;
use App\Models\Product;
use Illuminate\Support\Str;

class cartController extends Controller
{
    public function addToCart($product_slug, $product_size = null)
    {
        $product = Product::where('product_slug', $product_slug)->first();

        if (!$product) {
            return redirect()->back()->with('error', 'Product not found.');
        }

        // Check if product_size is required
        if ($product->category && $product->category->is_productsize) {
            if (!$product_size) {
                return redirect()->back()->with('error', 'Please select a size.');
            }

            $available_sizes = array_map('trim', explode(',', $product->product_size));
            if (!in_array($product_size, $available_sizes)) {
                return redirect()->back()->with('error', 'Invalid size selected.');
            }
        }

        // Session logic
        if (Session::has('customer_id')) {
            $customer_id = Session::get('customer_id');
            $guest_token = null;
        } else {
            if (!Session::has('guest_token')) {
                Session::put('guest_token', Str::random(32));
            }
            $customer_id = null;
            $guest_token = Session::get('guest_token');
        }

        // Check if item with same size is in cart
        $cartItem = Cart::where('product_id', $product->id)
            ->when($product_size, function ($query) use ($product_size) {
                $query->where('product_size', $product_size);
            })
            ->where(function ($query) use ($customer_id, $guest_token) {
                if ($customer_id) {
                    $query->where('customer_id', $customer_id);
                } else {
                    $query->where('guest_token', $guest_token);
                }
            })->first();

        if ($cartItem) {
            $cartItem->increment('quantity');
        } else {
            Cart::create([
                'customer_id' => $customer_id,
                'guest_token' => $guest_token,
                'product_id' => $product->id,
                'product_size' => $product_size,
                'quantity' => 1,
                'is_wishlist' => 0,
            ]);
        }

        return redirect()->back()->with('success', 'Product added to cart successfully!');
    }

    public function addToWishlist($product_slug)
    {
        // Find product by slug
        $product = Product::where('product_slug', $product_slug)->first();

        // If product doesn't exist, return back with an error message
        if (!$product) {
            return redirect()->back()->with('error', 'Product not found.');
        }

        // Check if the user is logged in
        if (Session::has('customer_id')) {
            $customer_id = Session::get('customer_id');
            $guest_token = null;
        } else {
            // If guest, check if guest_token exists, otherwise generate one
            if (!Session::has('guest_token')) {
                Session::put('guest_token', Str::random(32));
            }
            $customer_id = null;
            $guest_token = Session::get('guest_token');
        }

        // Check if the product is already in the cart
        $cartItem = Cart::where('product_id', $product->id)
            ->where(function ($query) use ($customer_id, $guest_token) {
                if ($customer_id) {
                    $query->where('customer_id', $customer_id);
                } else {
                    $query->where('guest_token', $guest_token);
                }
            })
            ->first();

        if ($cartItem) {
            if ($cartItem->is_wishlist) {
                return redirect()->back()->with('msg', 'Item Already in Wish List');
            } else {
                return redirect()->back()->with('msg', 'Item Already in Shopping Cart');
            }
        } else {
            // Add new item to cart
            Cart::create([
                'customer_id' => $customer_id,
                'guest_token' => $guest_token,
                'product_id' => $product->id,
                'quantity' => 1,
                'is_wishlist' => 1, // Default to wishlist
            ]);
        }

        return redirect()->back()->with('msg', 'Product added to wishlist successfully!');
    }

    public function moveToCart(Request $request, $id)
    {
        $cart = Cart::find($id);

        if (!$cart) {
            return redirect()->back()->with('msg', 'Cart item not found.');
        }

        // Authenticated customer
        if (Session::has('customer_id') && $cart->customer_id == Session::get('customer_id')) {
            $cart->is_wishlist = 0;
            if ($request->has('size')) {
                $cart->product_size = $request->size;
            }
            $cart->save();

            return redirect()->back()->with('msg', 'Product successfully moved to cart!');
        }

        // Guest user
        if (Session::has('guest_token') && $cart->guest_token == Session::get('guest_token')) {
            $cart->is_wishlist = 0;
            if ($request->has('size')) {
                $cart->product_size = $request->size;
            }
            $cart->save();

            return redirect()->back()->with('msg', 'Product successfully moved to cart!');
        }

        return redirect()->back()->with('msg', 'Unauthorized access.');
    }


    public function updateCart(Request $request)
    {
        $cart = Cart::find($request->cart_id);

        if (!$cart) {
            return response()->json(['success' => false, 'message' => 'Cart item not found.']);
        }
        $no_item = False;
        if ($request->action === 'increase') {
            $cart->increment('quantity');
        } elseif ($request->action === 'decrease') {
            if ($cart->quantity > 1) {
                $cart->decrement('quantity');
            } else {
                $cart->delete(); // Remove item if quantity reaches 0
                $no_item = True; //the item is not there in the cart
            }
        }

        $cart_empty = False;

        // Recalculate total price
        $carts = Cart::with('product')->where(function ($query) {
            if (Session::has('customer_id')) {
                $query->where('customer_id', Session::get('customer_id'));
            } elseif (Session::has('guest_token')) {
                $query->where('guest_token', Session::get('guest_token'));
            }
        })->get();

        if ($carts->isEmpty()) {
            $cart_empty = True; //there are no items in the cart. The cart is empty.
        }

        $totalPrice = $carts->sum(function ($cart) {
            return $cart->product->discounted_price * $cart->quantity;
        });

        return response()->json([
            'success' => true,
            'newQuantity' => $cart->quantity ?? 0, // If deleted, return 0
            'itemRate' => $cart->product->discounted_price,
            'totalPrice' => number_format($totalPrice, 2),
            'no_item' => $no_item,
            'cart_empty' => $cart_empty
        ]);
    }

    public function refreshCartTotals()
    {
        $customer_id = Session::get('customer_id');
        $guest_token = Session::get('guest_token');

        $carts = Cart::with('product')->where(function ($query) use ($customer_id, $guest_token) {
            if ($customer_id) {
                $query->where('customer_id', $customer_id);
            } elseif ($guest_token) {
                $query->where('guest_token', $guest_token);
            }
        })->get();

        $totalPrice = $carts->sum(fn($cart) => $cart->product->discounted_price * $cart->quantity);

        $generics = Generic::pluck('value', 'key')->toArray();

        return view('frontend.cart._totals', compact('totalPrice', 'generics'))->render();
    }
}
