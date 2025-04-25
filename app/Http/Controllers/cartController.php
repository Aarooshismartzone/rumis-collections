<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use App\Models\Cart;
use App\Models\Generic;
use App\Models\Product;
use Illuminate\Support\Str;

class cartController extends Controller
{
    public function addToCart($product_slug)
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
            // If exists, update quantity
            $cartItem->increment('quantity');
        } else {
            // Add new item to cart
            Cart::create([
                'customer_id' => $customer_id,
                'guest_token' => $guest_token,
                'product_id' => $product->id,
                'quantity' => 1,
                'is_wishlist' => 0, // Default to cart, not wishlist
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
        // Determine user context (customer_id or guest_token)
        $customer_id = Session::get('customer_id');
        $guest_token = Session::get('guest_token');

        // Fetch relevant cart items
        $carts = Cart::with('product')->where(function ($query) use ($customer_id, $guest_token) {
            if ($customer_id) {
                $query->where('customer_id', $customer_id);
            } elseif ($guest_token) {
                $query->where('guest_token', $guest_token);
            }
        })->get();

        // Calculate total price
        $totalPrice = $carts->sum(fn($cart) => $cart->product->discounted_price * $cart->quantity);

        return view('frontend.cart._totals', compact('totalPrice'))->render();
    }
}
