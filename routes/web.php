<?php

use App\Http\Controllers\Auth\customerPasswordResetController;
use App\Http\Controllers\cartController;
use App\Http\Controllers\checkoutController;
use App\Http\Controllers\customerAuthController;
use App\Http\Controllers\customerDashboardController;
use App\Http\Controllers\dashboardController;
use App\Http\Controllers\frontendController;
use App\Http\Controllers\orderController;
use App\Http\Controllers\pdfController;
use App\Http\Controllers\productCategoryController;
use App\Http\Controllers\productController;
use App\Http\Controllers\razorpayController;
use App\Http\Controllers\userController;
use App\Http\Middleware\CustomerAuth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;

//FRONTEND
Route::get('/', [frontendController::class, 'home'])->name('frontend.home');
Route::get('/shop/{category_slug?}', [frontendController::class, 'shop'])->name('frontend.shop');
Route::get('/search', [FrontendController::class, 'search'])->name('frontend.search');
Route::get('/product/{product_slug}', [frontendController::class, 'viewProduct'])->name('frontend.viewproduct');
Route::get('/product/add-to-cart/{product_slug}/{product_size?}', [cartController::class, 'addToCart'])->name('frontend.addtocart');
Route::get('/product/add-to-wishist/{product_slug}', [cartController::class, 'addToWishlist'])->name('frontend.addtowishlist');
Route::post('/cart/update', [cartController::class, 'updateCart'])->name('cart.update');
Route::get('/shopping-cart', [frontendController::class, 'viewCart'])->name('frontend.shoppingcart');
Route::get('/wishlist', [frontendController::class, 'viewWishlist'])->name('frontend.wishlist');
Route::get('/product/move-to-cart/{id}', [cartController::class, 'moveToCart'])->name('frontend.moveToCart');
Route::post('/cart/refresh-totals', [cartController::class, 'refreshCartTotals'])->name('cart.refreshTotals'); // Partial
Route::get('/checkout', [frontendController::class, 'viewCheckoutPage'])->name('checkout');
Route::post('/order/store', [orderController::class, 'addOrder'])->name('order.store');
Route::get('/order-summary', [frontendController::class, 'viewSummary'])->name('order.summary');

Route::get('/about', [frontendController::class, 'about'])->name('frontend.about');
Route::get('/contact', [frontendController::class, 'contact'])->name('frontend.contact');

//payment gateway
Route::get('/proceed-to-payment', [razorpayController::class, 'viewPaymentPage'])->name('payment');
Route::post('/proceed-to-payment', [razorpayController::class, 'proceedToPayment'])->name('payment.proceed');

//payment success
Route::get('/payment/success', [frontendController::class, 'paymentSuccess'])->name('payment.success');

//pdf
Route::get('/download-receipt/{oid}', [pdfController::class, 'downloadReceipt'])->name('receipt.download');

Route::prefix('customer')->name('customer.')->group(function () {

    // Customer Authentication
    Route::post('register', [CustomerAuthController::class, 'register'])->name('register.submit');
    Route::get('login', [CustomerAuthController::class, 'showLoginForm'])->name('login');
    Route::post('login', [CustomerAuthController::class, 'login'])->name('login.submit');
    Route::get('logout', [CustomerAuthController::class, 'logout'])->name('logout');

    // Customer Dashboard (Protected by middleware)
    Route::middleware(CustomerAuth::class)->group(function () {
        Route::get('dashboard', [CustomerDashboardController::class, 'dashboard'])->name('dashboard');
        Route::get('orders', [CustomerDashboardController::class, 'viewOrders'])->name('orders');
        Route::get('orders/{id}', [CustomerDashboardController::class, 'viewOrderItems'])->name('order.items');
        Route::get('profile', [CustomerDashboardController::class, 'viewProfile'])->name('profile');
        Route::get('addresses', [customerDashboardController::class, 'viewAddresses'])->name('addresses');
        Route::post('addAddress', [customerDashboardController::class, 'addAddress'])->name('addAddress');
    });

    // Password Reset Routes
    Route::get('forgot-password', [customerPasswordResetController::class, 'showLinkRequestForm'])->name('password.request');
    Route::post('forgot-password', [customerPasswordResetController::class, 'sendResetLinkEmail'])->name('password.email');
    Route::get('reset-password/{token}', [customerPasswordResetController::class, 'showResetForm'])->name('password.reset');
    Route::post('reset-password', [customerPasswordResetController::class, 'reset'])->name('password.update');
});

//BACKEND

// Login Route
Route::get('/internal/login', [userController::class, 'showLoginForm'])->name('admin.login');
Route::post('/internal/login', [userController::class, 'login'])->name('login.submit');

// Logout Route
Route::get('/backend/logout', [userController::class, 'logout'])->name('admin.logout');

// Dashboard
Route::get('/backend/dashboard', [dashboardController::class, 'dashboard'])->name('dashboard');
Route::post('/backend/dashboard/data', [dashboardController::class, 'fetchDashboardData'])->name('dashboard.data');

//Category
Route::get("/backend/categories", [productCategoryController::class, 'viewCategories'])->name('backend.categories.view');
Route::get('/backend/categories/add/{category_id?}', [productCategoryController::class, 'showAddCategoryForm'])->name('backend.categories.add');
Route::post('/backend/categories/save/{category_id?}', [productCategoryController::class, 'storeCategory'])->name('backend.categories.save');
Route::delete('/backend/categories/delete/{id}', [productCategoryController::class, 'deleteCategory'])->name('backend.categories.delete');

//Products
Route::get('/backend/products', [productController::class, 'viewProducts'])->name('backend.products.view');
Route::get('/backend/products/add/{product_id?}', [productController::class, 'showAddProductForm'])->name('backend.products.add');
Route::post('/backend/products/store/{product_id?}', [productController::class, 'storeProduct'])->name('backend.products.store');
Route::delete('/backend/products/delete/{id}', [productController::class, 'deleteProduct'])->name('backend.products.delete');

//Orders
Route::get('/backend/orders', [orderController::class, 'viewOrders'])->name('backend.orders.view');
Route::get('/backend/orders/{id}', [orderController::class, 'viewOrderDetails'])->name('backend.orders.show');
Route::post('/backend/orders/addOrderNote', [orderController::class, 'addOrderNote'])->name('backend.orders.addnote');
