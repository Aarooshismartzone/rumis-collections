<?php

use App\Http\Controllers\cartController;
use App\Http\Controllers\checkoutController;
use App\Http\Controllers\customerAuthController;
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

// Route::get('/', function () {
//     dd(Hash::make('2HrU11&eE'));
//     return view('frontend.home');
// });

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

//payment gateway
Route::get('/proceed-to-payment', [razorpayController::class, 'viewPaymentPage'])->name('payment');
Route::post('/proceed-to-payment', [razorpayController::class, 'proceedToPayment'])->name('payment.proceed');

//payment success
Route::get('/payment/success', [frontendController::class, 'paymentSuccess'])->name('payment.success');

//pdf
Route::get('/download-receipt/{oid}', [pdfController::class, 'downloadReceipt'])->name('receipt.download');

//Customer Login
Route::get('/customer/login', [customerAuthController::class, 'showLoginForm'])->name('customer.login');
Route::post('/customer/login', [customerAuthController::class, 'login'])->name('customer.login.submit');
Route::get('/customer/logout', [customerAuthController::class, 'logout'])->name('customer.logout');
// Apply middleware directly
Route::get('/customer/dashboard', [CustomerAuthController::class, 'dashboard'])
    ->middleware(CustomerAuth::class)
    ->name('customer.dashboard');

// Login Route
Route::get('/internal/login', [userController::class, 'showLoginForm'])->name('admin.login');
Route::post('/internal/login', [userController::class, 'login'])->name('login.submit');

// Logout Route
Route::get('/backend/logout', [userController::class, 'logout'])->name('admin.logout');

// Dashboard
Route::get('/backend/dashboard', [dashboardController::class, 'dashboard']);

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
