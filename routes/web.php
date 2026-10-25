<?php

use App\Filament\Pages\StoreVars;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\PaypalController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use App\Models\Product;
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

Route::get('/health', static fn () => response()->json(['status' => 'ok']));

Route::get('/', function () {
    $products = Product::orderBy('created_at','DESC')->paginate(6);
    return view('welcome', ['products' => $products]);
})->name('home');

Route::get('/notify', function () {
    $receiver = auth()->user();
    \Filament\Notifications\Notification::make()
        ->title($receiver->name)
        ->sendToDatabase($receiver);
    dd('Notification Sent');
})->middleware('auth');

Route::get('/product/{slug}', [ProductController::class, 'index']);

// Route::get('/successtest', function () {
//     return view('success');
// })->name('successtest');

Route::get('/successtest/{order_number}/{orderTimestamp}/{download_token}', [PaypalController::class, 'deliver'])->name('successtest');


Route::get('/checkout/{slug}', [CheckoutController::class, 'index'])->name('checkout');
Route::get('/collection/{slug}', [ProductController::class, 'collection'])->name('collection');

Route::post('paypal', [PaypalController::class, 'paypal'])->name('paypal');
Route::get('success', [PaypalController::class, 'success'])->name('success');
Route::get('cancel', [PaypalController::class, 'cancel'])->name('cancel');


Route::middleware(['auth'])
    ->prefix('filament')
    ->group(function () {
        Route::get('manage-store-settings', StoreVars::class)->name('filament.pages.manage-store-settings');
    });
