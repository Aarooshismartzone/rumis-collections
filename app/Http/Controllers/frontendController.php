<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Customeraddress;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\ProductInfo;
use App\Models\Cart;
use App\Models\Generic;
use App\Models\Note;
use App\Models\Order;
use App\Models\Orderitem;
use App\Models\ProductCategory;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
use PHPMailer\PHPMailer\Exception;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use PHPMailer\PHPMailer\PHPMailer;

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
        $products = Product::where('stock', '>', 0)->get();
        $customer = Session::has('customer_id') ? Customer::find(Session::get('customer_id')) : null;

        return view('frontend.home', compact('products', 'customer'));
    }

    public function about()
    {
        $customer = Session::has('customer_id') ? Customer::find(Session::get('customer_id')) : null;
        return view('frontend.about', compact('customer'));
    }

    public function contact()
    {
        $customer = Session::has('customer_id') ? Customer::find(Session::get('customer_id')) : null;
        return view('frontend.contact', compact('customer'));
    }

    public function privacyPolicy()
    {
        $customer = Session::has('customer_id') ? Customer::find(Session::get('customer_id')) : null;
        return view('frontend.privacy-policy', compact('customer'));
    }

    public function refundPolicy()
    {
        $customer = Session::has('customer_id') ? Customer::find(Session::get('customer_id')) : null;
        return view('frontend.refund-policy', compact('customer'));
    }

    public function cancellationPolicy()
    {
        $customer = Session::has('customer_id') ? Customer::find(Session::get('customer_id')) : null;
        return view('frontend.cancellation-policy', compact('customer'));
    }

    public function termsAndConditions()
    {
        $customer = Session::has('customer_id') ? Customer::find(Session::get('customer_id')) : null;
        return view('frontend.terms-and-conditions', compact('customer'));
    }

    public function shipping()
    {
        $customer = Session::has('customer_id') ? Customer::find(Session::get('customer_id')) : null;
        return view('frontend.shipping', compact('customer'));
    }

    public function shop($category_slug = null)
    {
        $query = Product::query()->where('stock', '>', 0);

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

        // // --- View Tracking Logic ---
        // $productId = $product->id;
        // $userIp = request()->ip();
        // $customerId = Session::get('customer_id');

        // // Build unique key for tracking view (per product + user)
        // $viewKey = $customerId
        //     ? 'product_view_customer_' . $customerId . '_' . $productId
        //     : 'product_view_guest_' . $userIp . '_' . $productId;

        // $lastViewed = Cache::get($viewKey);

        // // Check if viewed more than 24 hours ago (or not viewed yet)
        // if (!$lastViewed || now()->diffInHours($lastViewed) >= 24) {
        //     // Increment the view count
        //     $product->increment('view');

        //     // Store current timestamp in cache for 24 hours reference
        //     Cache::put($viewKey, now(), now()->addHours(24));
        // }

        // // --- End of View Tracking ---

        return view('frontend.item', compact('product', 'productinfos'));
    }

    public function search(Request $request)
    {
        $query = $request->input('query');

        $products = Product::where('product_name', 'like', "%$query%")
            ->orWhere('description', 'like', "%$query%")
            ->orWhereHas('category', function ($q) use ($query) {
                $q->where('category_name', 'like', "%$query%");
            })
            ->orWhereHas('tags', function ($q) use ($query) {
                $q->where('name', 'like', "%$query%");
            })
            ->orWhereHas('productInfos', function ($q) use ($query) {
                $q->where('value', 'like', "%$query%");
            })
            ->where('stock', '>', 0)
            ->paginate(12);

        return view('frontend.shop', compact('products', 'query'));
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

            // Fetch saved addresses for this customer
            $addresses = Customeraddress::where('customer_id', $customer_id)->get();
        } else {
            // If guest, check if guest_token exists, otherwise generate one
            if (!Session::has('guest_token')) {
                Session::put('guest_token', Str::random(32));
            }
            $customer_id = null;
            $guest_token = Session::get('guest_token');

            // No saved addresses for guests
            $addresses = collect();
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

        $errors = [];

        foreach ($carts as $cartitem) {
            $product = $cartitem->product;

            if ($product->stock == 0) {
                $errors[] = "The product '{$product->name}' is out of stock.";
            } elseif ($product->stock < $cartitem->quantity) {
                $errors[] = "Insufficient stock for '{$product->name}'. Available: {$product->stock}, requested: {$cartitem->quantity}.";
            }
        }

        if (!empty($errors)) {
            return back()->with('msg', implode(' ', $errors));
        }


        $generics = Generic::pluck('value', 'key')->toArray();

        // Convert 'is_gst' string to boolean
        $gstEnabled = filter_var($generics['is_gst'] ?? false, FILTER_VALIDATE_BOOLEAN);
        $gstRate = $gstEnabled ? (float) $generics['gst_rate_percent'] : 0;

        // Calculate totals
        $totalPrice = collect($carts)->sum(function ($item) {
            return $item->product->discounted_price * $item->quantity;
        });
        $freeDeliveryMin = $generics['delivery_free_min_price'] ?? 0;
        $deliveryCharge = ($totalPrice >= $freeDeliveryMin) ? 0 : ($generics['delivery_charges'] ?? 0);
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
            'generics',
            'customer_id',
            'addresses'
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
        $cs = Session::get('checkout_summary');

        if (!$cs || !isset($cs['order_id'])) {
            return redirect()->route('frontend.home')->with('error', 'Invalid session.');
        }

        $orderId = $cs['order_id'];
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
