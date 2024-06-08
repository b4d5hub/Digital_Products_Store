<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class CheckoutController extends Controller
{
    public function index(Request $request, $slug){
        $product = Product::where('slug', $slug)->firstOrFail();
        // return view('checkout', ['product' => $product]);
        $quantity = $request->query('purchase_qty', 1); // Default to 1 if not provided
        $totalPrice = $product->price * $quantity;

        return view('checkout', [
            'product' => $product,
            'quantity' => $quantity,
            'totalPrice' => $totalPrice,
        ]);
    }
}
