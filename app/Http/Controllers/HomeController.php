<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\Courier;
use App\Models\ProductReview;
use App\Models\User;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function index(Request $request)
    {

        $reqsearch = $request->get('search');

        $data = [
            'title'     => 'Kelompok 21 - Toko Sepatu',
            'kategori'  => ProductCategory::All(),
            'produk'    => Product::latest()->paginate(8),
        ];
        return view('contents.frontend.home', $data);
    }

    public function kategori($id)
    {
        $product_category = ProductCategory::with('products')->find($id);
        $kategori = ProductCategory::all();
        return view('contents.frontend.kategori', compact('product_category', 'kategori'));
    }
    public function kurir($id)
    {
        $couriers = Courier::with('products')->find($id);
        $kurir = Courier::all();
        return view('contents.frontend.kurir', compact('couriers', 'kurir'));
    }

    public function search(Request $request)
    {
        $reqsearch = $request->get('keyword');
        $produkdb = Product::leftJoin('kategori', 'produk.id_kategori', '=', 'kategori.id')
            ->select('kategori.nama_kategori', 'produk.*')
            ->when($reqsearch, function ($query, $reqsearch) {
                $search = '%' . $reqsearch . '%';
                return $query->whereRaw('nama_kategori like ? or nama_produk like ?', [
                    $search, $search
                ]);
            });
        $data = [
            'title'     => 'Cari : ' . $reqsearch,
            'kategori'  => ProductCategory::All(),
            'produk'    => $produkdb->latest()->paginate(8),
        ];
        return view('contents.frontend.kategori', $data);
    }

    public function produk(Request $request, $id)
    {
        $reqsearch = $request->get('keyword');
        $produkdb = Product::find($id);
        $review = ProductReview::where('product_id', '=', $id)->get();

        if (!$produkdb) {
            abort('404');
        }

        $data = [
            'title'     => $produkdb->nama_produk,
            'kategori'  => ProductCategory::All(),
            'profil_toko' => User::find(1),
            'edit'      => $produkdb,
            'id'      => $id,
            'review'    => $review,
        ];
        return view('contents.frontend.produk', $data);
    }


    public function redir_admin()
    {
        return redirect('admin');
    }
}