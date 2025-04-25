<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\ProductInfo;
use App\Models\Cart;
use App\Models\Generic;
use App\Models\Order;
use App\Models\Orderitem;
use App\Models\ProductCategory;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;

class frontendController extends Controller
{
    private $cartCount;
    private $wishlistCount;

    public function __construct()
    {
        $this->cartCount = $this->getCartCount();
        $this->wishlistCount = $this->getWishlistCount();

        // Share cartCount globally with all views
        view()->share([
            'cartCount' => $this->cartCount,
            'wishlistCount' => $this->wishlistCount,
        ]);
    }

    public function home()
    {
        $products = Product::all();
        $customer = Session::has('customer_id') ? Customer::find(Session::get('customer_id')) : null;

        return view('frontend.home', compact('products', 'customer'));
    }

    public function shop($category_slug = null)
    {
        $query = Product::query();

        if ($category_slug) {
            $category = ProductCategory::where('category_slug', $category_slug)->first();

            if ($category) {
                $query->where('product_category_id', $category->id);
            }
        } else {
            $category = null;
        }

        $products = $query->paginate(20);
        $customer = Session::has('customer_id') ? Customer::find(Session::get('customer_id')) : null;
        $categories = ProductCategory::all(); // For dropdown menu

        return view('frontend.shop', compact('products', 'customer', 'category', 'categories'));
    }


    public function viewProduct($product_slug)
    {
        $product = Product::where('product_slug', $product_slug)->firstOrFail();
        $productinfos = ProductInfo::where('product_id', $product->id)->get();

        return view('frontend.item', compact('product', 'productinfos'));
    }

    public function viewCart()
    {
        if (Session::has('customer_id')) {
            $carts = Cart::where('customer_id', Session::get('customer_id'))
                ->where('is_wishlist', 0)
                ->get();
        } elseif (Session::has('guest_token')) {
            $carts = Cart::where('guest_token', Session::get('guest_token'))
                ->where('is_wishlist', 0)
                ->get();
        } else {
            $carts = collect(); // empty collection
        }

        $totalPrice = $carts->sum(function ($cart) {
            return $cart->product->discounted_price * $cart->quantity;
        });

        return view('frontend.shopping-cart', compact('carts', 'totalPrice'));
    }

    public function viewWishlist()
    {
        if (Session::has('customer_id')) {
            $carts = Cart::where('customer_id', Session::get('customer_id'))
                ->where('is_wishlist', 1)
                ->get();
        } elseif (Session::has('guest_token')) {
            $carts = Cart::where('guest_token', Session::get('guest_token'))
                ->where('is_wishlist', 1)
                ->get();
        } else {
            $carts = collect(); // empty collection
        }

        return view('frontend.wishlist', compact('carts'));
    }

    public function viewCheckoutPage()
    {
        // Determine if user is logged in or a guest
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

        // Fetch cart items based on customer_id or guest_token
        $carts = Cart::with('product')
            ->when($customer_id, function ($query) use ($customer_id) {
                return $query->where('customer_id', $customer_id);
            })
            ->when($guest_token, function ($query) use ($guest_token) {
                return $query->where('guest_token', $guest_token);
            })
            ->get();

        $generics = Generic::pluck('value', 'key')->toArray();

        // Convert 'is_gst' string to boolean
        $gstEnabled = filter_var($generics['is_gst'] ?? false, FILTER_VALIDATE_BOOLEAN);
        $gstRate = $gstEnabled ? (float) $generics['gst_rate_percent'] : 0;

        // Calculate totals
        $totalPrice = collect($carts)->sum(function ($item) {
            return $item->product->discounted_price * $item->quantity;
        });
        $deliveryCharge = isset($generics['delivery_charges']) ? (float) $generics['delivery_charges'] : 0;
        $gstAmount = ($gstRate / 100) * $totalPrice;
        $grandTotal = $totalPrice + $deliveryCharge + $gstAmount;

        // Store pricing info in session
        Session::put('checkout_summary', [
            'total_price' => $totalPrice,
            'delivery_charge' => $deliveryCharge,
            'gst_amount' => $gstAmount,
            'grand_total' => $grandTotal
        ]);

        return view('frontend.checkout', compact(
            'carts',
            'totalPrice',
            'deliveryCharge',
            'gstAmount',
            'grandTotal',
            'generics'
        ));
    }

    public function viewSummary()
    {
        if (!session()->has('checkout_summary')) {
            return redirect()->route('checkout')->with('error', 'No order summary found.');
        }

        $summary = session('checkout_summary');
        //dd($summary);
        return view('frontend.order-summary', compact('summary'));
    }

    public function paymentSuccess()
    {
        $cartItems = Cart::where('is_wishlist', 'no')->get();
        $cs = Session::get('checkout_summary');
        $orderId = $cs['order_id'];
        $order = Order::find($orderId);

        if (!$order) {
            return redirect()->route('frontend.home')->with('error', 'Order not found.');
        }

        foreach ($cartItems as $item) {
            $product = $item->product;

            if ($product) {
                $product->increment('number_of_orders', $item->quantity);
            }

            Orderitem::create([
                'order_id' => $order->id,
                'product_id' => $item->product_id,
                'quantity' => $item->quantity,
            ]);
        }

        $order->order_status = 'payment-success';
        $order->save();

        Cart::where('is_wishlist', 'no')->delete();

        Session::forget('checkout_summary');

        return view('frontend.thankyou', compact('orderId'));
    }



    private function getCartCount()
    {
        if (Session::has('customer_id')) {
            return Cart::where('customer_id', Session::get('customer_id'))
                ->where('is_wishlist', 0)
                ->count();
        } elseif (Session::has('guest_token')) {
            return Cart::where('guest_token', Session::get('guest_token'))
                ->where('is_wishlist', 0)
                ->count();
        } else {
            Session::put('guest_token', Str::random(32));
            return Cart::where('guest_token', Session::get('guest_token'))
                ->where('is_wishlist', 0)
                ->count();
        }

        return 0;
    }

    private function getWishlistCount()
    {
        if (Session::has('customer_id')) {
            return Cart::where('customer_id', Session::get('customer_id'))
                ->where('is_wishlist', 1)
                ->count();
        } elseif (Session::has('guest_token')) {
            return Cart::where('guest_token', Session::get('guest_token'))
                ->where('is_wishlist', 1)
                ->count();
        } else {
            Session::put('guest_token', Str::random(32));
            return Cart::where('guest_token', Session::get('guest_token'))
                ->where('is_wishlist', 1)
                ->count();
        }

        return 0;
    }
}
