<?php

use App\Http\Controllers\Admin\AuthController as AdminAuthController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\HomepageContentController;
use App\Http\Controllers\Admin\MenuItemController;
use App\Http\Controllers\Admin\SiteSettingController;
use App\Http\Controllers\Admin\UpdatePostController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\PublicController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', [PublicController::class, 'home'])->name('home');
Route::get('/home', static fn () => redirect()->route('home'));
Route::get('/menu', [PublicController::class, 'menu'])->name('menu');
Route::get('/updates', [PublicController::class, 'updates'])->name('updates');
Route::get('/contact', [PublicController::class, 'contact'])->name('contact');
Route::get('/about', [PublicController::class, 'about'])->name('about');

Route::get('/order-online', [PublicController::class, 'ordering'])->name('ordering');

Route::prefix('cart')->group(function () {
    Route::get('/', [CartController::class, 'index'])->name('cart.index');
    Route::post('/add', [CartController::class, 'add'])->name('cart.add');
    Route::patch('/{id}', [CartController::class, 'update'])->name('cart.update');
    Route::delete('/{id}', [CartController::class, 'remove'])->name('cart.remove');
});

Route::get('/checkout', [CartController::class, 'checkoutForm'])->name('checkout.form');
Route::post('/checkout', [CartController::class, 'checkout'])->name('checkout');
Route::get('/checkout/success', [CartController::class, 'checkoutSuccess'])->name('checkout.success');
Route::get('/checkout/cancel', [CartController::class, 'checkoutCancel'])->name('checkout.cancel');

Route::prefix('admin')->name('admin.')->group(function () {
    Route::middleware('guest')->group(function () {
        Route::get('/login', [AdminAuthController::class, 'showLogin'])->name('login');
        Route::post('/login', [AdminAuthController::class, 'login'])->name('login.submit');
    });

    Route::middleware(['auth','admin'])->group(function () {
        Route::post('/logout', [AdminAuthController::class, 'logout'])->name('logout');

        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
        Route::resource('menu-items', MenuItemController::class)->except(['show']);
        Route::resource('orders', AdminOrderController::class);
        Route::resource('updates', UpdatePostController::class);
        Route::get('/settings', [SiteSettingController::class, 'edit'])->name('settings.edit');
        Route::put('/settings', [SiteSettingController::class, 'update'])->name('settings.update');
        Route::get('/homepage', [HomepageContentController::class, 'edit'])->name('homepage.edit');
        Route::put('/homepage', [HomepageContentController::class, 'update'])->name('homepage.update');
        Route::get('/about', [App\Http\Controllers\Admin\AboutPageController::class, 'edit'])->name('about.edit');
        Route::post('/about', [App\Http\Controllers\Admin\AboutPageController::class, 'update'])->name('about.update');
    });
});
