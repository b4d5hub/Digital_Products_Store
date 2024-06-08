<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;


class ProductController extends Controller
{
    public function index(Request $request, $slug){
        $product = Product::where('slug', $slug)->firstOrFail();
        // $buyerIp = $request->header('X-Forwarded-For') ? explode(',', $request->header('X-Forwarded-For'))[0] : $request->ip();
        // $buyerUserAgent = $request->header('User-Agent');
        // dd($buyerIp, $buyerUserAgent);
        return view('product', ['product' => $product]);
    }

    public function collection($slug){
        $category = Category::where('slug', $slug)->firstOrFail();
        $products = $category->products()->orderBy('created_at', 'DESC')->paginate(6);
        // $product = Product::where('slug', $slug)->firstOrFail();
        return view('welcome', ['products' => $products]);
    }

    // public function index(){
    //     return view('product');
    // }
}
