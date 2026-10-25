<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;


class ProductController extends Controller
{
    public function index(Request $request, $slug)
    {
        $product = Product::query()
            ->where('slug', $slug)
            ->where('is_visible', true)
            ->firstOrFail();

        return view('product', ['product' => $product]);
    }

    public function collection($slug)
    {
        $category = Category::query()
            ->where('slug', $slug)
            ->where('is_visible', true)
            ->firstOrFail();

        $products = $category->products()
            ->where('is_visible', true)
            ->latest()
            ->paginate(6);

        return view('welcome', ['products' => $products]);
    }
}
