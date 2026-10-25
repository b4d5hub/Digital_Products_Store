<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class CheckoutController extends Controller
{
    public function index(Request $request, $slug)
    {
        $product = Product::query()
            ->where('slug', $slug)
            ->where('is_visible', true)
            ->firstOrFail();

        abort_if($product->quantity < 1, 404);

        $validated = $request->validate([
            'purchase_qty' => ['nullable', 'integer', 'min:1', "max:{$product->quantity}"],
        ]);

        $quantity = $validated['purchase_qty'] ?? 1;
        $totalPrice = $product->price * $quantity;

        return view('checkout', [
            'product' => $product,
            'quantity' => $quantity,
            'totalPrice' => $totalPrice,
        ]);
    }
}
