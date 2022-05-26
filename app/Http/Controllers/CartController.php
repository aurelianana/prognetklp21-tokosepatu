<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use App\Models\Cart;
use App\Models\ProductCategory;
use App\Models\Product;
use Illuminate\Support\Facades\DB;

class CartController extends Controller
{
    public function index()
    {
        $carts = Cart::with('product')->where([['user_id', Auth::user()->id], ['status', '=', 'notyet']])->get();
        $kategori = ProductCategory::All();
        $title = 'Kelompok 21 - Toko Sepatu';
        return view('contents.frontend.cart')->with(compact('carts', 'kategori', 'title'));
    }

    public function add($id)
    {
        $user = Auth::user();
        $carts = Cart::where([['user_id', '=', $user->id], ['product_id', '=', $id], ['status', '=', 'notyet']])->get();
        $product = Product::find($id);
        if (count($carts) == 0) {
            $cart = new Cart();
            $cart->user_id = $user->id;
            $cart->product_id = $id;
            $cart->qty = 1;
            $cart->status = "notyet";
            $cart->save();
        } else {
            Cart::where('product_id', '=', $id)->update([
                'qty' => DB::raw('qty+1'),
            ]);
        }
        return back();
    }
}