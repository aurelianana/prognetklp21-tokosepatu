<?php

use App\Http\Controllers\AdminLoginController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\TransaksiAdminController;
use Illuminate\Support\Facades\Auth;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/products', function () {
    return view('products.index');
});

Auth::routes(['verify' => true]);

Route::get('/', [HomeController::class, 'index']);
Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('produk/{id}', [App\Http\Controllers\HomeController::class, 'produk'])->name('home.produk');
Route::get('buy_now/{id}', [App\Http\Controllers\TransaksiController::class, 'buy_now'])->name('produk.buy_now');
Route::get('kategori/{id}', [App\Http\Controllers\HomeController::class, 'kategori'])->name('home.kategori');
Route::get('cart', [App\Http\Controllers\CartController::class, 'index'])->name('cart.index');
Route::get('cart/add-{id}', [App\Http\Controllers\CartController::class, 'add'])->name('cart.add');
Route::get('checkout', [App\Http\Controllers\TransaksiController::class, 'checkout'])->name('checkout');
Route::post('checkout/confirm', [App\Http\Controllers\TransaksiController::class, 'store'])->name('checkout.confirm');
Route::post('buy_now/confirm/{id}', [App\Http\Controllers\TransaksiController::class, 'buy_now_store'])->name('buy_now.confirm');

//my list transaksi
Route::get('myTransaksi', [App\Http\Controllers\TransaksiController::class, 'index'])->name('transaksi.index');
Route::get('myTransaksi/{id}', [App\Http\Controllers\TransaksiController::class, 'detail'])->name('transaksi.detail');
Route::post('myTransaksi/success/{id}', [App\Http\Controllers\TransaksiController::class, 'success'])->name('transaksi.success');
Route::post('upload-pembayaran-{id}', [App\Http\Controllers\TransaksiController::class, 'upload_pembayaran'])->name('upload.pembayaran');
Route::post('upload-review-{id}', [App\Http\Controllers\TransaksiController::class, 'upload_review_user'])->name('upload.review.user');



// Route::get('/logout', [App\Http\Controllers\LoginController::class, 'logout'])->name('logout');

// Route Admin
Route::prefix('admin')->group(function () {
    Route::get('login', [AdminLoginController::class, 'showLoginForm'])->name('admin.login');
    Route::post('login', [AdminLoginController::class, 'login'])->name('admin.login.submit');
    Route::get('/', [App\Http\Controllers\AdminController::class, 'index'])->name('admin.dashboard');
    Route::get('/logout', [AdminLoginController::class, 'logout'])->name('admin.logout');
});

Route::prefix('admin')->middleware('auth:admin')->group(function () {
    Route::get('/', [App\Http\Controllers\AdminController::class, 'index'])->name('admin.index');
    Route::get('/product-review/{id}', [App\Http\Controllers\AdminController::class, 'product_reviews'])->name('admin.review');
    Route::post('/product-review/{id}/submit', [App\Http\Controllers\AdminController::class, 'review_store'])->name('admin.review.store');
    // route produk
    Route::prefix('produk')->group(function () {
        Route::get('/', [App\Http\Controllers\AdminController::class, 'produk'])->name('admin.produk');
        Route::post('upload', [App\Http\Controllers\AdminController::class, 'upload_image_product'])->name('admin.produk.upload');
        Route::get('delete/{category}', [App\Http\Controllers\AdminController::class, 'delete_produk'])->name('admin.delete_produk');
        Route::get('/edit', [App\Http\Controllers\AdminController::class, 'edit_produk'])->name('admin.edit_produk');
        Route::post('create', [App\Http\Controllers\AdminController::class, 'create_produk'])->name('admin.create_produk');
        Route::post('update', [App\Http\Controllers\AdminController::class, 'update_produk'])->name('admin.update_produk');
        Route::get('pesan/{id}', [App\Http\Controllers\PesanController::class, 'index']);
    });
    // route kategori
    Route::prefix('kategori')->group(function () {
        Route::get('/', [App\Http\Controllers\AdminController::class, 'kategori'])->name('admin.kategori');
        Route::get('delete/{category}', [App\Http\Controllers\AdminController::class, 'delete_kategori'])->name('admin.delete_kategori');
        Route::post('create', [App\Http\Controllers\AdminController::class, 'create_kategori'])->name('admin.create_kategori');
        Route::post('update', [App\Http\Controllers\AdminController::class, 'update_kategori'])->name('admin.update_kategori');
    });
    // route kurir
    Route::prefix('kurir')->group(function () {
        Route::get('/', [App\Http\Controllers\AdminController::class, 'kurir'])->name('admin.kurir');
        Route::get('delete/{category}', [App\Http\Controllers\AdminController::class, 'delete_kurir'])->name('admin.delete_kurir');
        Route::post('create', [App\Http\Controllers\AdminController::class, 'create_kurir'])->name('admin.create_kurir');
        Route::post('update', [App\Http\Controllers\AdminController::class, 'update_kurir'])->name('admin.update_kurir');
    });

    Route::name('admin.')->group(function () {
        Route::resource('transaksi', TransaksiAdminController::class);
        Route::post('transaction/{transaction}/accept', [TransaksiAdminController::class, 'acceptPayment'])->name('transaction.accept');
        Route::post('transaction/{transaction}/shipped', [TransaksiAdminController::class, 'updateShipped'])->name('transaction.shipped');
        Route::post('transaction/{transaction}/cancel', [TransaksiAdminController::class, 'cancelTransaction'])->name('transaction.cancel');
    });


    // route profil
    Route::prefix('profil')->group(function () {
        Route::get('/', [App\Http\Controllers\AdminController::class, 'profil'])->name('admin.profil');
        Route::post('update', [App\Http\Controllers\AdminController::class, 'update_profil'])->name('admin.update_profil');
    });
});