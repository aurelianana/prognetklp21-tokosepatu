<div class="modal-header">
    <h5 class="modal-title">Update Produk</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>
<form method="post" action="{{ route('admin.update_produk') }}" enctype="multipart/form-data">
    @csrf
    <div class="modal-body">
        <div class="form-group">
            <label for="">Kategori</label>
            <select class="form-select" name="id_kategori" required>
                @foreach($kategori as $r)
                <option value="{{ $r->id }}" {{ $product->id_kategori == $r->id ? 'selected' : ''}}>{{ $r->category_name }}</option>
                @endforeach
            </select>
            @error("id_kategori")
                <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>
        <div class="form-group mt-3">
            <label for="">Nama Produk</label>
            <input type="text" class="form-control @error("nama_produk") is-invalid @enderror" value="{{$product->product_name}}" name="nama_produk" id="nama_produk" placeholder="">
        </div>
        
        <div class="form-group mt-3">
            <label for="">Deskripsi</label>
            <textarea class="form-control @error("deskripsi") is-invalid @enderror" rows="8" name="deskripsi" id="deskripsi" placeholder="">{{$product->description}}</textarea>
        </div>
        <div class="form-group mt-3">
            <label for="">Harga jual</label>
            <input type="number" class="form-control @error("price") is-invalid @enderror" value="{{$product->price}}" name="harga_jual" id="harga_jual" placeholder="">
        </div>
        <div class="form-group mt-3">
            <label for="">Gambar <small class="text-danger ms-1">* Opsional</small></label>
            <input type="file" class="form-control @error("gambar") is-invalid @enderror" value="{{old("gambar")}}" 
                 name="gambar" id="gambar" placeholder="">
            @error("gambar")
                <span class="text-danger">{{ $message }}</span>
            @enderror
            {{-- <a href="{{url_images('gambar', $product->gambar)}}" target="_blank">
                <img src="{{url_images('gambar', $product->gambar)}}" class="img-fluid mt-3" style="width:80px;">
            </a> --}}
        </div>
        <input type="hidden" value="{{$product->id}}" name="id">
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary">Save</button>
    </div>
</form>