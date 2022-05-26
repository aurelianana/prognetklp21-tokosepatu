@extends('layouts.frontend')
@section('content')
    <div class="container mt-5">
        <div class="row">
            <div class="col-sm-9 mx-auto">
                <!--product -->
                <div class="product">
                    <h4 class="mb-4"><b>{{ $title }}</b></h4>
                    <div class="row">
                        <div class="col-sm-4">
                            {{-- <img src="{{ url_images('gambar', $edit->gambar) }}" class="img-fluid w-100 mb-3"> --}}
                            <img src="{{ $edit->image }}" class="img-fluid w-100 mb-3">
                        </div>
                        <div class="col-sm-8 detail-produk">
                            <div class="row mt-3">
                                <div class="col-sm-4"><b>Kategori</b></div>
                                @if ($edit->categories->count() > 0)
                                    <div class="col-sm-8">
                                        {{-- intinya ini ngecek apakah ada kategori, kalo ada tampulin 
                                        kategorinya kalo gaada nampilin teks tidak ada kategori --}}
                                        <a class="text-produk" href="{{ url('kategori/' . $edit->id) }}">
                                            {{ $edit->categories[0]->category_name }}
                                        </a>
                                    </div>
                                @else
                                    <div class="col">
                                        <div>Tidak Ada Kategori</div>
                                    </div>
                                @endif
                            </div>
                            <div class="row mt-3">
                                <div class="col-sm-4"><b>Nama Produk</b></div>
                                <div class="col-sm-8"><?= $edit->product_name ?></div>
                            </div>
                            <div class="row mt-3">
                                <div class="col-sm-4"><b>Harga jual</b></div>
                                <div class="col-sm-8 text-success">
                                    <h4><b>Rp<?= number_format($edit->price) ?>,-</b></h4>
                                </div>
                            </div>
                            <div class="row mt-3">
                                <div class="col-sm-4"><b>Deskripsi</b></div>
                                <div class="col-sm-8"><?= $edit->description ?></div>
                            </div>
                            <div class="row mt-3">
                                <div class="col-sm-4"><b></b></div>
                                <div class="col-sm-8">
                                    <button class="btn btn-success btn-md" data-toggle="modal"
                                        data-target="#exampleModalCenter">
                                        <i></i> Keranjang
                                    </button>
                                    <a class="btn btn-success btn-md" href="{{ route('produk.buy_now', $edit->id) }}"
                                        target="_blank" role="button">
                                        <i></i> Beli Sekarang
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <style>
            img {
                border-radius: 50%;
                height: 60px;
                width: 60px;
                float: left;
            }

            .time {
                float: right;
            }

            .you {
                background-color: #cccccc;
                border-radius: 5px;
                padding: 6px;
                margin: 10px 0;

            }

            .other {
                background-color: #eeeeee;
                border-color: #dddddd;
                border-radius: 5px;
                padding: 6px;
                margin: 10px 0;

            }

            .you:after,
            .other:after,
            .wrapper:after {
                content: "";
                clear: both;
                display: table;
            }

            h2 {
                text-align: center;
            }

            .wrapper {
                background-color: green;
                border-radius: 5px;
                padding: 10px;
                width: 500px;

            }

        </style>
        <hr style="margin-bottom:50px;">
        <div class="row">
            <div class="container">
                <div class="row">
                    <div class="col-12">
                        <h3 style="text-align:center;">Reviews</h3>
                    </div>
                </div>
                <div class="row" style="display:flex;align-items:center; justify-content:center">
                    <div class="col-9">

                        @foreach ($review as $item)
                            <div class="row">
                                <div class="col-2">
                                    <img src="../assets/images/signin-image.jpg" alt="">
                                </div>
                                <div class="col-10">
                                    <div class="you" style="margin-right:100px;">
                                        <p> {{ $item->content }}</p>
                                        <span class="time">{{ $item->created_at }}</span>
                                    </div>
                                </div>
                            </div>
                            @if ($item->response()->exists())
                                <div class="row" style="margin-left:100px;">
                                    <div class="col-2">
                                        <img src="../assets/images/signin-image.jpg" alt="">
                                    </div>
                                    <div class="col-10">
                                        <div class="other">
                                            <p> {{ $item->response->content }} </p>
                                            <span class="time">{{ $item->response->created_at }}</span>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        @endforeach

                    </div>
                </div>
                <hr style="margin-bottom:50px; margin-top:50px;">
            </div>
        </div>
    </div>


    <div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog"
        aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalCenterTitle">Modal title</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    Apakah anda yakin ingin menambah produk ini ke keranjang?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <a href="{{ route('cart.add', $id) }}"><button type="button" class="btn btn-primary">Add
                            Cart</button></a>

                </div>
            </div>
        </div>
    </div>
@endsection
@section('javascript')
@endsection
