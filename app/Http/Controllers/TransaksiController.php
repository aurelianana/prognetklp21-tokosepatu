<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\Transaction;
use App\Models\TransactionDetail;
use App\Models\Cart;
use Illuminate\Support\Facades\Storage;
use App\Models\ProductReview;
use App\Notifications\AdminNotification;
use Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth as FacadesAuth;
use Illuminate\Support\Facades\Notification;

class TransaksiController extends Controller
{

    public function detail($id)
    {
        $title = "Kelompok 21 - Toko Sepatu";
        $kategori = ProductCategory::all();
        $transaksi = Transaction::with('transaction_details', 'transaction_details.product')->find($id);
        return view('contents.frontend.checkout-detail')->with(compact('transaksi', 'title', 'kategori'));
        // return $transaksi;
    }

    public function success($id)
    {
        $transaction = Transaction::find($id);
        $transaction->update([
            'status' => 'success'
        ]);
        return redirect()->back();
    }

    public function upload_pembayaran($id, Request $request)
    {
        $gambar = $request->gambar;
        $name = 'produk_' . time() . '.' . $gambar->getClientOriginalExtension();
        $transaksi = Transaction::where('id', '=', $id)->first();
        $transaksi->proof_of_payment = $name;
        $transaksi->update();

        $user = FacadesAuth::user();
        $admin = FacadesAuth::guard('admin')->user();
        $dataAdmin = Admin::all();
        foreach($dataAdmin as $admin){
            $message = "Hallo ".$admin->username." user dengan nama ".$user->name." telah berhasil mengupload bukti pembayaran dari Transaksi : ".$transaksi->id;
            Notification::send($admin, new AdminNotification($message));
        }

        Storage::disk('asset')->put('assets/images/' . $name, file_get_contents($request->file('gambar')));

        return back();
        // return $transaksi;
    }

    public function upload_review_user($id, Request $request)
    {

        $i = 0;
        $j = 0;
        $k = 0;
        foreach ($request->product_id as $pp) {
            foreach ($request->rate as $rate) {
                $temp = (int)$rate;
                foreach ($request->content as $content) {
                    if ($i == $j && $i == $k)
                        ProductReview::create([
                            'product_id' => $pp,
                            'user_id' => Auth::user()->id,
                            'rate' => $temp,
                            'content' => $content,
                        ]);

                    $transaksi = Transaction::where('id', '=', $id)->first();
                    $transaksi->is_review = 1;
                    $transaksi->update();
                    $k++;
                }
                $j++;
                $user = FacadesAuth::user();
                $admin = FacadesAuth::guard('admin')->user();
                $dataAdmin = Admin::all();
                $product = Product::find($pp);
                foreach($dataAdmin as $admin){
                    $message = "Hallo ".$admin->username.", user dengan nama ".$user->name." memberikan review terhadap product : ".$product->product_name;
                    Notification::send($admin, new AdminNotification($message));
                }
            }
            $i++;
        }

        return back();
    }

    public function checkout()
    {
        $title = "Kelompok 21 - Toko Sepatu";
        $kategori = ProductCategory::all();
        $carts = Cart::with('product')->where([['user_id', '=', Auth::user()->id], ['status', '=', 'notyet']])->get();

        return view('contents.frontend.checkout', compact('title', 'kategori', 'carts'));
        // return $carts;
    }

    public function store(Request $request)
    {
        $request->validate([
            "address" => "required",
            "regency" => "required",
            "province" => "required",
            "shipping_cost" => "required",
            "courier_id" => "required",
            // 'products' => "required",
        ]);

        $date = Carbon::now('Asia/Makassar');

        $carts = Cart::with('product')->where([['user_id', '=', Auth::user()->id], ['status', '=', 'notyet']])->get();
        $temp_subtotal = 0;
        $temp_total_weight = 0;
        foreach ($carts as $dd) {
            $temp_subtotal = $temp_subtotal + ($dd->product->price * $dd->qty);
            $temp_total_weight = $temp_total_weight + $dd->product->weight * $dd->qty;
        }

        $timeout = $date->addHours(24);
        $transaksi = Transaction::create([
            'timeout' => $timeout,
            'address' => $request->address,
            'regency' => $request->regency,
            'province' => $request->province,
            'total' => $temp_subtotal + $request->shipping_cost,
            'shipping_cost' => $request->shipping_cost,
            'sub_total' => $temp_subtotal,
            'user_id' => Auth::user()->id,
            'courier_id' => $request->courier_id,
            'proof_of_payment' => null,
            'status' => 'unverified'
        ]);
        $i = 0;
        foreach ($carts as $dd) {
            $trx_detail = TransactionDetail::create([
                'transaction_id' => $transaksi->id,
                'product_id' => $dd->product->id,
                'qty' => $dd->qty,
                'discount_id' => NULL,
                'selling_price' => $dd->product->id
            ]);

            Cart::where('product_id', $dd->product->id)->where('user_id', Auth::user()->id)->where('status', 'notyet')
                ->update([
                    'status' => 'checkedout'
                ]);
        }

        $user = FacadesAuth::user();
        $admin = FacadesAuth::guard('admin')->user();
        $dataAdmin = Admin::all();
        foreach($dataAdmin as $admin){
            $message = "Hallo ".$admin->username.", terdapat transaksi baru dari user dengan nama : ".$user->name;
            Notification::send($admin, new AdminNotification($message));
        }


        // return $transaksi;
        $title = "Kelompok 21 - Toko Sepatu";
        $kategori = ProductCategory::all();
        return redirect()->route('transaksi.detail', $transaksi->id);
        // return redirect()->route('checkout.detail', $transaksi->id)->with('success', "Anda telah berhasil melakukan checkout untuk pesanan Anda! Silahkan melakukan pembayaran sebeluh batas terakhir waktu pembayaran!!!");
    }

    public function buy_now($id)
    {
        $title = "Kelompok 21 - Toko Sepatu";
        $kategori = ProductCategory::all();
        $product = Product::find($id);

        return view('contents.frontend.buynow', compact('title', 'kategori', 'product'));
    }

    public function buy_now_store(Request $request, $id)
    {
        $request->validate([
            "address" => "required",
            "regency" => "required",
            "province" => "required",
            "shipping_cost" => "required",
            "courier_id" => "required",
        ]);

        $date = Carbon::now('Asia/Makassar');

        $product = Product::find($id);


        $temp_subtotal = 0;
        $temp_total_weight = 0;
        $temp_subtotal = $product->price * 1;
        $temp_total_weight = $product->weight * 1;


        $timeout = $date->addHours(24);
        $transaksi = Transaction::create([
            'timeout' => $timeout,
            'address' => $request->address,
            'regency' => $request->regency,
            'province' => $request->province,
            'total' => $temp_subtotal + $request->shipping_cost,
            'shipping_cost' => $request->shipping_cost,
            'sub_total' => $temp_subtotal,
            'user_id' => Auth::user()->id,
            'courier_id' => $request->courier_id,
            'proof_of_payment' => null,
            'status' => 'unverified'
        ]);
        $i = 0;
        $trx_detail = TransactionDetail::create([
            'transaction_id' => $transaksi->id,
            'product_id' => $product->id,
            'qty' => 1,
            'discount_id' => NULL,
            'selling_price' => $product->price
        ]);


        // return $transaksi;
        $title = "Kelompok 21 - Toko Sepatu";
        $kategori = ProductCategory::all();
        return redirect()->route('transaksi.detail', $transaksi->id);
    }
}