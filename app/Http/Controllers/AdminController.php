<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\ProductCategoryDetail;
use App\Models\ProductImage;
use App\Models\User;
use App\Models\Courier;
use App\Models\ProductReview;
use App\Models\Response;
use App\Notifications\UserNotification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;

class AdminController extends Controller
{
    //
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    public function index()
    {
        $data = [
            'title' => 'Admin Toko'
        ];
        return view('contents.admin.home', $data);
    }

    // produk
    public function produk(Request $request)
    {
        $reqsearch = $request->get('search');
        $produkdb = Product::query()
            ->when($reqsearch, function ($query, $reqsearch) {
                $search = '%' . $reqsearch . '%';
                return $query->whereRaw('nama_kategori like ? or nama_produk like ?', [
                    $search, $search
                ]);
            });
        $data = [
            'title'     => 'Data Produk',
            'kategori'  => ProductCategory::All(),
            'produk'    => $produkdb->paginate(5),
            'request'   => $request
        ];
        return view('contents.admin.produk', $data);
    }

    public function upload_image_product(Request $request)
    {
        $request->validate([
            'gambar.*' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        foreach ($request->file('gambar') as $gambar) {
            $name = 'produk_' . time() . '.' . $gambar->getClientOriginalExtension();
            $destinationPath = public_path('/images');
            $gambar->move($destinationPath, $name);
        }
        return '/images/' . $name;
    }

    public function edit_produk(Request $request)
    {
        $product = Product::findOrFail($request->get('id'));
        $kategori = ProductCategory::all();
        return view('components.admin.produk.edit', compact('product', 'kategori'));
    }

    // data proses produk 
    //CREATE PRODUK
    public function create_produk(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            "kategori"   => "required",
            "nama_produk"   => "required",
            "deskripsi"     => "required",
            "harga_jual"    => "required",
        ]);

        if ($validator->passes()) {

            $product = Product::create([
                'product_name'   => $request->get("nama_produk"),
                'description'     => $request->get("deskripsi"),
                'price'    => $request->get("harga_jual"),
                'product_rate' => 5.0, //ini nanti buatin input sendiri di modalnya
                'stock' => 100, //ini nanti buatin input sendiri di modalnya
                'weight' => 100 //ini nanti buatin input sendiri di modalnya
            ]);

            foreach ($request->kategori as $kategori) {
                ProductCategoryDetail::insert([
                    'product_id' => $product->id,
                    'category_id' => $kategori
                ]);
            }

            foreach ($request->gambar as $gambar) {
                ProductImage::insert([
                    'product_id' => $product->id,
                    'image_name' => $gambar
                ]);
            }


            return redirect()->back()->with("success", " Berhasil Insert Data ! ");
        } else {
            return redirect()->back()->withErrors($validator)->with("failed", " Gagal Insert Data ! ");
        }
    }

    //UPDATE PRODUK
    public function update_produk(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            "id"            => "required",
            "id_kategori"   => "required",
            "nama_produk"   => "required",
            "deskripsi"     => "required",
            "harga_jual"    => "required",
        ]);

        if ($validator->passes()) {
            $produkdb = Product::findorFail($request->get('id'));
            if ($request->file('gambar')) {
                $validator = \Validator::make($request->all(), [
                    "gambar" => "required|image|max:1024",
                ]);
                if ($validator->passes()) {
                    $image = $request->file('gambar');
                    $input['imagename'] = 'produk_' . time() . '.' . $image->getClientOriginalExtension();

                    $destinationPath = storage_path('public/assets/img');
                    $image->move($destinationPath, $input['imagename']);
                    $gambar = $input['imagename'];
                } else {
                    return redirect()->back()->withErrors($validator)->with("failed", " Gagal Update Data ! ");
                }
            } else {
                $gambar = $produkdb->gambar;
            }

            $produkdb->update([
                'id_kategori'   => $request->get("id_kategori"),
                'gambar'        => $gambar,
                'nama_produk'   => $request->get("nama_produk"),
                'deskripsi'     => $request->get("deskripsi"),
                'harga_jual'    => $request->get("harga_jual"),
                'updated_at'    => date('Y-m-d H:i:s'),
            ]);

            return redirect()->back()->with("success", " Berhasil Update Data Produk " . $request->get("nama_produk") . ' !');
        } else {
            return redirect()->back()->withErrors($validator)->with("failed", " Gagal Update Data ! ");
        }
    }

    //DELETE PRODUK
    public function delete_produk(Request $request, $id)
    {

        $produk = Product::findOrFail($id);
        if ($produk->images->count() > 0) {
            ProductImage::where('product_id', $id)->delete();
            foreach ($produk->images as $image) {
                $image->delete();
                unlink(public_path($image->image_name));
            }
        }
        if ($produk->categories->count() > 0) {
            ProductCategoryDetail::where('product_id', $id)->delete();
        }
        $produk->delete();
        return redirect()->back()->with("success", " Berhasil Delete Data Produk ! ");
    }

    // kategori
    public function kategori(Request $request)
    {
        if (!empty($request->get('id'))) {
            $edit = ProductCategory::findOrFail($request->get('id'));
        } else {
            $edit = '';
        }

        $data = [
            'title'     => 'Data Kategori',
            'kategori'  => ProductCategory::paginate(5),
            'edit'      => $edit,
            'request'   => $request
        ];
        return view('contents.admin.kategori', $data);
    }

    // data proses kategori
    //CREATE KATEGORI
    public function create_kategori(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            "nama_kategori" => "required",
        ]);
        if ($validator->passes()) {
            ProductCategory::create([
                'category_name' => $request->get("nama_kategori"),
            ]);
            return redirect()->back()->with("success", " Berhasil Insert Data ! ");
        } else {
            return redirect()->back()->withErrors($validator)->with("failed", " Gagal Insert Data ! ");
        }
    }

    //UPDATE KATEGORI
    public function update_kategori(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            "id"            => "required",
            "nama_kategori" => "required",
        ]);
        if ($validator->passes()) {
            ProductCategory::findOrFail($request->get('id'))->update([
                'category_name' => $request->get("nama_kategori"),
            ]);
            return redirect()->back()->with("success", " Berhasil Update Data ! ");
        } else {
            return redirect()->back()->withErrors($validator)->with("failed", " Gagal Update Data ! ");
        }
    }

    //DELETE KATEGORI
    public function delete_kategori(Request $request, $id)
    {
        $kategori = ProductCategory::findOrFail($id);
        $kategori->delete();
        return redirect()->back()->with("success", " Berhasil Delete Data ! ");
    }

    // kurir
    public function kurir(Request $request)
    {
        if (!empty($request->get('id'))) {
            $edit = Courier::findOrFail($request->get('id'));
        } else {
            $edit = '';
        }

        $data = [
            'title'     => 'Data Kurir',
            'kategori'  => Courier::paginate(5),
            'edit'      => $edit,
            'request'   => $request
        ];
        return view('contents.admin.kurir', $data);
    }
    // data proses kurir
    //CREATE KURIR
    public function create_kurir(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            "nama_kurir" => "required",
        ]);
        if ($validator->passes()) {
            Courier::create([
                'courier' => $request->get("nama_kurir"),
            ]);
            return redirect()->back()->with("success", " Berhasil Insert Data ! ");
        } else {
            return redirect()->back()->withErrors($validator)->with("failed", " Gagal Insert Data ! ");
        }
    }

    //UPDATE KURIR
    public function update_kurir(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            "id"      => "required",
            "nama_kurir" => "required",
        ]);
        if ($validator->passes()) {
            Courier::findOrFail($request->get('id'))->update([
                'courier' => $request->get("nama_kurir"),
            ]);
            return redirect()->back()->with("success", " Berhasil Update Data ! ");
        } else {
            return redirect()->back()->withErrors($validator)->with("failed", " Gagal Update Data ! ");
        }
    }

    //DELETE KURIR
    public function delete_kurir(Request $request, $id)
    {
        $kurir = Courier::findOrFail($id);
        $kurir->delete();
        return redirect()->back()->with("success", " Berhasil Delete Data ! ");
    }



    // profil
    public function profil(Request $request)
    {
        $data = [
            'title' => 'Data Produk',
            'edit' => User::findOrFail(auth()->user()->id),
            'request' => $request
        ];
        return view('contents.admin.profil', $data);
    }

    // data proses profil
    public function update_profil(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            "name"                  => "required",
            "email"                 => "required",
            "password"              => "required|min:6",
            "password_confirmation" => "required|min:6",
        ]);

        if ($validator->passes()) {
            if ($request->get("password") == $request->get("password_confirmation")) {
                User::findOrFail(auth()->user()->id)->update([
                    'name'          => $request->get("name"),
                    'email'         => $request->get("email"),
                    'phone'         => $request->get("phone"),
                    'address'       => $request->get("address"),
                    'password'      => Hash::make($request->get("password")),
                    'updated_at'    => date('Y-m-d H:i:s'),
                ]);
                return redirect()->back()->with("success", " Berhasil Update Data ! ");
            } else {
                return redirect()->back()->with("failed", "Confirm Password Tidak Sama !");
            }
        } else {
            return redirect()->back()->withErrors($validator)->with("failed", " Gagal Update Data ! ");
        }
    }


    public function product_reviews($id)
    {
        $review = ProductReview::where('product_id', $id)->get();
        $data = [
            'title'     => 'Review Produk',
            'kategori'  => ProductCategory::All(),
            'review'    => $review,
        ];
        return view('contents.admin.review-product', $data);
    }

    public function review_store(Request $request, $id)
    {
        $response = Response::whereReviewId($id)->get();

        Response::create([
            'review_id' => $id,
            'admin_id' => auth()->user('admin')->id,
            'content' => $request->content
        ]);

        $productReview = ProductReview::with('product')->findOrFail($id);
        $user = User::find($productReview->user_id);
        $message = "Hallo " . $user->name . ", ulasan pada produk " . $productReview->product->product_name . " telah tanggapi oleh Admin Toko";

        Notification::send($user, new UserNotification($message));
        
        return redirect()->back();
    }

    public function markNotifications()
    {
        $adminNotifications = Auth::guard('admin')->user()->notifications->whereNull('read_at');
        foreach($adminNotifications as $data){
            $data->update([
                'read_at' => now()
            ]);
        }
        return redirect()->back();
    }
}