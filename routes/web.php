<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TechStoreController;

// Ruta para limpiar sesión (útil para desarrollo)
Route::get('/limpiar-sesion', function() {
    session()->flush();
    return redirect('/techstore')->with('success', '✅ Sesión limpiada correctamente');
})->name('clear.session');

// Ruta raíz redirige a la tienda
Route::get('/', function () {
    return redirect()->route('techstore.index');
});

// Grupo de rutas de TechStore
Route::prefix('techstore')->name('techstore.')->group(function () {
    // Catálogo
    Route::get('/', [TechStoreController::class, 'index'])->name('index');
    
    // Carrito
    Route::post('/cart/add', [TechStoreController::class, 'addToCart'])->name('cart.add');
    Route::get('/cart', [TechStoreController::class, 'cart'])->name('cart');
    Route::post('/cart/update', [TechStoreController::class, 'updateCart'])->name('cart.update');
    Route::delete('/cart/remove/{productId}', [TechStoreController::class, 'removeFromCart'])->name('cart.remove');
    
    // Checkout y Confirmación
    Route::get('/checkout', [TechStoreController::class, 'checkout'])->name('checkout');
    Route::post('/checkout/process', [TechStoreController::class, 'processCheckout'])->name('checkout.process');
    Route::get('/confirm/{orderId}', [TechStoreController::class, 'confirm'])->name('confirm');
    Route::post('/confirm/{orderId}', [TechStoreController::class, 'confirmOrder'])->name('confirm.order');
    Route::get('/success/{orderId}', [TechStoreController::class, 'success'])->name('success');
});
