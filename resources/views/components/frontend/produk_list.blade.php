<div class="d-none d-lg-block">
    <div class="row">
        @foreach ($produk as $r)
            <div class="col-sm-3 mb-3 d-none d-lg-block">
                <div class="card-product">
                    <a href="{{ url('produk/' . $r->id) }}" class="text-produk">
                        {{-- <img src="{{ asset('/assets/img', $r->gambar) }}" class="img-fluid w-100"> --}}
                        <img src="{{ $r->image }}" class="img-fluid w-100">
                    </a>
                    <div class="clearfix mb-3"></div>
                    <h5 class="text-produk">Rp{{ number_format($r->price) }},-</h5>
                    <a href="{{ url('produk/' . $r->id) }}" class="text-produk">{{ $r->product_name }}</a>
                    <div class="clearfix"></div>
                </div>
            </div>
        @endforeach
    </div>
</div>
<div class="d-lg-none">
    <div class="row">
        @foreach ($produk as $r)
            <div class="col-6 mb-3 d-lg-none">
                <div class="card-product">
                    <a href="{{ url('produk/' . $r->id) }}" class="text-produk">
                        {{-- <img src="{{ asset('/assets/img', $r->gambar) }}" class="img-fluid w-100"> --}}
                        <img src="{{ $r->image }}" class="img-fluid w-100">
                    </a>
                    <div class="clearfix mb-3"></div>
                    <h5 class="text-produk">Rp{{ number_format($r->price) }},-</h5>
                    <a href="{{ url('produk/' . $r->id) }}" class="text-produk">{{ $r->product_name }}</a>
                    <div class="clearfix"></div>
                </div>
            </div>
        @endforeach
    </div>
</div>
